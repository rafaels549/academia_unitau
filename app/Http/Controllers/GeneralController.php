<?php

namespace App\Http\Controllers;

use App\Models\Academia;
use App\Models\OpenDay;
use App\Models\SpecificDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    
       
        $dayOfWeek = $formattedDate->dayOfWeekIso;
     
        switch($dayOfWeek) {
           
            case 7:
                $dayOfWeek = 1;
                break;
        }

        $horarios = [];

        $disponibleDay = OpenDay::where('day', $dayOfWeek)->first();

        if($disponibleDay) {
          
        $openingTime = Carbon::parse($disponibleDay->opening_time);
        $closingTime = Carbon::parse($disponibleDay->closing_time);
        
       
        $currentTime = $openingTime->copy();
        while ($currentTime->lessThan($closingTime)) {
            $horarios[] = $currentTime->format('H:i');
            $currentTime->addHour(); 
        }
        return response()->json(["horarios"=> $horarios],200);
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
}
