<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Auth;
use Validator;

class AssetController extends Controller
{
    public function assetall($departement_id = null) {
        if ($departement_id){
            $query = Asset::where('departement_id', $departement_id)->get();
            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }else{
            $query = Asset::all();
            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }
        // $data['success'] = $success;
        // $data['message'] = $message;
        // $data['Total Data'] = $count;
        // $data['pageData'] = $query;

        // return response()->json($data, 200);
        return response()->json([
            "success" => $success,
            "message" => $message,
            "Total Data" => $count,
            "data" => $query
        ]);
    }

    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            "asset_number" => "required",
            "asset_serial_number"=> "required",
            "asset_capitalized_on"=> "required",
            "asset_manager"=> "required",
            "asset_desc"=> "required",
            "asset_quantity"=> "required",
            "asset_po"=> "required",
            "asset_status"=> "required",
            "departement_id"=> "required",
            "count_id"=> "required"
        ]);

        if($validator->fails()){
            return $validator->errors();       
        }

        $asset = Asset::create($input);
        return response()->json([
            "success" => true,
            "message" => "Asset created successfully.",
            "data" => $asset
        ]);

        // $messages = [
        //     'asset_number' => 'hehehehe',
        //     'asset_serial_number' => 'hahaha',
        //     'asset_capitalized_on' => 'wkwkkw',
        // ];

        // $validated = $request->validate([
        //     "asset_number" => 'required',
        //     "asset_serial_number"=> "required",
        //     "asset_capitalized_on"=> "required",
        //     "asset_manager"=> "required",
        //     "asset_desc"=> "required",
        //     "asset_quantity"=> "required",
        //     "asset_po"=> "required",
        //     "asset_status"=> "required",
        //     "departement_id"=> "required",
        //     "count_id"=> "required"
        // ],$messages);
        
        // $flight = Asset::create([
        //     "asset_number" => '',
        //     "asset_serial_number"=> "",
        //     "asset_capitalized_on"=> "",
        //     "asset_manager"=> "",
        //     "asset_desc"=> "",
        //     "asset_quantity"=> 1,
        //     "asset_po"=> "7000078429",
        //     "asset_status"=> "",
        //     "departement_id"=> 1,
        //     "count_id"=> 1,
        // ]);

        // $data = "Welcome " . Auth::user()->name;
        // return response()->json($data, 200);
    }

    public function update(Request $request){
        Post::where('id',3)->update(['title'=>'Updated title']);

        $validator = Validator::make($input, [
            "asset_number" => "required",
            "asset_serial_number"=> "required",
            "asset_capitalized_on"=> "required",
            "asset_manager"=> "required",
            "asset_desc"=> "required",
            "asset_quantity"=> "required",
            "asset_po"=> "required",
            "asset_status"=> "required",
            "departement_id"=> "required",
            "count_id"=> "required"
        ]);
    }

    // public function assetall() {
    //     $data = "Data All Asset";
    //     return response()->json($data, 200);
    // }
}