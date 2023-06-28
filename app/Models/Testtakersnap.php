<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testtakersnap extends Model
{
    use HasFactory;

    protected $table = "test_taker_snap";

    protected $fillable = [
        'taker_id',
        'test_id',
        'snap',
    ];
}
