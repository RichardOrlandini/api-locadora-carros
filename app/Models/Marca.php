<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'imagem'
    ];

    public function rules() {

        return  [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png'
        ];
        //Unique ignora o id no 3ª parâmetro.
       
    }

    public function feedback() {

        return [
            'required' => 'O campo :attribute é obrigatório.',
            'imagem.mimes' => 'O arquivo deve ser apenas do tipo PNG.',
            'nome.unique' => 'O nome já existe.',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
        ];
    }
}
