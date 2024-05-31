<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return new UserResource($request->user());
});


Route::middleware(['auth:sanctum'])->group( function () {
    Route::prefix('events')->group(function(){
          Route::post("/create", [EventoController::class,"store"]); 
          Route::put("/update/{id}", [EventoController::class,"update"]);
          Route::delete("/delete/{id}",[ EventoController::class , "delete"]);
          Route::get("/get",[EventoController::class , "getEvents"]); 
          Route::get("/{id}",[EventoController::class , "getEvent"]);     
       
    });

    Route::prefix('academia')->group(function(){
        Route::post("/create", [AcademiaController::class,"store"]); 
        Route::put("/update/{id}", [AcademiaController::class,"update"]);
        Route::delete("/delete", [AcademiaController::class , "delete"]);
      
        Route::get("/{id}",[AcademiaController::class , "getAcademia"]); 
     
         Route::get("{id}/permission/{id1}",[AcademiaController::class,"permit"]);

         Route::get("{id}/miss/{id1}",[AcademiaController::class,"miss"]);

         Route::get("{id}/events/",[ AcademiaController::class,"getAcademiaEvents"]);

         Route::get("{id}/event/{id1}",[AcademiaController::class,"getAcademiaEvent"]);
        });
    
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::post('/users/create', [UserController::class, 'addUser']);
        Route::post('/user-image', [UserController::class, 'updateUserImage']);
        Route::patch('/makeuser/{id}', [UserController::class, 'makeUser']);
        Route::patch('/makeadmin/{id}', [UserController::class, 'makeAdmin']);
        Route::get('/getall', [EventoController::class, "getAllEvents"]);


});
