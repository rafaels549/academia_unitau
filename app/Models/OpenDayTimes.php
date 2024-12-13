<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenDayTimes extends Model
{
    use HasFactory;
    protected $fillable = ['opening_time', 'closing_time'];
}
