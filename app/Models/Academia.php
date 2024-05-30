<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpenDay;

class Academia extends Model
{
    use HasFactory;


    public function open_days(){
          return $this->hasMany(OpenDay::class,"academia_id");
    }

    public function eventos(){
         return $this->hasMany(Evento::class,"academia_id");
    }
}
