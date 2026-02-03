<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TransactionInterface;
use App\Models\MenuTransaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository implements TransactionInterface
{
    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function store(array $data): mixed
    {
        return DB::transaction(function () use ($data) {
            $transaction = $this->model->create([
                'user_id' => $data['user_id'],
                'payment_method' => $data['payment_method'],
                'status_payment' => $data['status_payment'] ?? 'unpaid',
                'status' => $data['status'] ?? 'pending',
                'total_amount' => $data['total_amount'],
            ]);

            foreach ($data['menus'] as $menu) {
                MenuTransaction::create([
                    'transaction_id' => $transaction->id,
                    'menu_id' => $menu['menu_id'],
                    'quantity' => $menu['quantity'],
                ]);
            }

            return $transaction;
        });
    }
}
