<?php

namespace App\Http\Controllers;

use App\Models\NoiteDeSono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class NoiteDeSonoController extends Controller
{
    // Método para adicionar uma noite de sono
    public function adicionarNoiteDeSono(Request $request)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado
    
        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'data' => 'required|date',
            'horas_dormidas' => 'required|integer|min:1|max:24',
        ]);
    
        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Verificar se já existe uma noite de sono para este usuário e data
        $existeNoiteDeSono = NoiteDeSono::where('usuario_id', $usuario->id)
            ->where('data', $request->input('data'))
            ->exists();
    
        if ($existeNoiteDeSono) {
            return response()->json([
                'message' => 'Você já registrou uma noite de sono para esta data.'
            ], 400);
        }
    
        try {
            // Cria a nova noite de sono
            NoiteDeSono::create([
                'usuario_id' => $usuario->id,
                'data' => $request->input('data'),
                'horas_dormidas' => $request->input('horas_dormidas'),
            ]);
        } catch (QueryException $e) {
            // Trata a violação da restrição única do banco de dados
            if ($e->getCode() == '23000') { // Código SQLSTATE para violação de restrição de integridade
                return response()->json([
                    'message' => 'Você já registrou uma noite de sono para esta data.'
                ], 400);
            } else {
                // Repassa a exceção se não for relacionada à restrição única
                throw $e;
            }
        }
    
        // Obtém todas as noites de sono do usuário, ordenadas por data
        $noitesDeSono = $usuario->noitesDeSono()
            ->orderBy('data', 'asc')
            ->get();
    
        // Retorna as noites de sono
        return response()->json([
            'message' => 'Noite de sono adicionada com sucesso!',
            'data' => $noitesDeSono
        ], 201);
    }
    

    // Método para listar as noites de sono do usuário autenticado
    public function listarNoitesDeSono(Request $request)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado

        // Obtém as noites de sono do usuário
        $noitesDeSono = $usuario->noitesDeSono()->get();

        // Retorna as noites de sono
        return response()->json([
            'message' => 'Noites de sono recuperadas com sucesso!',
            'data' => $noitesDeSono
        ], 200);
    }
}
