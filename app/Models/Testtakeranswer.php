<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testtakeranswer extends Model
{
    use HasFactory;

    protected $table = "test_taker_answers";

    protected $fillable = [
        'taker_id',
        'category',
        'category_id',
        'question',
        'question_id',
        'option_one',
        'option_two',    
        'option_three',  
        'option_four',    
        'correct', 
        'marks', 
        'selected_option',
        'test_id'
    ];

}
