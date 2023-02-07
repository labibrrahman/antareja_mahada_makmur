<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function assetall($departement_id = null) {
        if ($departement_id){
            // $query = Asset::where('departement_id', $departement_id)->get();
            $query = Asset::join('departments', 'departments.id', '=', 'assets.departement_id')
            ->join('categories', 'categories.id', '=', 'assets.category_id')
            ->join('counts', 'counts.id', '=', 'assets.count_id')
            ->where('departement_id', $departement_id)
            ->get();

            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }else{
            $query = Asset::join('departments', 'departments.id', '=', 'assets.departement_id')
            ->join('categories', 'categories.id', '=', 'assets.category_id')
            ->join('counts', 'counts.id', '=', 'assets.count_id')
            ->get();
            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }

        
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

    public function destroy(Request $request){
        $input = $request->all();

        $deleted = Asset::where('id', $input['id'])->delete();
        
        return response()->json([
        "success" => true,
        "message" => "Asset deleted successfully.",
        "data" => $deleted
        ]);
    }


    public function get_upload_image($id_asset = null){
        if ($id_asset){
            $query = Upload::where('id_asset', $id_asset)->get();
            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }else{
            $query = Upload::all();
            $count = count($query);
            if($count == 0){
                $success = false;
                $message = "No Data Avaiable";
            }else{
                $success = true;
                $message = "here is data";
            }
        }

        return response()->json([
            "success" => $success,
            "message" => $message,
            "Total Data" => $count,
            "data" => $query
        ]);
    }

    public function upload_image(Request $request){

        //BUAT VALIDASI JIKA INPUT DATA GAGAL ATAU UPLOAD FILE GAGAL

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            "asset_id"=> "required",
            "user_id"=> "required",
            "upload_status"=> "required",
            "upload_image"=> "required|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
         ]);
         if ($validator->fails()) {
            return sendCustomResponse($validator->messages()->first(),  'error', 500);
         }

        $uploadFolder = 'asset';
        $image = $request->file('upload_image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $data_image = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );

        $input["upload_image"] = $image_uploaded_path;

        $data = Upload::create($input);

        // $asset = Asset::create($input);
        return response()->json([
            "success" => true,
            "message" => "Asset created successfully.",
            "data_image" => $data_image,
            "data" => $data
        ]);
    }

    // public function destroy(Request $request){
    //     $input = $request->all();

    //     // $deleted = Asset::where('id', 0)->delete();
    // }
}