<?php

namespace App\Repository;

use App\Models\Transaction;
use App\Models\TransactionProduct;

class TransactionRepository
{
    public function getAll(array $fields)
    {
        return Transaction::select($fields)
            ->with(['transactionProduct.product', 'merchant.keeper'])
            ->latest()
            ->paginate(10);
    }
    public function getById(int $id, array $fields)
    {
        return Transaction::select($fields)
            ->with(['transactionProduct.product', 'merchant.keeper'])
            ->findOrFail();
    }
    public function create(array $data)
    {
        return Transaction::create($data);
    }
    public function update(int $id, array $data)
    {
        $transaction  = Transaction::findOrFail($id);
        return $transaction->update($data);
    }
    public function delete(int $id)
    {
        $transaction = Transaction::findOrFail($id);
        return $transaction->delete();
    }
    public function createTransactionProduct(int $transactionId, array $products)
    {
        foreach ($products as $product) {
            $subTotal = $product['quantity'] * $product['price'];
            TransactionProduct::create([
                'transaction_id' => $transactionId,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'sub_total' => $subTotal
            ]);
        }
    }
    public function getTransactionByMerchant(int $merchantId)
    {
        return Transaction::where('merchant_id', $merchantId)
            ->select(['*'])
            ->with(['merchant', 'transactionProduct.product'])
            ->get();
    }
}
