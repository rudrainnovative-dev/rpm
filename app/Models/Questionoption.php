<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionoption extends Model
{
    use HasFactory;

    protected $table = "question_options";

    protected $fillable = [
        'question_id',
        'option',
        'user_id'
    ];
}
