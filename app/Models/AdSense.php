<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSense extends Model
{
    use HasFactory;

    protected $table = 'adsense';

    protected $fillable = [
        'titulo',
        'descricao',
        'codigo_adsense',
        'posicao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes para posições
    const POSICOES = [
        'header' => 'Cabeçalho',
        'sidebar' => 'Barra Lateral',
        'footer' => 'Rodapé',
        'content' => 'Conteúdo'
    ];

    // Acessor para obter o nome da posição
    public function getPosicaoNomeAttribute()
    {
        return self::POSICOES[$this->posicao] ?? $this->posicao;
    }

    // Scope para buscar apenas anúncios ativos
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    // Scope para buscar por posição
    public function scopePorPosicao($query, $posicao)
    {
        return $query->where('posicao', $posicao);
    }
}
