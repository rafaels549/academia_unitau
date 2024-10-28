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
                  "time"=>"required",
                  "service" => "required"
           ]);

           try {



           $date = Carbon::parse($request->date)->format('Y-m-d');
           $time = Carbon::parse($request->time)->format('H:i:s');


           $existingEvent = Evento::where('date', $date)
               ->where('time', $time)
               ->first();

              



         if(auth()->user()->eventos()->where("event_id",$existingEvent?->id)->exists()) {
            return response()->json(['errors' => "Você já possui um evento para esta data e hora"], 400);
         }

         


            if(!$existingEvent) {


               $event = new Evento();
               $event->date = $date;
               $event->time = $time;
               $event->service = $request->service;
               $event->academia_id = 1;
               $event->save();
               $event->users()->attach(auth()->user()->id);
            } else {
             



            }

         }   catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
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

    public function getEvents()
    {
        try {
         
            $events = auth()->user()->eventos()->orderBy("created_at", "desc")->get();
            
            return response()->json(["events" => EventoResource::collection($events)], 200);
        } catch (\Exception $e) {
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

public function cancelarAgendamento($id) {
 $event = Evento::findOrFail($id);
 $event->users()->detach(auth()->user()->id);
 if($event->users()->count() < 1) {
   $event->delete();
 }
}

public function delete(Evento $evento) {
   $evento->delete();
}






}
