<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\MutationsDet;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function assetall(Request $request) {
        $search = $request->input('search');
        $offset = $request->input('offset') ?? 1;
        $limit = $request->input('limit') ?? 10000;
        if ($search){
            // $query = Asset::where('departement_id', $departement_id)->get();
            $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                // ->where('departement_id', $departement_id)
                ->where('asset_number', 'like', '%' . $search . '%')
                ->orWhere('asset_desc', 'like', '%' . $search . '%')
                ->offset($offset)->limit($limit)
                // ->forPage($offset, $limit)
                ->get(['assets.*','departments.department','categories.category','counts.count']);

            $query_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                // ->where('departement_id', $departement_id)
                ->where('asset_number', 'like', '%' . $search . '%')
                ->orWhere('asset_desc', 'like', '%' . $search . '%')
            ->get(['assets.*','departments.department','categories.category','counts.count']);
            
            $count = count($query_count);
            if($count == 0){
                $status = false;
                $message = "No Data Avaiable";
            }else{
                $status = true;
                $message = "here is data";
            }
        }else{
            $query = Asset::join('departments', 'departments.id', '=', 'assets.departement_id')
                ->join('categories', 'categories.id', '=', 'assets.category_id')
                ->join('counts', 'counts.id', '=', 'assets.count_id')
                ->offset($offset)->limit($limit)
                // ->forPage($offset, $limit)
                ->get(['assets.*','departments.department','categories.category','counts.count']);

            $query_count = Asset::join('departments', 'departments.id', '=', 'assets.departement_id')
                ->join('categories', 'categories.id', '=', 'assets.category_id')
                ->join('counts', 'counts.id', '=', 'assets.count_id')
                ->offset($offset)->limit($limit)
                // ->forPage($offset, $limit)
                ->get(['assets.*','departments.department','categories.category','counts.count']);
            $count = count($query_count);
            if($count == 0){
                $status = false;
                $message = "No Data Avaiable";
            }else{
                $status = true;
                $message = "here is data";
            }
        }
        return response()->json([
            "status" => $status,
            "message" => $message,
            "total_data" => $count,
            "data" => $query
        ]);
    }

    public function assetbydepartement(Request $request) {
        $departement_id = $request->input('departement_id');
        $search = $request->input('search');
        $offset = $request->input('offset') ?? 1;
        $limit = $request->input('limit') ?? 10000;
        $mode = $request->input('mode');

        if ($departement_id){
            $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                ->where('departement_id', $departement_id)
                ->when($search != '', function($query_or) use ($search) {
                    $query_or->where('asset_number', 'like', '%' . $search . '%')
                            ->orWhere('asset_desc', 'like', '%' . $search . '%');
                })

                ->when($mode == 'all_upload', function ($query_) {
                    return $query_->whereNotIn('assets.id',MutationsDet::select('asset_id'));
                })

                ->when($mode == 'hide', function ($query_) {
                    return $query_->whereNotIn('assets.id',MutationsDet::select('asset_id'));
                })

                ->when($mode == 'hide', function ($query_) {
                    return $query_->whereNotIn('assets.id',Upload::select('asset_id'));
                })

                ->when($mode == 'mutation', function ($query) {
                    $query->where(function($query) {
                        $query->whereIn('assets.id',MutationsDet::select('asset_id'));
                    });
                })
                ->when($mode == 'upload', function ($query) {
                    $query->where(function($query) {
                        $query->whereIn('assets.id',Upload::select('asset_id'));
                    });
                })
                ->when($mode == 'upload', function ($query) {
                    $query->where(function($query) {
                        $query->whereNotIn('assets.id',MutationsDet::select('asset_id'));
                    });
                })

                ->offset($offset)->limit($limit)
                // ->forPage($offset, $limit)
                ->get(['assets.*','departments.department','categories.category','counts.count']);

            $query_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                ->when($search != '', function($query_or) use ($search) {
                    $query_or->where('asset_number', 'like', '%' . $search . '%')
                            ->orWhere('asset_desc', 'like', '%' . $search . '%');
                })

                ->when($mode == 'hide', function ($query_) {
                    return $query_->whereNotIn('assets.id',MutationsDet::select('asset_id'));
                })

                ->when($mode == 'hide', function ($query_) {
                    return $query_->whereNotIn('assets.id',Upload::select('asset_id'));
                })

                ->when($mode == 'mutation', function ($query) {
                    $query->where(function($query) {
                        $query->whereIn('assets.id',MutationsDet::select('asset_id'));
                    });
                })
                ->when($mode == 'upload', function ($query) {
                    $query->where(function($query) {
                        $query->whereIn('assets.id',Upload::select('asset_id'));
                    });
                })
                ->when($mode == 'upload', function ($query) {
                    $query->where(function($query) {
                        $query->whereNotIn('assets.id',MutationsDet::select('asset_id'));
                    });
                })

                ->where('departement_id', $departement_id)
                // ->forPage($offset, $limit)
                ->get(['assets.*','departments.department','categories.category','counts.count']);
            $count = count($query_count);
            if($count == 0){
                $status = false;
                $message = "No Data Avaiable";
            }else{
                $status = true;
                $message = "here is data";
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Parameter is null'
            ]);
        }
        
        return response()->json([
            "status" => $status,
            "message" => $message,
            "total_data" => $count,
            "data" => $query
        ]);
    }

    public function asset_id(Request $request) {
        $id = $request->input('id');
        $data = '';
        $data_check = '';
        $query = '';

        if ($id){
            $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                ->where('assets.id', $id)
                ->get(['assets.*','departments.department','categories.category','counts.count']);
            foreach($query as $data_query){
                $data = $data_query;
            }
            if($data == ''){
                return response()->json([
                    "status" => false,
                    "message" => "No Data Avaiable"
                ]);
            }
            $query_upload = Upload::where('asset_id', $id)->get();
            foreach($query_upload as $data_query){
                $data_query->upload_image = 'https://kitadev.xyz/storage/'.$data_query->upload_image;
                $data_check = $data_query;
            }
            if($data_check == ''){

            }else{
                $data['image_upload'] = $query_upload;
            }
            $status = true;
            $message = "here is data";
        }else{
            $status = false;
            $message = "No Data Avaiable";
        }
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data
        ]);
    }

    public function store(Request $request) {
        $input = $request->all();
        $input['created_at'] = date('Y-m-d H:i:s');
        $input['asset_status'] = (($input['asset_status'] == null) || ($input['asset_status'] == '') ? "-":$input['asset_status']);
        $validator = Validator::make($input, [
            "asset_number" => "required",
            "asset_serial_number"=> "required",
            "asset_capitalized_on"=> "required",
            "asset_manager"=> "required",
            "asset_desc"=> "required",
            "asset_quantity"=> "required",
            "asset_po"=> "required",
            "departement_id"=> "required",
            "count_id"=> "required",
            "category_id"=> "required",
            "location"=> "required",
            "asset_condition"=> "required",
        ]);

        if($validator->fails()){
            return $validator->errors();       
        }

        $asset = Asset::create($input);
        if($asset){
            return response()->json([
                "status" => true,
                "message" => "Asset created successfully.",
                "data" => $asset
            ]);
        }

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

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $input['updated_at'] = date('Y-m-d H:i:s');

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
            "category_id"=> "required",
            "count_id"=> "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'status' => false
            ]);
        }

        $asset = Asset::find($id);   
        $asset->asset_number = $input['asset_number'];
        $asset->asset_serial_number = $input['asset_serial_number'];
        $asset->asset_capitalized_on = $input['asset_capitalized_on'];
        $asset->asset_manager = $input['asset_manager'];
        $asset->asset_desc = $input['asset_desc'];
        $asset->asset_quantity = $input['asset_quantity'];
        $asset->asset_po = $input['asset_po'];
        $asset->asset_status = $input['asset_status'];
        $asset->departement_id = $input['departement_id'];
        $asset->category_id = $input['category_id'];
        $asset->count_id = $input['count_id'];
        $asset->save();

        return response()->json([
            'status' => true,
            'message' => 'updated successfully'
        ]);
    }

    public function destroy(Request $request){
        $input = $request->all();

        $deleted = Asset::where('id', $input['id'])->delete();
        
        return response()->json([
        "status" => true,
        "message" => "Asset deleted successfully.",
        "data" => $deleted
        ]);
    }

    public function get_upload_image($id_asset = null){
        if ($id_asset){
            $query = Upload::where('id_asset', $id_asset)->get();
            $count = count($query);
            if($count == 0){
                $status = false;
                $message = "No Data Avaiable";
            }else{
                $status = true;
                $message = "here is data";
            }
        }else{
            $query = Upload::all();
            $count = count($query);
            if($count == 0){
                $status = false;
                $message = "No Data Avaiable";
            }else{
                $status = true;
                $message = "here is data";
            }
        }

        return response()->json([
            "status" => $status,
            "message" => $message,
            "total_data" => $count,
            "data" => $query
        ]);
    }

    public function upload_image(Request $request){

        //BUAT VALIDASI JIKA INPUT DATA GAGAL ATAU UPLOAD FILE GAGAL

        $input = $request->all();
        $input['created_at'] = date('Y-m-d H:i:s');
        $id = $input['asset_id'];

        $validator = Validator::make($request->all(), [
            "asset_id"=> "required",
            "user_id"=> "required",
            "upload_status"=> "required",
            "upload_image"=> "required|image|mimes:jpg,png,jpeg,gif,svg|max:5048",
            "location"=> "required",
            "asset_condition"=> "required",
         ]);
         
         if($validator->fails()){
            return $validator->errors();       
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

        $asset = Asset::find($id);   
        $asset->location = $input['location'];
        $asset->asset_condition = $input['asset_condition'];
        $asset->save();

        if($asset){
            unset($input['location']);
            unset($input['asset_condition']);
            $data = Upload::create($input);

            // $asset = Asset::create($input);
            if($data){
                return response()->json([
                    "status" => true,
                    "message" => "Asset created successfully.",
                    "data_image" => $data_image,
                    "data" => $data
                ]);
            }
        }
    }

    public function upload_(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            "asset_id"=> "required",
            "user_id"=> "required",
            "upload_status"=> "required",
            "upload_image"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
            "location"=> "required",
            "asset_condition"=> "required",
        ]);
        if($validator->fails()){
            return $validator->errors();       
        }

        $data = Upload::select('upload_image')->where('id', $input['id'])->get();
        foreach($data as $datas){
            dd($data);

        }

        // file_exists(public_path('path/to/asset.png'));

        // $uploadFolder = 'asset';
        // $image = $request->file('upload_image');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        // $data_image = array(
        //     "image_name" => basename($image_uploaded_path),
        //     "image_url" => Storage::disk('public')->url($image_uploaded_path),
        //     "mime" => $image->getClientMimeType()
        // );

        // $input["upload_image"] = $image_uploaded_path;

        // $data = Upload::create($input);

        // // $asset = Asset::create($input);
        // return response()->json([
        //     "status" => true,
        //     "message" => "Asset created successfully.",
        //     "data_image" => $data_image,
        //     "data" => $data
        // ]);

        // $asset = Asset::find($id);   
        // $asset->asset_number = $input['asset_number'];
        // $asset->asset_serial_number = $input['asset_serial_number'];
        // $asset->asset_capitalized_on = $input['asset_capitalized_on'];
        // $asset->asset_manager = $input['asset_manager'];
        // $asset->asset_desc = $input['asset_desc'];
        // $asset->asset_quantity = $input['asset_quantity'];
        // $asset->asset_po = $input['asset_po'];
        // $asset->asset_status = $input['asset_status'];
        // $asset->departement_id = $input['departement_id'];
        // $asset->category_id = $input['category_id'];
        // $asset->count_id = $input['count_id'];
        // $asset->save();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'updated successfully'
        // ]);
    }

}