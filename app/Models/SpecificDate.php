<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificDate extends Model
{
    use HasFactory;

    protected $fillable = [
        "academia_id",
        "data",
        "start_hour",
        "end_hour",
        "type"
    ];

    protected $casts = [
         "data" => "date"
    ];
}
