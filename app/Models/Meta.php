<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model 
{
    use HasFactory;

    protected $fillable = [ 
        'usuario_id',
        'titulo',
        'descricao',
    ];

    /**
     * Relação com o modelo User.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
