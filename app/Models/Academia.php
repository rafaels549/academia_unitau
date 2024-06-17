<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpenDay;

class Academia extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'capacidade', 'max_faltas'];
    public function schedules(){
          return $this->hasMany(OpenDay::class,"academia_id");
    }

    public function eventos(){
         return $this->hasMany(Evento::class,"academia_id");
    }

    public function specificDates() {
        return $this->hasMany(SpecificDate::class, "academia_id");
    }

    public function usuarios(){
           return $this->belongToMany(User::class,"usuarios_evento","academia_id","user_id");
    }


}
