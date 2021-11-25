<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testquestion extends Model
{
    use HasFactory;

    protected $table = "test_questions";

    protected $fillable = [
        'test_id',    
        'question_id',
        'category_id',
        'options'
    ];

    public function purpose() {
        return $this->hasOne(Testpurpose::class,  'id', 'purpose_id');
    } 

    public function question() {
      return $this->hasOne(Questionbank::class,  'id', 'question_id');  
    }

    public function category() {
      return $this->hasOne(Category::class,  'id', 'category_id');  
    }
}
