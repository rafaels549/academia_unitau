<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ra',
        "is_admin",
        'is_blocked',
        'curso',
        'periodo',
        'documento'
    ];

    protected $appends = ['faltou'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function faltas(){
         return $this->hasMany(Falta::class, "user_id");
    }
    public function presencas(){
        return $this->hasMany(Presenca::class, "user_id");
    }
    public function eventos(){
        return $this->belongsToMany(Evento::class, "eventos_user","user_id","event_id");
    }


}
