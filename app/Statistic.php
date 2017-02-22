<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'year',
        'period',
        'statistics',
    ];

    public $timestamps = false;

}
