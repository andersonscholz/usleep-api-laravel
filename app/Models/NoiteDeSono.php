<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoiteDeSono extends Model
{
    protected $table = 'noites_de_sono';

    protected $fillable = [
        'usuario_id',
        'data',
        'horas_dormidas',
    ];

    // Relacionamento com o modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Acessor para obter a qualidade do sono
    public function getQualidadeAttribute()
    {
        $horas = $this->horas_dormidas;

        if ($horas >= 1 && $horas <= 3) {
            return 'Muito Ruim';
        } elseif ($horas >= 4 && $horas <= 5) {
            return 'Ruim';
        } elseif ($horas == 6) {
            return 'Ok';
        } elseif ($horas == 7) {
            return 'Bom';
        } elseif ($horas >= 8) {
            return 'Excelente';
        } else {
            return 'Indefinido';
        }
    }
}
