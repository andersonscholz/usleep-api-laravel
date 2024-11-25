<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\TestControllers;
use App\Http\Controllers\NoiteDeSonoController;

Route::post('/cadastro', [UsuarioController::class, 'cadastro']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UsuarioController::class, 'logout']);
    Route::post('/noites-de-sono', [NoiteDeSonoController::class, 'adicionarNoiteDeSono']);
    Route::get('/noites-de-sono', [NoiteDeSonoController::class, 'listarNoitesDeSono']);
});
Route::post('/solicitar-redefinicao-senha', [UsuarioController::class, 'solicitarRedefinicaoSenha']);
Route::post('/redefinir-senha', [UsuarioController::class, 'redefinirSenha']);