<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Academia;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{


    public function get($id) {
      
        $services = Service::where("academia_id", $id)->get();
    
        // Retorna os serviços como resposta JSON
        return response()->json([
            'services' => ServiceResource::collection($services)
        ]);
    }


    public function create($id, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|min:0',
        ]);
        $service = new Service();
        $service->name = $request->name;
        $service->academia_id = Academia::findOrFail($id)->id;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->save();
    }

    public function delete($id) {
        $service = Service::findOrFail($id);
        $service->delete();
    }

    public function update($id, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        $service = Service::findOrFail($id);
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->save();
    }
}
