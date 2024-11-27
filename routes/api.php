<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NoiteDeSonoController;
use App\Http\Controllers\MetaController;

// Rotas públicas
Route::post('/cadastro', [UsuarioController::class, 'cadastro']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::get('/', [UsuarioController::class, 'listarUsuarios']);

// Rotas protegidas pelo middleware de autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UsuarioController::class, 'logout']);
    Route::post('/noites-de-sono', [NoiteDeSonoController::class, 'adicionarNoiteDeSono']);
    Route::get('/noites-de-sono', [NoiteDeSonoController::class, 'listarNoitesDeSono']);

    // Rotas para Metas
    Route::post('/metas', [MetaController::class, 'adicionarMeta']);
    Route::get('/metas', [MetaController::class, 'listarMetas']);
    Route::delete('/metas/{id}', [MetaController::class, 'deletarMeta']);
    Route::put('/metas/{id}', [MetaController::class, 'atualizarMeta']);
});
