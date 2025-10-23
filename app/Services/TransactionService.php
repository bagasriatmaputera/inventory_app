<?php

namespace App\Services;

use App\Repository\MerchantProductRepository;
use App\Repository\MerchantRepository;
use App\Repository\ProductRepository;
use App\Repository\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    private TransactionRepository $transactionRepository;
    private MerchantProductRepository $merchantProductRepository;
    private ProductRepository $productRepository;
    private MerchantRepository $merchantRepository;
    public function __construct(
        TransactionRepository $transactionRepository,
        MerchantProductRepository $merchantProductRepository,
        ProductRepository $productRepository,
        MerchantRepository $merchantRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->merchantProductRepository = $merchantProductRepository;
        $this->productRepository = $productRepository;
        $this->merchantRepository = $merchantRepository;
    }
    public function getAll(array $fields)
    {
        return $this->transactionRepository->getAll($fields);
    }
    public function getTransactionById(int $id, array $fields)
    {
        $transaction = $this->transactionRepository->getById($id, $fields ?? ['*']);
        if (!$transaction) {
            throw ValidationException::withMessages([
                'transaction' => ['Transaction not found']
            ]);
        }
        return $transaction;
    }
    public function getTransactionByMerchant(int $merchantId, array $fields)
    {
        $transactionMerchant = $this->transactionRepository->getTransactionByMerchant($merchantId);
        if (!$transactionMerchant) {
            throw ValidationException::withMessages([
                'transaction_merchant' => ['Transaction not found']
            ]);
        }
        return $transactionMerchant;
    }
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $merchant = $this->merchantRepository->getById($data['merchant_id'], ['id', 'keeper_id']);

            if (!$merchant) {
                throw ValidationException::withMessages([
                    'mechant_id' => ['Merchant not found']
                ]);
            }

            if (Auth::id() !== $merchant->keeper_id) {
                throw ValidationException::withMessages([
                    'authotization' => ['You only can prosses transaction fot yout assigned merchant']
                ]);
            }

            $product = [];
            $subTotal = 0;

            foreach ($product as $productData) {
                $merchantProduct = $this->merchantProductRepository->getByMerchantProduct($data['merchant_id'], $productData['product_id']);

                if (!$merchantProduct || $merchantProduct->stock < $productData['quantity']) {
                    throw ValidationException::withMessages(
                        [
                            'stock' => ['Insufficient stock for product ID:' . $productData['product_id']]
                        ]
                    );
                }

                $product = $this->productRepository->getById($productData['product_id'], ['id', 'price']);
                if (!$product) {
                    throw ValidationException::withMessages([
                        'product_id' => ["Product with ID {$productData['product_id']} not found"]
                    ]);
                }

                $price = $product->price;
                $productSubTotal = $productData['quantity'] * $price;
                $subTotal += $productSubTotal;

                $products[] = [
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $price,
                    'sub_total' => $subTotal
                ];

                $newStock = max(0, $merchantProduct->stock - $productData['quantity']);

                $this->merchantProductRepository->updateStock($newStock, $data['merchant_id'], $productData['product_id']);
            }

            $taxTotal = $subTotal * 0.1;
            $grandTotal = $subTotal - $taxTotal;

            $transaction = $this->transactionRepository->create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'merchant_id' => $data['merchant_id'],
                'sub_total' => $subTotal,
                'tax_total' => $taxTotal,
                'grand_total' =>  $grandTotal
            ]);

            $transactionProduct = $this->transactionRepository->createTransactionProduct($transaction->id, $products);

            return $transaction->fresh();
        });
    }
}
