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
        'timezone',
        'duration',
        'status',
        'start_message',
        'end_message',
    ];

    public function purpose() {
        return $this->hasOne(Testpurpose::class, 'id', 'purpose_id');
    }

    public function test_questions() {
        return $this->hasMany(Testquestion::class, 'test_id', 'id');
    }

    public function test_sections() {
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

    public function registation_fields() {
        return $this->hasMany(Registationfield::class, 'test_id', 'id')->where('allow',1);   
    }

    public function taker() {
        return $this->hasMany(Assigncandidate::class, 'test_id', 'id');   
    }
}
