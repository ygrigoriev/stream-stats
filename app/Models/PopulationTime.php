<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopulationTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'population_time';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'immutable_datetime',
    ];
}
