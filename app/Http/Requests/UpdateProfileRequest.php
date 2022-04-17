<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "full_name" => ['required', 'string', 'max:255'],
            "password" => ['required', 'current_password', 'string', 'max:255'],
            "new_password" => ['required', 'string', 'confirmed', 'max:255'],
            "new_password_confirmation" => ['required', 'string', 'max:255']
        ];
    }
}
