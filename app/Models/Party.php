<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'date',
            'duration',
            'capacity',
            'description',
            'cover_photo',
        ];
}
