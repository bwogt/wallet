<?php

namespace App\Http\Controllers;

use App\Actions\Deposit\DepositAction;
use App\Actions\Transfer\TransferAction;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Deposit\DepositRequest;
use App\Http\Requests\Transfer\TransferRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\Transfer\TransferResource;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function deposit(DepositRequest $request, DepositAction $action): JsonResponse
    {
        $transaction = $action($request->toDTO());

        return response()->json(
            FlashMessage::success(trans('flash_messages.success.deposit'))
                ->merge(['data' => TransactionResource::make($transaction)]),
            JsonResponse::HTTP_CREATED
        );
    }

    public function transfer(TransferRequest $request, TransferAction $action): JsonResponse
    {
        $transaction = ($action)($request->toDTO());

        return response()->json(
            FlashMessage::success(trans('flash_messages.success.transfer'))
                ->merge(['data' => TransferResource::make($transaction)]),
            JsonResponse::HTTP_CREATED
        );
    }
}
