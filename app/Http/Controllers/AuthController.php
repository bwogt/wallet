<?php

namespace App\Http\Controllers;

use App\Actions\Auth\Login\LoginAction;
use App\Actions\Auth\Register\RegisterConsumerAction;
use App\Actions\Auth\Register\RegisterMerchantAction;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Auth\Consumer\Register\RegisterConsumerRequest;
use App\Http\Requests\Auth\Login\LoginUserRequest;
use App\Http\Requests\Auth\Register\RegisterMerchantRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request, LoginAction $action): JsonResponse
    {
        $loginDTO = $action($request->toDTO());

        return response()->json(
            FlashMessage::success(trans('flash_messages.success.login'))
                ->merge(['data' => new LoginResource($loginDTO)]),
            Response::HTTP_OK
        );
    }

    public function logout(): JsonResponse
    {
        request()->user()->tokens()->delete();

        return response()->json(
            FlashMessage::success(trans('flash_messages.success.logout')),
            Response::HTTP_OK
        );
    }

    public function registerConsumer(RegisterConsumerRequest $request, RegisterConsumerAction $action): JsonResponse
    {
        $loginDTO = $action($request->toDTO());

        return response()->json(
            FlashMessage::success(trans_choice('flash_messages.success.registered.m', 1, [
                'model' => trans_choice('model.user', 1),
            ]))->merge(['data' => new LoginResource($loginDTO)]),
            Response::HTTP_CREATED
        );
    }

    public function registerMerchant(RegisterMerchantRequest $request, RegisterMerchantAction $action): JsonResponse
    {
        $loginDTO = $action($request->toDTO());

        return response()->json(
            FlashMessage::success(trans_choice('flash_messages.success.registered.m', 1, [
                'model' => trans_choice('model.user', 1),
            ]))->merge(['data' => new LoginResource($loginDTO)]),
            Response::HTTP_CREATED
        );
    }
}
