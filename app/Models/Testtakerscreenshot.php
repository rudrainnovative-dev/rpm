<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testtakerscreenshot extends Model
{
    use HasFactory;

    protected $table = "test_taker_screen_shot";

    protected $fillable = [
        'taker_id',
        'test_id',
        'snap',
    ];
}
