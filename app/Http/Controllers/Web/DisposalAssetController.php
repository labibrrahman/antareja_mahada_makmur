<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\Counts;
use App\Models\Categories;
use App\Models\Departement;
use App\Models\Mutations;
use App\Models\MutationsDet;
use Illuminate\Support\Facades\Auth;
use Validator;
use DataTables;
use Session;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportAsset;
use Illuminate\Support\Facades\Storage;

class DisposalAssetController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      if ($request->ajax()) {
        $data = Mutations::leftjoin('users', 'users.id', '=', 'mutations.user_id')
        ->orderBy('mutations.created_at', 'DESC')
        ->where('mutations.status','r')
        ->get(['mutations.id as id', 'users.full_name as name',DB::raw('DATE_FORMAT(mutations.created_at, "%d-%b-%Y") as created_ats'),
          DB::raw("(
          CASE 
          WHEN mutations.status='m' THEN 'Mutasi'
          WHEN mutations.status='r' THEN 'Rusak'
          ELSE ''
          END) as mutations_stat")
          ]);
          return Datatables::of($data)
          ->addIndexColumn()
          ->addColumn('action', function($row){
                $btn =  '<a href="" data-toggle="modal" onclick=getDetailMutation('.$row['id'].') data-target="#exampleModal" class="edit btn btn-success btn-sm">Detail Mutation</a>&nbsp;'.
                        '<a href="/disposal_asset/ba_disposal_asset/'.$row['id'].'")" class="btnPrints btn btn-warning btn-sm" id=""><i class=\'fa fa-print\'></i>Print</a>';
                return $btn;
          })
          ->rawColumns(['action'])
          ->make(true);
      }
      //   $data = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
      //   ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
      //   ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
      //   ->leftjoin('detail_mutations', 'detail_mutations.asset_id', '=', 'assets.id')
      //   ->leftjoin('mutations', 'mutations.id', '=', 'detail_mutations.mutasi_id')
      //   ->whereIn('assets.id',MutationsDet::select('asset_id'))
      //   ->orderBy('assets.asset_capitalized_on', 'DESC')
      //   ->get(['assets.*','departments.department as department','category','counts.count as count', 'detail_mutations.description as desc_mutation', 
      //   DB::raw("(
      //   CASE 
      //   WHEN mutations.status='sb' THEN 'Sangat Baik'
      //   WHEN mutations.status='b' THEN 'Baik '
      //   WHEN mutations.status='rd' THEN 'Rusak,diperbaiki'
      //   WHEN mutations.status='rt' THEN 'Rusak,tdk dapat diperbaiki'
      //   WHEN mutations.status='h' THEN 'Hilang'
      //   ELSE ''
      //   END) as mutations_stat")
      //   ]);
      //   

      return view('pages.disposal_asset', ['title' => 'Disposal Asset']);
    }

    public function ba_disposal_asset($id){
      $get_disposal = json_decode(Mutations::select("*")->where('id', $id)->first());
      // dd($get_mutation);
      $no_ba = count(json_decode(Mutations::select("id")->where('status', 'r')->whereDate('created_at', '<=', $get_disposal->created_at)->get()));

      $data = MutationsDet::leftJoin('assets','assets.id','=','detail_mutations.asset_id')
              ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
              ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
              ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
              ->leftjoin('mutations', 'mutations.id', '=', 'detail_mutations.mutasi_id')
              ->leftjoin('users', 'users.id', '=', 'mutations.user_id')
              ->where('detail_mutations.mutasi_id',$id)
              ->where('mutations.status','r')
              ->get(['assets.*','users.full_name as name','departments.department','categories.id as category_id','categories.category','counts.count',
              DB::raw("(
                CASE 
                WHEN assets.asset_condition='sb' THEN 'Sangat Baik'
                WHEN assets.asset_condition='b' THEN 'Baik '
                WHEN assets.asset_condition='rd' THEN 'Rusak,diperbaiki '
                WHEN assets.asset_condition='rt' THEN 'Rusak, tidak dapat diperbaiki'
                WHEN assets.asset_condition='h' THEN 'Hilang'
                ELSE ''
                END) as assets_cond")
                ]);

      $json_data = json_decode($data);
      $user_req = reset($json_data)->name;

      return view('pages.berita_acara.disposal_asset',['title' => 'Berita Acara'])
      ->with('no_ba', $no_ba)
      ->with('mutation_data', $json_data)
      ->with('user_req', $user_req);
    }

    public function getDataDetailMutations($id){
      $data = MutationsDet::leftjoin('assets','assets.id','=','detail_mutations.asset_id')
      ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
      ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
      ->leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
      ->where('detail_mutations.mutasi_id', $id)
      ->get(['assets.*','departments.department as department','category','counts.count as count']);
      return Datatables::of($data)->make(true);;
    }

    public function getDataAsset(Request $request)
    {
          $post = $request->all();
          $query = Asset::select('*')->where('id' , $post['id'])->get();
          foreach($query as $query_data){
            $data = $query_data;
          }
          $query_upload = json_decode(Upload::select('*')->where('asset_id', $post['id'])->get());
          $photo = [];
          $i = 0;
          foreach($query_upload as $data_image){
            $photo[$i] = array('id_image' => $data_image->id ,'image' => $data_image->upload_image);
            $i++;
          }
          $data->photo = $photo;

          return json_decode($data);
    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}