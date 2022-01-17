<?php
namespace App\Traits;

use Illuminate\Support\Str;
trait HasUuid
{

    /**
     * Boot function from Laravel.
     */
    public static function bootUsesUuid(): void
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }
    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
