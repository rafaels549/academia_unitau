<?php

namespace App\Models;

use App\Enums\DaysOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenDay extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => DaysOfWeek::class
     ];
     
    protected $fillable = ['academia_id', 'day'];
   public function openDayTimes() {
    return $this->hasMany(OpenDayTimes::class);
   }
   
}
