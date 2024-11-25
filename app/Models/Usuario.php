<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    // Método para obter a senha do usuário (Laravel espera o campo 'password')
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Relacionamento com NoiteDeSono
    public function noitesDeSono()
    {
        return $this->hasMany(NoiteDeSono::class, 'usuario_id');
    }
}
