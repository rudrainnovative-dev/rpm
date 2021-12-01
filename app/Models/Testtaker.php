<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testtaker extends Model
{
    use HasFactory;

    protected $table = "test_takers";

    protected $fillable = [
        'name',
        'email',
        'dob',
        'gender',
        'mobile',
        'avatar',
        'id_card',
        'test_id',
        'test_name'

    ];
}
