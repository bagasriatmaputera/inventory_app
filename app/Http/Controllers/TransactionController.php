<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function index()
    {
        $fields = ['*'];
        $transaction = $this->transactionService->getAll($fields);
        return response()->json(TransactionResource::collection($transaction));
    }
    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        return response()->json([
            'message' => 'Create transaction successfully',
            'data' => $transaction
        ]);
    }
    public function show(int $transactionId)
    {
        $fields = ['*'];
        try {
            $transaction = $this->transactionService->getTransactionById($transactionId, $fields);
            return response()->json(new TransactionResource($transaction), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 404);
        }
    }
    public function getTransactionByMerchant()
    {
        $user = auth()->user();

        if (!$user || !$user->merchant) {
            return response()->json([
                'message' => 'NO merchant assigned'
            ], 403);
        }

        $merchantId = $user->merchant->id;

        $transaction = $this->transactionService->getTransactionByMerchant($merchantId, $fields ?? ['*']);
        return response()->json($transaction);
    }
}
