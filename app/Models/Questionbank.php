<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionbank extends Model
{
    use HasFactory;

    protected $table = "questions_bank";

    protected $fillable = [
        'category_id',
        'title',
        'marks',
        'correct',
        'answers_justification',
        'user_id'
    ];


    public function category() {
        return $this->hasOne(Category::class,  'id', 'category_id');
    }

    public function options() {
        return $this->hasMany(Questionoption::class, 'question_id', 'id');
    }
}
