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
        'email',    
        'test_id',
        'start',
        'end', 
        'resume',
        'share'
    ];

}
