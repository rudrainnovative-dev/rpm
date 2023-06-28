<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordersetting extends Model
{
    use HasFactory;

    protected $table = "order_settings";

    protected $fillable = [
        'test_id',
        'section_id',
        'order'
    ];
}
