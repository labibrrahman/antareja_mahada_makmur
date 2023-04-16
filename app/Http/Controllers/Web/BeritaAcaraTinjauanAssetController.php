<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\BeritaAcaraTinjauanAsset;
use App\Models\Departement;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TinjauanAsset;
use Validator;
use DataTables;
use Session;
use DB;

class BeritaAcaraTinjauanAssetController extends Controller
{
    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      if ($request->ajax()) {
        $data = BeritaAcaraTinjauanAsset::leftjoin('departments', 'departments.id', '=', 'ba_tinjauan_asset.departement_id')
        ->get(['ba_tinjauan_asset.*', DB::raw('DATE_FORMAT(ba_tinjauan_asset.tgl_awal, "%d-%b-%Y") as tgl_awal_desc'), DB::raw('DATE_FORMAT(ba_tinjauan_asset.tgl_akhir, "%d-%b-%Y") as tgl_akhir_desc'), 
        DB::raw("(
          CASE 
          WHEN ba_tinjauan_asset.departement_id = 0 THEN 'ALL'
          ELSE departments.department
          END) as dept_desc")]);
          return Datatables::of($data)
          ->addIndexColumn()
          ->addColumn('action', function($row){
                // $btn =  '<a href="/tinjauan_asset_print/'.$row['departement_id'].'/'.$row['tgl_awal'].'/'.$row['tgl_akhir'].'")" class="btnPrints btn btn-warning btn-sm" id=""><i class=\'fa fa-print\'></i>Print</a>';
                $btn =  '<a href="/tinjauan_asset_print/'.$row['id'].'")" class="btnPrints btn btn-warning btn-sm" id=""><i class=\'fa fa-print\'></i></a> &nbsp;'.
                        '<a href="/tinjauan_asset_excel/'.$row['id'].'")" class=" btn btn-success btn-sm" id=""><i class=\'fa fa-file-excel\'></i></a> &nbsp;'.
                        '<a href="" data-toggle="modal" onclick=confirmDelete('.$row['id'].','.$row['ba_number'].') data-target="#deletedModal" class="edit btn btn-danger btn-sm"><i class=\'fa fa-trash\'></i></a> &nbsp;';
                        
                return $btn;
          })
          ->rawColumns(['action'])
          ->make(true);
      }
      $departement = json_decode(Departement::all());

      return view('pages.ba_tinjauan_asset', ['title' => 'Berita Acara Tinjauan Asset'])
      ->with('departement',$departement)
      ;
    }

    public function store(Request $request) {
      $input = $request->all();
      $input['departement_id'] = $input['departement_id'] ?? "-";
      $input['created_at'] = date('Y-m-d H:i:s');
      $input['created_by'] = Session::get('id');

      $validator = Validator::make($input, [
          "ba_number" => "required",
          "tgl_awal" => "required",
          "tgl_akhir"=> "required",
          "departement_id"=> "required",
      ]);

      if($validator->fails()){
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => implode(" ", $validator->messages()->all()));
      }else{
        $asset = BeritaAcaraTinjauanAsset::create($input);
        if($asset){
          $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Input Asset successfully');
        }
      }
      echo json_encode($array_alert);
    }
    

    public function get_ba_number(){
      $getDataBATInjauanAsset = json_decode(BeritaAcaraTinjauanAsset::select('*')->orderBy('ba_number', 'DESC')->first());
      if($getDataBATInjauanAsset == null){
        $setNumberBA = 1;
      }else{
        $setNumberBA = $getDataBATInjauanAsset->ba_number + 1;
      }

      return json_decode($setNumberBA);
    }

    public function tinjauan_asset_print($id){
      $get_data_ba_tinjauan_asset = json_decode(BeritaAcaraTinjauanAsset::find($id));
      $date_from = $get_data_ba_tinjauan_asset->tgl_awal;
      $date_to = $get_data_ba_tinjauan_asset->tgl_akhir;
      $departement_id = $get_data_ba_tinjauan_asset->departement_id;

      $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
      $bln = $array_bln[date("n",strtotime($date_to))];
      $year = date("Y",strtotime($date_to));

      $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                      ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                      ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                      ->when($departement_id != 0, function ($query_) use ($departement_id) {
                          return $query_->where('assets.departement_id', $departement_id);
                      })
                      ->when($date_from != "-", function ($query_) use ($date_from, $date_to) {
                          return $query_->whereBetween('assets.asset_capitalized_on', [$date_from, $date_to]);
                      })
                      ->whereIn('assets.id',Upload::select('asset_id'))
                      ->orderBy('assets.asset_capitalized_on')
                      ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count',
                      DB::raw("(
                        CASE 
                        WHEN assets.asset_condition = 'sb' THEN 'Sangat Baik'
                        WHEN assets.asset_condition = 'b' THEN 'Baik'
                        WHEN assets.asset_condition = 'rd' THEN 'Rusak, dapat diperbaiki'
                        WHEN assets.asset_condition = 'rt' THEN 'Rusak, tidak dapat diperbaiki'
                        WHEN assets.asset_condition = 'h' THEN 'Hilang'
                        ELSE ''
                        END) as asset_condition")
                    ]);
      $asset = json_decode($query_asset);
      // dd($asset);
      foreach($asset as $data_asset){
          $query_upload = Upload::where('asset_id', $data_asset->id)->get();
          $get_photo = json_decode($query_upload);
          foreach($get_photo as $set_photo){
              $data_asset->photo[] = $set_photo->upload_image;
          }
          $data_asset->count_photo = count($get_photo);
      }
  
      $getDept = json_decode(Departement::select('department')->where('id', $departement_id)->get());
      if($getDept == null){
          $getDept = 'ALL';
      }else{
          $getDept = reset($getDept)->department;
      }
      return view('pages.berita_acara.tinjauan_asset',['title' => 'Berita Acara'])
      ->with('dept', $getDept)
      ->with('bln', $bln)
      ->with('year', $year)
      ->with('no_ba', $get_data_ba_tinjauan_asset->ba_number)
      ->with('asset_data', $asset);
    }

    public function tinjauan_asset_excel($id)
    {
      // dd(new TinjauanAsset($id));
      $get_data_ba_tinjauan_asset = json_decode(BeritaAcaraTinjauanAsset::find($id));
      return Excel::download(new TinjauanAsset($id), "exportTinjauanAsset_".$get_data_ba_tinjauan_asset->ba_number.".xlsx");
    }

    // public function tinjauan_asset_excel($id){
    //   $get_data_ba_tinjauan_asset = json_decode(BeritaAcaraTinjauanAsset::find($id));
    //   $date_from = $get_data_ba_tinjauan_asset->tgl_awal;
    //   $date_to = $get_data_ba_tinjauan_asset->tgl_akhir;
    //   $departement_id = $get_data_ba_tinjauan_asset->departement_id;

    //   $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
    //   $bln = $array_bln[date("n",strtotime($date_to))];
    //   $year = date("Y",strtotime($date_to));

    //   $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
    //                   ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
    //                   ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
    //                   ->when($departement_id != 0, function ($query_) use ($departement_id) {
    //                       return $query_->where('assets.departement_id', $departement_id);
    //                   })
    //                   ->when($date_from != "-", function ($query_) use ($date_from, $date_to) {
    //                       return $query_->whereBetween('assets.asset_capitalized_on', [$date_from, $date_to]);
    //                   })
    //                   ->whereIn('assets.id',Upload::select('asset_id'))
    //                   ->orderBy('assets.asset_capitalized_on')
    //                   ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count',
    //                   DB::raw("(
    //                     CASE 
    //                     WHEN assets.asset_condition = 'sb' THEN 'Sangat Baik'
    //                     WHEN assets.asset_condition = 'b' THEN 'Baik'
    //                     WHEN assets.asset_condition = 'rd' THEN 'Rusak, dapat diperbaiki'
    //                     WHEN assets.asset_condition = 'rt' THEN 'Rusak, tidak dapat diperbaiki'
    //                     WHEN assets.asset_condition = 'h' THEN 'Hilang'
    //                     ELSE ''
    //                     END) as asset_condition")
    //                 ]);
    //   $asset = json_decode($query_asset);
    //   // dd($asset);
    //   foreach($asset as $data_asset){
    //       $query_upload = Upload::where('asset_id', $data_asset->id)->get();
    //       $get_photo = json_decode($query_upload);
    //       foreach($get_photo as $set_photo){
    //           $data_asset->photo[] = $set_photo->upload_image;
    //       }
    //       $data_asset->count_photo = count($get_photo);
    //   }
  
    //   $getDept = json_decode(Departement::select('department')->where('id', $departement_id)->get());
    //   if($getDept == null){
    //       $getDept = 'ALL';
    //   }else{
    //       $getDept = reset($getDept)->department;
    //   }
    //   return view('pages.berita_acara.tinjauan_asset',['title' => 'Berita Acara'])
    //   ->with('dept', $getDept)
    //   ->with('bln', $bln)
    //   ->with('year', $year)
    //   ->with('no_ba', $get_data_ba_tinjauan_asset->ba_number)
    //   ->with('asset_data', $asset);
    // }

    public function destroy(Request $request){
      $input = $request->all();
      
      $ba_destroy = BeritaAcaraTinjauanAsset::destroy($input['id']);
      if($ba_destroy){
        $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Deleted Asset successfully');
      }else{
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => 'Deleted Asset fail');
      }
      echo json_encode($array_alert);
    }

    // public function ba_disposal_asset($id){
    //   $get_disposal = json_decode(Mutations::select("*")->where('id', $id)->first());
    //   // dd($get_mutation);
    //   $no_ba = count(json_decode(Mutations::select("id")->where('status', 'r')->whereDate('created_at', '<=', $get_disposal->created_at)->get()));

    //   $data = MutationsDet::leftJoin('assets','assets.id','=','detail_mutations.asset_id')
    //           ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
    //           ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
    //           ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
    //           ->leftjoin('mutations', 'mutations.id', '=', 'detail_mutations.mutasi_id')
    //           ->leftjoin('users', 'users.id', '=', 'mutations.user_id')
    //           ->where('detail_mutations.mutasi_id',$id)
    //           ->where('mutations.status','r')
    //           ->get(['assets.*','users.full_name as name','departments.department','categories.id as category_id','categories.category','counts.count',
    //           DB::raw("(
    //             CASE 
    //             WHEN assets.asset_condition='sb' THEN 'Sangat Baik'
    //             WHEN assets.asset_condition='b' THEN 'Baik '
    //             WHEN assets.asset_condition='rd' THEN 'Rusak,diperbaiki '
    //             WHEN assets.asset_condition='rt' THEN 'Rusak, tidak dapat diperbaiki'
    //             WHEN assets.asset_condition='h' THEN 'Hilang'
    //             ELSE ''
    //             END) as assets_cond")
    //             ]);

    //   $json_data = json_decode($data);
    //   $user_req = reset($json_data)->name;

    //   return view('pages.berita_acara.disposal_asset',['title' => 'Berita Acara'])
    //   ->with('no_ba', $no_ba)
    //   ->with('mutation_data', $json_data)
    //   ->with('user_req', $user_req);
    // }

    // public function getDataDetailMutations($id){
    //   $data = MutationsDet::leftjoin('assets','assets.id','=','detail_mutations.asset_id')
    //   ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
    //   ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
    //   ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
    //   ->where('detail_mutations.mutasi_id', $id)
    //   ->get(['assets.*','departments.department as department','category','counts.count as count']);
    //   return Datatables::of($data)->make(true);;
    // }

    // public function getDataAsset(Request $request)
    // {
    //       $post = $request->all();
    //       $query = Asset::select('*')->where('id' , $post['id'])->get();
    //       foreach($query as $query_data){
    //         $data = $query_data;
    //       }
    //       $query_upload = json_decode(Upload::select('*')->where('asset_id', $post['id'])->get());
    //       $photo = [];
    //       $i = 0;
    //       foreach($query_upload as $data_image){
    //         $photo[$i] = array('id_image' => $data_image->id ,'image' => $data_image->upload_image);
    //         $i++;
    //       }
    //       $data->photo = $photo;

    //       return json_decode($data);
    // }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}