<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\MutationsDet;
use App\Models\Categories;
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

        // return view('pages.berita_acara.index',['title' => 'Dashboard']);
        return view('pages.berita_acara.index',['title' => 'Berita Acara']);
        
    }

    public function tinjauan_asset(){
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
        }

        return view('pages.berita_acara.tinjauan_asset',['title' => 'Berita Acara'])
        ->with('asset_data', $asset);

    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}