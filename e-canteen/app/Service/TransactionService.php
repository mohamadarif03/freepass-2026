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
}
