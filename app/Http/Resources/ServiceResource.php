<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

/**
 * @mixin Service
 */
class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'status' => $this->status->value,
            'valid_until' => $this->valid_until->format('d-m-Y H:i:s'),
            'private_key' => Crypt::decrypt($this->private_key),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
