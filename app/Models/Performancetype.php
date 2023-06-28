<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performancetype extends Model
{
    use HasFactory;

    protected $table = "performance_types";

    protected $fillable = [
        'name',
    ];
    
}
