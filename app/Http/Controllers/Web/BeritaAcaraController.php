<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\MutationsDet;
use App\Models\Categories;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class BeritaAcaraController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departement = json_decode(Departement::all());

        return view('pages.berita_acara.index',['title' => 'Berita Acara'])
        ->with('departement',$departement);
        
    }

    public function tinjauan_asset($departement_id){

        $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                        ->when($departement_id != 0, function ($query_) use ($departement_id) {
                            return $query_->where('assets.departement_id', $departement_id);
                        })
                        ->whereIn('assets.id',Upload::select('asset_id'))
                        ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count']);

        $asset = json_decode($query_asset);
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
        ->with('asset_data', $asset);

    }

    public function disposal_asset(){

        $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                        ->whereIn('assets.id',Upload::select('asset_id'))
                        ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count']);

        $asset = json_decode($query_asset);
        foreach($asset as $data_asset){
            $query_upload = Upload::where('asset_id', $data_asset->id)->get();
            $get_photo = json_decode($query_upload);
            foreach($get_photo as $set_photo){
                $data_asset->photo[] = $set_photo->upload_image;
            }
            $data_asset->count_photo = count($get_photo);
        }

        return view('pages.berita_acara.disposal_asset',['title' => 'Berita Acara'])
        ->with('asset_data', $asset);

    }

    public function mutasi_asset(){

        $query_asset = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
                        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
                        ->whereIn('assets.id',Upload::select('asset_id'))
                        ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count']);

        $asset = json_decode($query_asset);
        foreach($asset as $data_asset){
            $query_upload = Upload::where('asset_id', $data_asset->id)->get();
            $get_photo = json_decode($query_upload);
            foreach($get_photo as $set_photo){
                $data_asset->photo[] = $set_photo->upload_image;
            }
            $data_asset->count_photo = count($get_photo);
        }

        return view('pages.berita_acara.mutasi_asset',['title' => 'Berita Acara'])
        ->with('asset_data', $asset);

    }

    
    

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}