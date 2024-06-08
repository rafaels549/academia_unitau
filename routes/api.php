<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\AcademiaController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\Academia;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return new UserResource($request->user());
});


Route::middleware(['auth:sanctum'])->group( function () {

    Route::middleware('blocked')->group(function () {


    Route::prefix('events')->group(function(){
          Route::post("/create", [EventoController::class,"store"]); 
          Route::put("/update/{id}", [EventoController::class,"update"]);
          Route::delete("/delete/{id}",[ EventoController::class , "delete"]);
          Route::get("/get",[EventoController::class , "getEvents"]); 
          Route::get("/{id}",[EventoController::class , "getEvent"]);     
          Route::post("{id}/miss/{id1}",[AcademiaController::class,"miss"]);
          Route::post("{id}/presenca/{id1}",[AcademiaController::class,"presence"]);
          Route::patch("/cancelaragendamento/{id}", [EventoController::class, "cancelarAgendamento"]);
    });

    Route::prefix('academia')->group(function(){
        Route::post("/create", [AcademiaController::class,"store"]); 
        Route::put("/update", [AcademiaController::class,"update"])->middleware('admin');
        Route::delete("/delete", [AcademiaController::class , "delete"]);
      
        Route::get("/{id}",[AcademiaController::class , "getAcademia"]); 
     
         Route::get("{id}/permission/{id1}",[AcademiaController::class,"permit"]);
         Route::get('/getacademia', [AcademiaController::class, "getAcademia"])->middleware('admin');
      

         Route::get("{id}/events/",[ AcademiaController::class,"getAcademiaEvents"]);

         Route::get("{id}/event/{id1}",[AcademiaController::class,"getAcademiaEvent"]);
         Route::patch("/adicionardata", [AcademiaController::class, "adicionarData"])->middleware('admin');
         Route::patch("/removerdata", [AcademiaController::class, "removerData"])->middleware('admin');

                });
    
        Route::get('/users', [UserController::class, 'getUsers'])->middleware('admin');
        Route::post('/users/create', [UserController::class, 'addUser'])->middleware('admin');
        Route::post('/user-image', [UserController::class, 'updateUserImage']);
        Route::patch('/makeuser/{id}', [UserController::class, 'makeUser'])->middleware('admin');
        Route::patch('/makeadmin/{id}', [UserController::class, 'makeAdmin'])->middleware('admin');
        Route::patch('/block/{id}', [UserController::class, 'block'])->middleware('admin');
        Route::patch('/unblock/{id}', [UserController::class, 'unblock'])->middleware('admin');
        Route::get('/getall', [EventoController::class, "getAllEvents"])->middleware('admin');
        Route::get("/disabled-days", [GeneralController::class, "getDisabledDaysOfWeek"]);
        Route::patch('/disponible-hours', [GeneralController::class, 'getDisponibleHoursOfTheDate']);
        Route::get("/removedates", [GeneralController::class, "getRemovedDates"]);
        Route::get("/addeddates", [GeneralController::class, "getAddedDates"]);
    });
});

