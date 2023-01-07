<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guest();
    }

    public function rules(): array
    {
        return [
            'token' => ['string', 'required'],
            'password' => ['string', 'required', 'confirmed', 'min:8'],
            'password_confirmation' => ['string', 'required', 'min:8']
        ];
    }
}
