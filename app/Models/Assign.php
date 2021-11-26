<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    use HasFactory;

    protected $table = "assign_tests";

    protected $fillable = [
        
    ];

    public function candidates() {
        return $this->hasMany(Assigncandidate::class, 'assign_id', 'id');
    }
}
