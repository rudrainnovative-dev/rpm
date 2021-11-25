<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performancecriteriaoption extends Model
{
    use HasFactory;

    protected $table = "performance_criteria_options";

    protected $fillable = [
        'performance_criteria_id',
        'performance_type_id',
        'criteria',
        'formula',
        'score',
        'remarks'
    ];
}
