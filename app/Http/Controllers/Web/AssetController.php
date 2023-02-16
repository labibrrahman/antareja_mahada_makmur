<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use Validator;
use DataTables;
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
      return view('pages.asset', ['title' => 'Asset']);
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

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}