<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutations;
use App\Models\Asset;
use App\Models\MutationsDet;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
// use Illuminate\Support\Facades\Storage;

class MutationsController extends Controller
{
    public function insertMutation(Request $request) {
        if($request->user_id){
            try {
                $id_mutation = Mutations::insertGetId(array(
                    'user_id' => $request->user_id,
                    'status' => $request->status
                ));
            } catch (QueryException $exception) {
                return response()->json([
                    "status" => false,
                    'message' => 'Insert failed',
                    "error_info" => $exception->errorInfo[2]
                ]);
            }

            $data = array();
            $arr_id_asset = array();

            foreach($request->data as $det_mutation){
                $data[] = ['mutasi_id'=>$id_mutation, 'asset_id'=> $det_mutation['asset_id'], 'description'=>$det_mutation['desc']];
            }
            $mutation = MutationsDet::insert($data); // Eloquent approach
            if($mutation == true){
                foreach($request->data as $det_mutation){
                    $arr_id_asset[] = ['asset_status' => $det_mutation['asset_id']];
                }
                $asset = Asset::whereIn('id', $arr_id_asset)->update(['asset_status'=>$request->status]);
            }
            return response()->json([
                "status" => $mutation,
                "message" => "Mutations created successfully.",
            ]);
        }else{
            return response()->json([
                "status" => false,
                'message' => 'Parameter is null'
            ]);
        }

    }
}