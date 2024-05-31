<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use Illuminate\Http\Request;
use App\Models\Evento;
use Carbon\Carbon;

class EventoController extends Controller
{
    public function store(Request $request){
        
           $request->validate([
                  "date" =>"required",
                  "time"=>"required"
           ]);

           $date = Carbon::parse($request->date)->format('Y-m-d');
           $time = Carbon::parse($request->time)->format('H:i:s');
       
   
           $existingEvent = Evento::where('date', $date)
               ->where('time', $time)
               ->first();
       
         

            if(!$existingEvent) {
               $event = new Evento();
               $event->date = $date;
               $event->time = $time;
            
               $event->academia_id = 1;
               $event->save();
               $event->users()->attach(auth()->user()->id);
            } else {
               if($existingEvent->users()->count() > $existingEvent->academia->capacidade) {
                  throw \Illuminate\Validation\ValidationException::withMessages([
                     'date' => ['O evento já está lotado para esta data e hora.']
                 ]);
               }
               $existingEvent->users()->attach(auth()->user()->id);
            }
            
         
          
    }


    public function update(Evento $event,Request $request){
        $request->validate([
            "date" =>"required",
            "time"=>"required"
     ]);
        try{
               $event->date = $request->date;
               $event->time = $request->time;
               
               $event->save();

            
           return response()->json(['success' => 'OK'
        ], 200);
        }catch(\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
             }
        
               
    }

    public function getEvents(){

         try{
        $events = auth()->user()->eventos;
          
        return response()->json(["events"=> EventoResource::collection($events)],200);
         }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
         }
}

public function getAllEvents() {
   try{
      $events = Evento::orderBy("created_at", "desc")->get();
        
      return response()->json(["events"=> EventoResource::collection($events)],200);
       }catch(\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 400);
       }
}



 
}