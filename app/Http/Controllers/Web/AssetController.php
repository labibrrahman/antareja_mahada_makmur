<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Counts;
use App\Models\Categories;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportAsset;

class AssetController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      if ($request->ajax()) {
        $data = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
        ->orderBy('assets.asset_capitalized_on', 'DESC')
        ->get(['assets.*','departments.department as department','category','counts.count as count']);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                      $btn =  '<a href="" data-toggle="modal" onclick=getasset_ajax('.$row['id'].') data-target="#exampleModal" class="edit btn btn-success btn-sm">Add Price</a>&nbsp;'.
                              '<a href="" data-toggle="modal" onclick=getupdate_ajax('.$row['id'].') data-target="#updateAsset" class="edit btn btn-warning btn-sm">Edit</a>&nbsp;'.
                              '<a href="" data-toggle="modal" onclick=confirmDelete('.$row['id'].') data-target="#deletedModal" class="edit btn btn-danger btn-sm">Deleted</a>';
                      return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
      }

      $count = json_decode(Counts::all());
      $categories = json_decode(Categories::all());
      $departement = json_decode(Departement::all());
      return view('pages.asset', ['title' => 'Asset'])
      ->with('count',$count)
      ->with('departement',$departement)
      ->with('categories',$categories);
    }

    public function getDataAsset(Request $request)
    {
          $post = $request->all();
          $query = Asset::select('*')->where('id' , $post['id'])->get();
          foreach($query as $query_data){
            $data = $query_data;
          }
          return json_decode($data);
    }

    public function storePrice(Request $request)
    {
      $input = $request->all();

      $validator = Validator::make($input, [
        'id_asset' => 'required',
        'asset_price' => 'required',
      ]);

      if($validator->fails()){
	        return back()->with('warning', 'Data is null');
      }

      $asset = Asset::find($input['id_asset']);   
      $asset->asset_price = $input['asset_price'];
      $asset->save();

      return back()->with('success', "Input Asset Price successfully");
    }

    public function store(Request $request) {
      $input = $request->all();
      // $input['asset_manager'] = Session::get('id');
      $input['asset_manager'] = "-";
      $input['asset_status'] = "-";
      $input['created_at'] = date('Y-m-d H:i:s');
      $input['created_by'] = Session::get('id');
      $validator = Validator::make($input, [
          "asset_number" => "required",
          "asset_serial_number"=> "required",
          "asset_capitalized_on"=> "required",
          // "asset_manager"=> "required",
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
        return back()->with('success', "Input Asset successfully");
      }
    }

    public function update(Request $request) {
      
      $input = $request->all();
      $id = $input['id_asset'];
      $input['asset_manager'] = "-";
      $input['asset_status'] = "-";
      $input['asset_serial_number'] = $input['asset_serial_number'] ?? "-";
      $input['updated_at'] = date('Y-m-d H:i:s');
      $input['updated_by'] = Session::get('id');
      $validator = Validator::make($input, [
          "asset_number" => "required",
          "asset_capitalized_on"=> "required",
          "asset_desc"=> "required",
          "asset_quantity"=> "required",
          "asset_po"=> "required",
          "departement_id"=> "required",
          "count_id"=> "required",
          "category_id"=> "required",
      ]);

      if($validator->fails()){
          return back()->with('warning', 'Data is null');
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



      if($asset){
        return back()->with('success', "Input Asset successfully");
      }
  }

  public function destroy(Request $request){
    $input = $request->all();
    $id = $input['id_asset'];
    $asset = Asset::destroy($id);
    if($asset){
      return back()->with('success', "Deleted Asset successfully");
    }else{
      return back()->with('warning', 'Deleted Asset fail');
    }
  }

  public function import(Request $request)
  {
    Excel::import(new ImportAsset(), $request->file('file'));
    return back()->with('success', "Asset has been imported");
  }

  

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}