<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatGPTController;

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

Route::get('/chat', [ChatGPTController::class, 'index'])->name('chat.index');
Route::post('/chat/ask', [ChatGPTController::class, 'ask'])->name('get_chat');

Route::get('/', function () {
    return view('welcome');
});
