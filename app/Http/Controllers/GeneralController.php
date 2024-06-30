<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use App\Models\OpenDay;
use App\Models\SpecificDate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Evento;

class GeneralController extends Controller
{
    public function getDisabledDaysOfWeek() {

        try {


        $academia = Academia::findOrFail(1);
        $daysOfWeek = [1, 2, 3, 4, 5, 6, 7];

        $disponibleDays = $academia->schedules()->pluck("day");


        $disponibleDaysValues = [];


        foreach ($disponibleDays as $day) {
            $disponibleDaysValues[] = $day->value;
        }

        $disabledDays = array_diff($daysOfWeek, $disponibleDaysValues);


        $disabledDays = array_values($disabledDays);



        return response()->json(["dias" => $disabledDays]);
    }  catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
    }

    public function getDisponibleHoursOfTheDate(Request $request) {
        $formattedDate = Carbon::parse($request->date);

        // Obter o dia da semana em formato ISO (1 = segunda-feira, 7 = domingo)
        $dayOfWeek = $formattedDate->dayOfWeekIso;

        // Ajuste se for domingo
        switch($dayOfWeek) {
            case 1:
            $dayOfWeek = 7;
            break;
            case 7:
                $dayOfWeek = 1;
                break;
        }

        $horarios = [];
        $academia = Academia::findOrFail(1);

        $disponibleDay = OpenDay::where('day', $dayOfWeek)->first();

        if ($disponibleDay) {
            $openingTime = Carbon::parse($disponibleDay->opening_time);
            $closingTime = Carbon::parse($disponibleDay->closing_time);

            $currentDateTime = Carbon::now();
            $isToday = $formattedDate->isToday();
            $currentTime = $openingTime->copy();

            $capacity = $academia->capacidade; // Defina a capacidade máxima da academia aqui

            while ($currentTime->lessThan($closingTime)) {
                if ($isToday && $currentTime->lessThan($currentDateTime)) {
                    $currentTime->addHour();
                    continue;
                }


                $event = Evento::where('date', $formattedDate->format('Y-m-d'))
                                   ->where('time', $currentTime->format('H:i:s'))
                                   ->first();
                    if($event) {
                        $eventCount = $event->users()->count();
                        if ($eventCount < $capacity) {
                            $horarios[] = $currentTime->format('H:i');
                        }
                    } else {
                        $horarios[] = $currentTime->format('H:i');
                    }



                $currentTime->addHour();
            }

            return response()->json(["horarios" => $horarios], 200);
        } else {
            return response()->json(['error' => $dayOfWeek], 400);
        }
    }

public function getRemovedDates() {
    try {
        $removedDates = SpecificDate::where("type", "remover")->pluck("data")->toArray();





        return response()->json(["removedDates" => $removedDates], 200);
    } catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

    public function getAddedDates() {
        try {


     $addedDates = SpecificDate::where("type", "adicionar")->pluck("data")->toArray();


        return response()->json(["addedDates"=> $addedDates],200);
    }catch(\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
     }
    }

    public function getVagas(Request $request) {

           try {
            $academia = Academia::findOrFail(1);
            $request->validate([
                "date" => "required",
                "time" => "required"
               ]);
           $evento = Evento::where("date", Carbon::parse($request->date)->format("Y-m-d"))
           ->where("time", Carbon::parse($request->time)->format("H:i:s"))
           ->first();
           if($evento) {
            $vagas = $evento->academia->capacidade - $evento->users()->count();
           } else {
            $vagas = $academia->capacidade;
           }

           return response()->json(["vagas" => $vagas]);
           }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
         }
    }
}
