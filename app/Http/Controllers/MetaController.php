<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class MetaController extends Controller
{
    /**
     * Método para adicionar uma nova meta.
     */
    public function adicionarMeta(Request $request)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado

        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Cria a nova meta
            $meta = Meta::create([
                'usuario_id' => $usuario->id,
                'titulo' => $request->input('titulo'),
                'descricao' => $request->input('descricao'),
            ]);
        } catch (QueryException $e) {
            // Trata possíveis erros de banco de dados
            return response()->json([
                'message' => 'Erro ao adicionar a meta. Tente novamente.'
            ], 500);
        }

        // Retorna a meta criada
        return response()->json([
            'message' => 'Meta adicionada com sucesso!',
            'data' => $meta
        ], 201);
    }

    /**
     * Método para listar todas as metas do usuário autenticado.
     */
    public function listarMetas(Request $request)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado

        // Obtém as metas do usuário, ordenadas por data de criação decrescente
        $metas = Meta::where('usuario_id', $usuario->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Retorna as metas
        return response()->json([
            'message' => 'Metas recuperadas com sucesso!',
            'data' => $metas
        ], 200);
    }

    /**
     * Método para deletar uma meta específica.
     */
    public function deletarMeta(Request $request, $id)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado

        // Encontra a meta pelo ID e pelo usuário
        $meta = Meta::where('id', $id)->where('usuario_id', $usuario->id)->first();

        if (!$meta) {
            return response()->json([
                'message' => 'Meta não encontrada.'
            ], 404);
        }

        try {
            $meta->delete();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro ao deletar a meta. Tente novamente.'
            ], 500);
        }

        return response()->json([
            'message' => 'Meta deletada com sucesso!'
        ], 200);
    }

    /**
     * Método para atualizar uma meta específica.
     */
    public function atualizarMeta(Request $request, $id)
    {
        $usuario = $request->user(); // Obtém o usuário autenticado

        // Encontra a meta pelo ID e pelo usuário
        $meta = Meta::where('id', $id)->where('usuario_id', $usuario->id)->first();

        if (!$meta) {
            return response()->json([
                'message' => 'Meta não encontrada.'
            ], 404);
        }

        // Validação dos dados recebidos
        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Atualiza a meta com os dados fornecidos
            $meta->update($request->only(['titulo', 'descricao']));
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro ao atualizar a meta. Tente novamente.'
            ], 500);
        }

        return response()->json([
            'message' => 'Meta atualizada com sucesso!',
            'data' => $meta
        ], 200);
    }
}
