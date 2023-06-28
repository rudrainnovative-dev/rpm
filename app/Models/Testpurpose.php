<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testpurpose extends Model
{
    use HasFactory;

    protected $table = "test_purpose";

    protected $fillable = [
        'purpose',
    ];
}
