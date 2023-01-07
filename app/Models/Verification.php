<?php

namespace App\Models;

use App\Enums\VerificationTypeEnum;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $service_id
 * @property VerificationTypeEnum $type
 * @property Carbon $valid_until
 * @property string $control
 * @property bool $active
 * @property mixed $ip_address
 */
class Verification extends Model
{
    use HasFactory, HasUuid;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'valid_until' => 'datetime',
        'type' => VerificationTypeEnum::class,
    ];
    protected $dates = ['valid_until'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
