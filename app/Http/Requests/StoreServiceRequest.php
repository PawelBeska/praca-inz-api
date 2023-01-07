<?php

namespace App\Http\Requests;

use App\Dto\ServiceDto;
use App\Enums\ServiceStatusEnum;
use App\Interfaces\RequestToDtoInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreServiceRequest extends FormRequest implements RequestToDtoInterface
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDto(): ServiceDto
    {
        return new ServiceDto(
            $this->get('name'),
            Auth::user(),
            ServiceStatusEnum::ACTIVE,
            now()->addMonth(),
            Str::uuid()
        );
    }
}
