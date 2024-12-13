<?php

namespace App\Http\Controllers;

use App\Enums\DaysOfWeek;
use App\Http\Resources\AcademiaResource;
use Illuminate\Http\Request;
use App\Models\Academia;
use App\Models\Evento;
use App\Models\OpenDay;
use App\Models\Falta;
use App\Models\OpenDayTimes;
use App\Models\SpecificDate;
use App\Models\User;
use Carbon\Carbon;

class AcademiaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "capacidade" => "required",
            "telefone" => "required",
            "days" => "required|array|min:1",
            "start_hour" => "required",
            "end_hour" => "required"
        ]);
    
        try {
            // Cria uma nova academia
            $academia = new Academia();
            $academia->phone = $request->telefone;
            $academia->capacidade = $request->capacidade;
            $academia->save();
    
            // Salva os dias e horários relacionados
            foreach ($request->days as $day) {
                // Cria um novo dia relacionado à academia
                $open_day = new OpenDay();
                $open_day->academia_id = $academia->id;
                $open_day->day = $day;
                $open_day->save();
    
                // Adiciona os horários para este dia
                $open_day_time = new OpenDayTimes();
                $open_day_time->open_day_id = $open_day->id;
                $open_day_time->opening_time = $request->start_hour;
                $open_day_time->closing_time = $request->end_hour;
                $open_day_time->save();
            }
    
            return response()->json(['success' => 'Academia criada com sucesso!'], 201);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    


    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'capacidade' => 'required|integer|min:1',
        'max_faltas' => 'required|integer|min:0',
        'schedule' => 'required|array|min:1',
        'schedule.*.day' => 'required|string',
        'schedule.*.shifts' => 'required|array|min:1',
        'schedule.*.shifts.*.openingTime' => 'required  ',
        'schedule.*.shifts.*.closingTime' => 'required|after:schedule.*.shifts.*.openingTime',
    ]);

    try {
        $academia = Academia::findOrFail(1);

        // Atualiza os dados da academia
        $academia->update($request->only(['name', 'phone', 'capacidade', 'max_faltas']));

        // Remove os dias e horários existentes
        $academia->schedules()->each(function ($openDay) {
            $openDay->openDayTimes()->delete(); // Remove os horários associados
            $openDay->delete(); // Remove o dia
        });

        // Adiciona os novos dias e horários
        foreach ($request->schedule as $schedule) {
            $dia = null;

            switch ($schedule['day']) {
              case 'Segunda':
                  $dia = DaysOfWeek::Segunda;
                  break;
              case 'Terça':
                  $dia = DaysOfWeek::Terça;
                  break;
              case 'Quarta':
                  $dia = DaysOfWeek::Quarta;
                  break;
              case 'Quinta':
                  $dia = DaysOfWeek::Quinta;
                  break;
              case 'Sexta':
                  $dia = DaysOfWeek::Sexta;
                  break;
              case 'Sábado':
                  $dia = DaysOfWeek::Sábado;
                  break;
              case 'Domingo':
                  $dia = DaysOfWeek::Domingo;
                  break;
          }
  
  
            // Cria um novo dia
            $openDay = $academia->schedules()->create([
                'day' => $dia->value,
            ]);

            // Cria múltiplos turnos para o dia
            foreach ($schedule['shifts'] as $shift) {
                $openDay->openDayTimes()->create([
                    'opening_time' => $shift['openingTime'],
                    'closing_time' => $shift['closingTime'],
                ]); 
            }
        }

        return response()->json(["success" => "OK"], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}



public function getAcademia(){
 try{

   $academia = Academia::findOrFail(1);

     return response()->json(["academia"=> new AcademiaResource($academia)],200);
      }catch(\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 400);
      }
}

public function delete(Academia $academia){
 try{

           $academia->delete();

     return response()->json(["sucess"=>"OK"],200);
      }catch(\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 400);
      }
}
public function permit(Academia $academia,User $user){

   try{

      $academia->usarios()->attach($user->id);

        return response()->json(["sucess"=>"OK"],200);
   }catch(\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 400);
   }

}

public function miss($id, $id1){
   try{
      $academia = Academia::findOrFail(1);
      $user  = User::findOrFail($id);
      $event = Evento::findOrFail($id1);
      $falta = new Falta;
      $falta->user_id = $user->id;
      $falta->event_id = $event->id;

      $falta->save();

      if($user->faltas->count() > $academia->max_faltas) {
        $user->update([
            'is_blocked' => 1
         ]);
      }


    return response()->json(["sucess"=>"OK"],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
}
}

public function presence($userId, $eventId) {
   try {

       $user = User::findOrFail($userId);
       $event = Evento::findOrFail($eventId);


       $falta = Falta::where('user_id', $user->id)
                     ->where('event_id', $event->id)
                     ->first();

       // Se a falta existir, excluí-la
       if ($falta) {
           $falta->delete();
       } else {
           return response()->json(['error' => 'Falta não encontrada para o usuário e evento especificados.'], 404);
       }

       return response()->json(["success" => "Presença marcada com sucesso."], 200);
   } catch (\Exception $e) {
       return response()->json(['error' => $e->getMessage()], 400);
   }
}

public function getAcademiaEvents(Academia $academia){
   try{

        $events = $academia->eventos;
        return response()->json(["events"=>$events],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
}
 }
 public function getAcademiaEvent(Academia $academia,Evento $event){
   try{

        $event = $academia->events->where("id",$event->id)->first();
        return response()->json(["events"=>$event],200);
}catch(\Exception $e) {
  return response()->json(['error' => $e->getMessage()], 400);
}
 }

 public function removerData(Request $request) {
    try {
        $data = Carbon::parse($request->date)->format('Y-m-d');
        if(SpecificDate::where("data", $data)->where("type", "adicionar")->exists()) {
            $specific = SpecificDate::where("data", $data)->where("type", "adicionar")->first();
            $specific->delete();
        }    else {


    $academia = Academia::findOrFail(1);
    $academia->specificDates()->create([
       "data" => $data,
       "type" => "remover"
    ]);
}

} catch(\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 400);
  }
 }

 public function adicionarData(Request $request) {
    try {

  $data = Carbon::parse($request->date)->format('Y-m-d');

  if(SpecificDate::where("data", $data)->where("type", "remover")->exists()) {
     $specific = SpecificDate::where("data", $data)->where("type", "remover")->first();
     $specific->delete();
  } else {


    $academia = Academia::findOrFail(1);
    $academia->specificDates()->create([
       "data" => $data,
       "start_hour" => Carbon::parse($request->start_hour)->format('H:i:s'),
       "end_hour" => Carbon::parse($request->end_hour)->format('H:i:s'),
       "type" => "adicionar"
    ]);
}

} catch(\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 400);
  }
 }

}
