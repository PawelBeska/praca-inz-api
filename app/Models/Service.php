<?php

namespace App\Models;

use App\Enums\ServiceStatusEnum;
use App\Enums\VerificationTypeEnum;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int $user_id
 * @property string $name
 * @property Carbon $valid_until
 * @property ServiceStatusEnum $status
 * @property VerificationTypeEnum $type
 * @property string $private_key
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class Service extends Model
{
    use HasFactory, HasUuid;

    protected $guarded = [];
    /**
     * @var mixed|string|null
     */
    protected $casts = [
        'valid_until' => 'datetime',
        'status' => ServiceStatusEnum::class,
    ];

    protected $dates = ['created_at', 'updated_at', 'valid_until'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifications(): HasMany
    {
        return $this->HasMany(Verification::class);
    }
}
