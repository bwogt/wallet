<?php

namespace App\Http\Requests\Base;

use App\Exceptions\Validation\ValidationRequestMessagesException;
use App\Http\Messages\FlashMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiFormRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new ValidationRequestMessagesException($validator))
            ->setFlashMessage($this->flashMessage())
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Generate a flash message for the request.
     */
    public function flashMessage(): FlashMessage
    {
        return FlashMessage::error(trans('flash_messages.errors.form_request'));
    }
}
