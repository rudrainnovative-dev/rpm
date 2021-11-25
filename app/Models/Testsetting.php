<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testsetting extends Model
{
    use HasFactory;

    protected $table = "test_settings";

    protected $fillable = [
        'test_id',
        'criteria_id'
    ];
}
