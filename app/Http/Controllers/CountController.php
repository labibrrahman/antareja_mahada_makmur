<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\MutationsDet;
use Symfony\Component\HttpFoundation\Response;

class CountController extends Controller
{
    public function asset(Request $request) {
        $departement_id = $request->input('departement_id');
        $mode = $request->input('mode');
        $limit = 5;
        $query = '';
        $count = '';

        if ($departement_id){
            if($mode == 'asset'){
                $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('assets.created_at', 'DESC')
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $query_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('assets.created_at', 'DESC')
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);
                $count = count($query_count);
                if($count == 0){
                    $status = false;
                    $message = "No Data Avaiable";
                }else{
                    $status = true;
                    $message = "here is data";
                }
            }else if($mode == 'upload'){
                $query = Asset::join('uploads', 'uploads.asset_id', '=', 'assets.id')
                    ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('uploads.created_at', 'DESC')
                    ->groupBy(
                        [   
                            'assets.id',
                            'assets.asset_number',
                            'assets.asset_serial_number',
                            'assets.asset_capitalized_on',
                            'assets.asset_manager',
                            'assets.asset_desc',
                            'assets.asset_quantity',
                            'assets.asset_po',
                            'assets.asset_status',
                            'assets.departement_id',
                            'assets.category_id',
                            'assets.count_id',
                            'assets.created_at',
                            'assets.created_by',
                            'assets.updated_at',
                            'assets.updated_by','departments.department','categories.category','counts.count'
                        ]
                    )
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $query_count = Asset::join('uploads', 'uploads.asset_id', '=', 'assets.id')
                    ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('uploads.created_at', 'DESC')
                    ->groupBy(
                        [   
                            'assets.id',
                            'assets.asset_number',
                            'assets.asset_serial_number',
                            'assets.asset_capitalized_on',
                            'assets.asset_manager',
                            'assets.asset_desc',
                            'assets.asset_quantity',
                            'assets.asset_po',
                            'assets.asset_status',
                            'assets.departement_id',
                            'assets.category_id',
                            'assets.count_id',
                            'assets.created_at',
                            'assets.created_by',
                            'assets.updated_at',
                            'assets.updated_by','departments.department','categories.category','counts.count'
                        ]
                    )
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $count = count($query_count);
                if($count == 0){
                    $status = false;
                    $message = "No Data Avaiable";
                }else{
                    $status = true;
                    $message = "here is data";
                }
            }else if($mode == 'mutation'){
                $query_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)

                    ->when($mode == 'mutation', function ($query_) {
                        return $query_->whereIn('assets.id',MutationsDet::select('asset_id'));
                    })
                    // ->when($mode == 'hide', function ($query_) {
                    //     return $query_->whereIn('assets.id',Upload::select('asset_id'));
                    // })

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
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Departement is null'
            ]);
        }
        return response()->json([
            "status" => $status,
            "message" => $message,
            "total_data" => $count,
            "data" => $query
        ]);
    }

    public function latestData(Request $request) {
        $departement_id = $request->input('departement_id');
        $mode = $request->input('mode');
        $limit = 5;
        if($mode == 'asset'){
            if ($departement_id){
                $query = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('assets.created_at', 'DESC')
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $query_count = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('assets.created_at', 'DESC')
                    ->limit($limit)
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
        }else if($mode == 'upload'){
            if ($departement_id){
                $query = Asset::join('uploads', 'uploads.asset_id', '=', 'assets.id')
                    ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('uploads.created_at', 'DESC')
                    ->groupBy(
                        [   
                            'assets.id',
                            'assets.asset_number',
                            'assets.asset_serial_number',
                            'assets.asset_capitalized_on',
                            'assets.asset_manager',
                            'assets.asset_desc',
                            'assets.asset_quantity',
                            'assets.asset_po',
                            'assets.asset_status',
                            'assets.departement_id',
                            'assets.category_id',
                            'assets.count_id',
                            'assets.created_at',
                            'assets.created_by',
                            'assets.updated_at',
                            'assets.updated_by','departments.department','categories.category','counts.count'
                        ]
                    )
                    ->limit($limit)
                    ->get(['assets.*','departments.department','categories.category','counts.count']);

                $query_count = Asset::join('uploads', 'uploads.asset_id', '=', 'assets.id')
                    ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                    ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                    ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                    ->where('departement_id', $departement_id)
                    ->orderBy('uploads.created_at', 'DESC')
                    ->groupBy(
                        [   
                            'assets.id',
                            'assets.asset_number',
                            'assets.asset_serial_number',
                            'assets.asset_capitalized_on',
                            'assets.asset_manager',
                            'assets.asset_desc',
                            'assets.asset_quantity',
                            'assets.asset_po',
                            'assets.asset_status',
                            'assets.departement_id',
                            'assets.category_id',
                            'assets.count_id',
                            'assets.created_at',
                            'assets.created_by',
                            'assets.updated_at',
                            'assets.updated_by','departments.department','categories.category','counts.count'
                        ]
                    )
                    ->limit($limit)
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
        }
        
        return response()->json([
            "status" => $status,
            "message" => $message,
            "total_data" => $count,
            "data" => $query
        ]);
    }
}


