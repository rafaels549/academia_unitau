<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academia;

class Evento extends Model
{
use HasFactory;

public function users(){
return $this->belongsToMany(User::class, "eventos_user","event_id","user_id");
}

public function academia(){
    return $this->belongsTo(Academia::class,"academia_id");
}


}