<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
   protected $fillable = [
        'nome_original',
        'caminho',
    ];
}
