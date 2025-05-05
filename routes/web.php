<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChamadoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/chamados/create', [ChamadoController::class, 'create'])->name('chamados.create');
Route::post('/chamados', [ChamadoController::class, 'store'])->name('chamados.store');
Route::get('/chamados/{chamado}/edit', [ChamadoController::class, 'edit'])->name('chamados.edit');
Route::post('/chamados/{chamado}', [ChamadoController::class, 'update'])->name('chamados.update');
Route::put('/chamados/{chamado}/historico-situacao', [ChamadoController::class, 'salvarHistoricoSituacao'])->name('chamados.salvarHistoricoSituacao');
Route::resource('chamados', ChamadoController::class)->except(['create']); // Nesta rota o create method possui link em "Novo Chamado"
Route::post('/chamados/filtrar', [ChamadoController::class, 'filtrarChamados'])->name('chamados.filtrar');
