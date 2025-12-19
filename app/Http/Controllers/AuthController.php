<?php

namespace App\Http\Controllers;

use App\Actions\Auth\Consumer\RegisterConsumerAction;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Auth\Consumer\Register\RegisterConsumerRequest;
use App\Http\Resources\Auth\LoginResource;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function store(RegisterConsumerRequest $request, RegisterConsumerAction $action)
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
