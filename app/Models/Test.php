<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = "tests";

    protected $fillable = [
        'public_id',    
        'name',
        'purpose_id',
        'assessment_type', 
        'assessment_time_from',
        'assessment_time_to',
        'timezone',
        'duration',
        'status'
    ];

    public function purpose() {
        return $this->hasOne(Testpurpose::class, 'id', 'purpose_id');
    } 

    public function test_questions() {
        return $this->hasMany(Testquestion::class, 'test_id', 'id');
    }

    public function test_settings() {
        return $this->hasMany(Testsetting::class, 'test_id', 'id');
    }

    public function order_settings() {
        return $this->hasMany(Ordersetting::class, 'test_id', 'id');
    }

    public function section_settings() {
        return $this->hasMany(Sectionsetting::class, 'test_id', 'id');
    }

    public function test_sections() {
        return $this->hasMany(Testquestion::class, 'test_id', 'id')->groupBy('category_id');
    }
}
