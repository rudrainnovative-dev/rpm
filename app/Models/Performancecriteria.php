<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performancecriteria extends Model
{
    use HasFactory;

    protected $table = "performance_criterias";

    protected $fillable = [
        'from',
        'to'
    ];

    public function options() {
        return $this->hasMany(Performancecriteriaoption::class, 'performance_criteria_id', 'id');
    }
}
