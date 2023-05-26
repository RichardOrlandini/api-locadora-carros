<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'air_bag',
        'abs'
    ];

    public function rules() {

        return  [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
        //Unique ignora o id no 3ª parâmetro.
       
    }

    public function feedback() {

        return [
            'required' => 'O campo :attribute é obrigatório.',
            'imagem.mimes' => 'O arquivo deve ser apenas do tipo PNG,JPEG,JPG.',
            'nome.unique' => 'O nome já existe.',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
        ];
    }
}
