<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use App\Models\Facility;
use App\Models\Unit;

class FacilityController extends Controller
{
    public function listFacilities(): View
    {
        return view('app.facility.list-facility', ['facilities' => Facility::all(), 'units' => Unit::all()] );
    }

    public function new(){
        return view('app.facility.new', ['facilities'=> Facility::all()]);
    }


    public function new_facility(Request $request){
        if(empty($request->name)){
            return response()->json(["type"=>"warning", "message" => "Tesis adı girin"]);
        }

        $tesis = new Facility;
        $tesis->facility = trim(ucfirst($request->name));
        $tesis->save();

        return response()->json(["type"=>"success", "message" => "Tesis kaydedildi", "status" => true, "reload" => true]);
    }

    public function new_unit(Request $request){
        if($request->tesis == 0){
            return response()->json(["type"=>"warning", "message" => "Tesis seçin"]);
        }
        if(empty($request->unit_name)){
            return response()->json(["type"=>"warning", "message" => "Birim adı girin"]);
        }

        $birim = new Unit;
        $birim->facility_id = $request->tesis;
        $birim->unit = trim(ucfirst($request->unit_name));
        $birim->save();
        return response()->json(["type"=>"success", "message" => "Birim kaydedildi", "status" => true, "reload" => true]);
        
    }

    public function delete_facility(Request $request){
        $tesis = Facility::find($request->id);
        $tesis->delete();
        return response()->json(["type" => "success", "message" => "Tesis silindi", "status" => true, "reload"=>true]);
    }
    public function delete_unit(Request $request){
        $unit = Unit::find($request->id);
        $unit->delete();
        return response()->json(["type" => "success", "message" => "Birim silindi", "status" => true, "reload"=>true]);
    }
}

