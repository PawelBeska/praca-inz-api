<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $description
 * @property mixed $name
 * @property mixed $slug
 * @property mixed $id
 */
class Role extends Model
{
    use HasFactory;


    public static function getDefaultRole(): Role
    {
        return self::first();
    }

}
