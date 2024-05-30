<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group( function () {
    Route::prefix('events')->group(function(){
          Route::post("/create", [EventoController::class,"store"]); 
          Route::put("/update/{id}", [EventoController::class,"update"]);
          Route::delete("/delete/{id}",[ EventoController::class , "delete"]);
          Route::get("/",[EventoController::class , "getEvents"]); 
          Route::get("/{id}",[EventoController::class , "getEvent"]);     
    });

    Route::prefix('academia')->group(function(){
        Route::post("/create", [AcademiaController::class,"store"]); 
        Route::put("/update/{id}", [AcademiaController::class,"update"]);
        Route::delete("/delete", [AcademiaController::class , "delete"]);
      
        Route::get("/{id}",[AcademiaController::class , "getAcademia"]); 
     
         Route::get("{id}/permission/{id}",[AcademiaController::class,"permit"]);

         Route::get("{id}/miss/{id}",[AcademiaController::class,"miss"]);

         Route::get("{id}/events/",[ AcademiaController::class,"getAcademiaEvents"]);

         Route::get("{id}/event/{id}",[AcademiaController::class,"getAcademiaEvent"]);
        });
    


});
