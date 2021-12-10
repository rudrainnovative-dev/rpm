<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function answers() {
        return $this->hasMany(Testtakeranswer::class, 'taker_id', 'id');
    }

}
