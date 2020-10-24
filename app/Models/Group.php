<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = 
    [
        'espelho', 'status', 'anoformacao', 'datasituacao', 'ultimoenvio', 
        'area', 'uf', 'telefone', 'contato', 'titulo', 'lideres'
    ];
}
