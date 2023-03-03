<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\Categories;
use App\Models\MutationsDet;
use Auth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class CategoryAssetController extends Controller
{
    // public function getCategoryByAsset(Request $request) {
    //     $departement_id = $request->input('departement_id');
    //     $categories_id = $request->input('categories_id');
    //     $offset = $request->input('offset') ?? 1;
    //     $limit = $request->input('limit') ?? 10000;

    //     if ($departement_id){
    //         $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
    //             ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
    //             ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
    //             ->where('departement_id', $departement_id)
    //             ->where('categories.id', $categories_id)
    //             ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
    //             // ->whereIn('assets.id',Upload::select('asset_id'))
    //             ->offset($offset)->limit($limit)
    //             // ->forPage($offset, $limit)
    //             ->get(['assets.*','departments.department','categories.category','counts.count']);
    //         if($query){
    //             $count = count($query);
    //             return response()->json([
    //                 "status" => true,
    //                 "message" => "here is data",
    //                 "total_data" => $count,
    //                 "data" => $query
    //             ]);
    //         }else{
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Query error'
    //             ]);
    //         }
    //     }else{
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Parameter is null'
    //         ]);
    //     }
    // }

    public function getCategory(Request $request) {
        $departement_id = $request->input('departement_id');
        $categories_id = $request->input('categories_id');
        $offset = $request->input('offset') ?? 1;
        $limit = $request->input('limit') ?? 10000;
        $mode = $request->input('mode');

        if ($departement_id){
            if($mode == 'asset'){
                $data = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->where('categories.id', $categories_id)
                    ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                    // ->whereIn('assets.id',Upload::select('asset_id'))
                    ->offset($offset)->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $data_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->where('categories.id', $categories_id)
                    ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                    // ->whereIn('assets.id',Upload::select('asset_id'))
                    ->get(['assets.*','departments.department','categories.category','counts.count']);
                $count = count($data_count);
            }else{
                $data = Asset::select('category_id','categories.category')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->where('departement_id',$departement_id)
                    ->groupBy('category_id','categories.category')->get();
                    $count = count($data);
            }
            if($data){
                return response()->json([
                    "status" => true,
                    "message" => "here is data",
                    "total_data" => $count,
                    "data" => $data
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Parameter is null'
            ]);
        }
    }
}