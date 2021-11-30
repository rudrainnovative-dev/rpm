<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "question_categories";

    protected $fillable = [
        'name',
    ];

    public function questions() {
        return $this->hasMany(Testquestion::class,  'id', 'category_id');
    } 
}
