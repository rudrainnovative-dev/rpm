<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sectionsetting extends Model
{
    use HasFactory;

    protected $table = "section_settings";

    protected $fillable = [
        'test_id',
        'section_id',
        'instruction'
    ];
}
