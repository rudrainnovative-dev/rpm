<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registationfield extends Model
{
    use HasFactory;

    protected $table = "test_registration_fields";

    protected $fillable = [
        'test_id',
        'field',    
        'code',
        'type',
        'required', 
        'user_id'
    ];

}
