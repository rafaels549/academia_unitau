<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class EventoController extends Controller
{
    public function store(Request $request){
        
           $request->validate([
                  "date" =>"required",
                  "time"=>"required"
           ]);

            $event = new Event();
            $event->date = $request->date;
            $event->time = $request->time;

            $event->attach(auth()->user()->id);
            $event->save();
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
        $events = Event::where("user_id",auth()->user()->id)->get();

          
        return response->json(["events"=>$events],200);
         }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
         }
}

 public function getEvent(Evento $event){
    try{
        

          
        return response->json(["events"=>$event],200);
         }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
         } 
 }

 public function delete(Evento $event){
    try{
        
              $event->delete();
          
        return response->json(["sucess"=>"OK"],200);
         }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
         } 
 }
}