<?php

namespace App\Service;

use App\Contracts\Interfaces\TransactionInterface;
use App\Contracts\Repositories\MenuRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionService
{
    private TransactionInterface $transactionRepository;
    private MenuRepository $menuRepository;
    private TripayService $tripayService;

    public function __construct(
        TransactionInterface $transactionRepository,
        MenuRepository $menuRepository,
        TripayService $tripayService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->menuRepository = $menuRepository;
        $this->tripayService = $tripayService;
    }

    public function createTransaction(array $data, $user)
    {
        $totalAmount = 0;
        $orderItems = [];
        $menuData = [];

        foreach ($data['menus'] as $item) {
            $menu = $this->menuRepository->getById($item['menu_id']);

            if ($menu->stock < $item['quantity']) {
                throw new Exception("Stock for menu '{$menu->name}' is insufficient. Available: {$menu->stock}, Requested: {$item['quantity']}");
            }

            $price = $menu->price;
            $quantity = $item['quantity'];
            $subtotal = $price * $quantity;
            $totalAmount += $subtotal;

            $menuData[] = [
                'menu_id' => $menu->id,
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'sku' => $menu->id,
                'name' => $menu->name,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        $transactionData = [
            'user_id' => $user->id,
            'payment_method' => $data['payment_method'] === 'cash' ? 'cash' : 'digital',
            'status_payment' => 'unpaid',
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'menus' => $menuData
        ];

        $transaction = $this->transactionRepository->store($transactionData);

        if ($data['payment_method'] !== 'cash') {
            try {
                $tripayResponse = $this->tripayService->requestTransaction(
                    $data['method_code'],
                    $transaction,
                    $orderItems,
                    $user
                );

                if (isset($tripayResponse['data']['merchant_ref'])) {
                    $this->transactionRepository->update($transaction->id, [
                        'merchant_ref' => $tripayResponse['data']['merchant_ref']
                    ]);
                }

                return [
                    'transaction' => $transaction,
                    'tripay' => $tripayResponse,
                ];
            } catch (Exception $e) {
                throw new Exception('Tripay Error: ' . $e->getMessage());
            }
        }

        return ['transaction' => $transaction];
    }

    public function handleCallback(array $tripayData)
    {
        $transaction = $this->transactionRepository->getByReference($tripayData['merchant_ref']);


        if (!$transaction) {
            return ['success' => false, 'message' => 'Transaction not found'];
        }

        if ($transaction->status_payment === $tripayData['status_payment']) {
            return ['success' => true, 'message' => 'Transaction already processed'];
        }

        if ($tripayData['status_payment'] === 'paid' && $transaction->status_payment !== 'paid') {
            foreach ($transaction->menus as $menuTransaction) {
                $menu = $this->menuRepository->getById($menuTransaction->menu_id);
                if ($menu) {
                    $this->menuRepository->update($menu->id, [
                        'stock' => $menu->stock - $menuTransaction->quantity
                    ]);
                }
            }
        }

        $this->transactionRepository->update($transaction->id, [
            'status_payment' => $tripayData['status_payment']
        ]);

        return ['success' => true, 'message' => 'Transaction updated successfully'];
    }
}
