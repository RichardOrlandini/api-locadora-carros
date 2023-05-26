<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller {

    private $modelo;
    
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }
    
    public function index()  {
   
        $modelos = $this->modelo->all();
        return response()->json($modelos, 200);
    }
   
    public function store(Request $request) {

        $request->validate($this->modelo->rules());

        $image = $request->file('imagem');
        $imagem_urn =  $image->store('imagens/modelos', 'public');

       $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->lugares,
       ]);
        
       return response()->json($modelo, 201);
    }

    public function show($id) {
    
        $modelo = $this->modelo->find($id);

        if ($modelo === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($modelo, 200);
    }

    public function update(Request $request,  $id)  {

        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'], 404);
        }

        if ($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            //aplicando regras apenas nos parâmetros da requisição.
            foreach($modelo->rules() as $input => $regra){

                if (array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($modelo->rules());
        }

        //remove a img antiga, caso tenha uma nova img na request.
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }
        
        $image = $request->file('imagem');
        $imagem_urn =  $image->store('imagens/modelos', 'public');

        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->lugares,
        ]);

        return response()->json($modelo, 200);
    }

    public function destroy($id) {
    
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'], 404);
        }

        //remove a img.
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'O modelo foi removida com sucesso!'], 200);
    }
}
