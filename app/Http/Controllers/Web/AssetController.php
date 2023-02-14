<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use DB;
class AssetController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.asset', ['title' => 'Asset']);
    }

    public function getData(Request $request){

        // Total records
   
        // Fetch records
        // $records = Employees::orderBy($columnName,$columnSortOrder)
        //   ->where('employees.name', 'like', '%' .$searchValue . '%')
        //   ->select('employees.*')
        //   ->skip($start)
        //   ->take($rowperpage)
        //   ->get();
   
        $records = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                ->get(['assets.asset_number as asset_number','departments.department as department','categories as category','counts.count as count']);

        $totalRecords = count($records);
        dd($record);
        $data_arr = array();
        
        foreach($records as $record){
           $id = $record->id;
           $username = $record->username;
           $name = $record->name;
           $email = $record->email;
   
           $data_arr[] = array(
             "id" => $id,
             "username" => $username,
             "name" => $name,
             "email" => $email
           );
        }
   
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecords,
           "aaData" => $data_arr
        );
   
        echo json_encode($response);
        exit;
      }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}