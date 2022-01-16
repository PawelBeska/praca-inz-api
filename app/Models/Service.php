<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $uuid
 * @property mixed $valid_until
 * @property mixed $status
 * @property mixed $type
 * @property mixed $name
 * @property mixed $user_id
 */
class Service extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function verifications(): HasMany
    {
        return $this->HasMany(Verification::class);
    }
}
