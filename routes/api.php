<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ChanelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/update-client', [ClientController::class, 'update']);

Route::post('/chanels', [ChanelController::class, 'chanels']);
Route::post('/chanel/{chanel}', [ChanelController::class, 'chanel']);
Route::post('/save-chanel', [ChanelController::class, 'saveChanel']);
Route::post('/save-program/{chanel}', [ChanelController::class, 'saveProgram']);
Route::post('/delete-chanel/{chanel}', [ChanelController::class, 'deleteChanel']);
Route::post('/delete-program/{program}', [ChanelController::class, 'deleteProgram']);

Route::post('/export-chanel/{chanel}', [ChanelController::class, 'exportChanel']);
Route::post('/export-all-chanels', [ClientController::class, 'exportAllChanels']);

Route::post('/upload-file', [ChanelController::class, 'uploadFile']);
Route::post('/parse-file', [ChanelController::class, 'parseFile']);
