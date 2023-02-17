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
        ->orderBy('assets.created_at', 'DESC')
        ->get(['assets.*','departments.department as department','category','counts.count as count']);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                      $btn = '<a href="" data-toggle="modal" onclick=getasset_ajax('.$row['id'].') data-target="#exampleModal" class="edit btn btn-success btn-sm">Add Price</a>';
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

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}