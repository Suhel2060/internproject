<?php

use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\TestMiddlewareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/signup',[PostApiController::class,'registerUser']);
Route::post('/login',[PostApiController::class,'loginUser']);


Route::middleware('auth:api')->group(function () {
    Route::get('/allpost',[PostApiController::class,'getAllPost']);
    Route::get('/getpost/{id}',[PostApiController::class,'showPost']);
    Route::put('/updatepost/{id}',[PostApiController::class,'updatePost']);
    Route::post('/createpost',[PostApiController::class,'createPost']);
    Route::delete('/deletepost/{id}',[PostApiController::class,'deletePost']);
});


Route::post('testmiddleware',[TestMiddlewareController::class,'test'])->middleware('custom.middleware');
