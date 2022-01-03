<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigncandidate extends Model
{
    use HasFactory;

    protected $table = "assign_candidates";

    protected $fillable = [
        'assign_id',
        'name',
        'email',    
        'test_id',
        'start',
        'end', 
        'resume',
        'share',
        'assessment_type',
        'status',
        'user_id',
    ];

    public function test() {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }
}
