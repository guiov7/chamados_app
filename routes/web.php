<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/chamados', [ChamadoController::class, 'store'])->name('chamados.store');
Route::resource('chamados', ChamadoController::class);

Route::put('/chamados/{chamado}/atualizar-situacao', [ChamadoController::class, 'atualizarSituacao'])->name('chamados.atualizarSituacao');
