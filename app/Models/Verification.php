<?php

namespace App\Models;

use App\Traits\HasUuidPrimaryKey;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $type
 * @property DateTime $valid_until
 * @property string $control
 * @property bool $active
 * @property int $service_id
 */
class Verification extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }
}
