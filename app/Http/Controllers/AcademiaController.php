<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Academia;
use App\Models\OpenDays;
use App\Models\Falta;
use App\Models\User;
class AcademiaController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "capacidade" =>"required",
            "telefone"=>"required",
              "days" => "required|array|min:1",
              'start_hour'=>"required",
              'end_hour'=>"required"
     ]);
        try{
       

         $academia = new Academia();
         $academia->phone= $request->telefone;
         $academia->capacidade= $request->capacidade;
         $days[]=$request->days;

                

               
          foreach($days as $day){
                   $open_day = new OpenDay();
                    $open_day->academia_id =$academia->id;
                    
                     
                    $open_day->day = $day;
                    $open_day->start_hour =$request->start_hour;
                    $open_day->end_hour = $request->end_hour;
                   

                  
                    $open_day->save();


                    


          }
          
         
         $academia->save();
    }catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
     }
 }


 public function update(Academia $academia,Request $request){
    $request->validate([
        "capacidade" => "required_without_all:telefone,days",
        "telefone" => "required_without_all:capacidade,days",
        "days" => "required|array|min:1",
        'start_hour'=>"required_without_all:capacidade,telefone",
         "end_hour"=>"required_without_all:capacidade,telefone"
    ]);
     try{
            $academia->phone = $request->telefone;
            $academia->capacidade= $request->capacidade;
            foreach($request->days as $day){
                    foreach($academia->open_days as $workingDay){
                            if($day == $workingDay->day){
                                
                                         $workingDay->start_hour = $request->start_hour;
                                         $workingDay->end_hour = $request->end_hour;
                                         $workingDay->save();
                            }else{
                                       $workingDay->delete();
                            }
                    }
            }
            $academia->save();

         
        return response()->json(['success' => 'OK'
     ], 200);
     }catch(\Exception $e) {
             return response()->json(['error' => $e->getMessage()], 400);
          }
     
            
 }

 

public function getAcademia(Academia $academia){
 try{
     

       
     return response->json(["academia"=>$academia],200);
      }catch(\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 400);
      } 
}

public function delete(Academia $academia){
 try{
     
           $academia->delete();
       
     return response->json(["sucess"=>"OK"],200);
      }catch(\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 400);
      } 
}
public function permit(Academia $academia,User $user){

   try{

      $academia->user_id=$user->id;
        $academia->save();
        return response->json(["sucess"=>"OK"],200);
   }catch(\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
   } 

}

public function miss(User $user){
   try{

      $falta = new Falta();
      $falta->user_id = $user->id;

      $falta->save();

    
    return response->json(["sucess"=>"OK"],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
} 
}

public function getAcademiaEvents(Academia $academia){
   try{

        $events = $academia->eventos;
        return response->json(["events"=>$events],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
} 
 }
 public function getAcademiaEvent(Academia $academia,Event $event){
   try{

        $event = $academia->events->where("id",$event->id)->first();
        return response->json(["events"=>$event],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
} 
 }

}
