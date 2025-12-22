<?php

namespace App\Http\Controllers;

use App\Actions\Consumer\DepositAction;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Deposit\DepositRequest;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function deposit(DepositRequest $request, DepositAction $action): JsonResponse
    {
        $action($request->toDTO());

        return response()->json(
            FlashMessage::success(trans('flash_messages.success.deposit')),
            JsonResponse::HTTP_CREATED
        );
    }
}
