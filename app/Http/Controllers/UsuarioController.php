<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    public function cadastro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $usuario = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => Hash::make($request->input('senha')), // Hash da senha
        ]);
    
        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'data' => $usuario
        ], 201);
    }
    

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $usuario = Usuario::where('email', $request->input('email'))->first();
    
        if ($usuario && Hash::check($request->input('senha'), $usuario->senha)) {
            $token = $usuario->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $usuario
            ], 200);
        } else {
            return response()->json([
                'message' => 'Credenciais inválidas.'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
  
    

    public function listarUsuarios()
    {
        // Recupera todos os usuários, excluindo campos sensíveis
        $usuarios = Usuario::select('id', 'nome', 'email', 'created_at', 'updated_at')->get();
    
        return response()->json([
            'message' => 'Usuários recuperados com sucesso!',
            'data' => $usuarios
        ], 200);
    }

    
}