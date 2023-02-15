<?php
namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Upload;
use App\Models\MutationsDet;
use App\Models\Categories;
use DB;
class HomeController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $titlechart = "Asset Masuk/Bulan";
        $month_monitoring = 12;
        $year_monitoring = date("Y"); 
        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Des"];
        $pemasukanAsset = array();
        $pemasukanAsset[0] = ['Month','Asset'];
        for ($i=1; $i < $month_monitoring; $i++) { 
            $pemasukanAsset[$i] = [$month[$i-1], (int)count(Asset::select('id')->whereMonth('created_at', $i)->get())];
        }

        $labelAsset = array();
        $labelAsset[0] = ['Month','Asset'];
        for ($i=1; $i < $month_monitoring; $i++) { 
            $labelAsset[$i] = [$month[$i-1], (int)count(Asset::select('id')
                                                    ->whereIn('assets.id',Upload::select('asset_id'))
                                                    ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                                                    ->whereMonth('created_at', $i)
                                                    ->get())];
        }

        // $labelAsset = array();
        // $labelAsset[0] = ['Month','Asset'];
        $getCategory = Asset::select('category_id')->groupBy('category_id')->get();
        $getCategoryCode = array();
        $dataCategory = json_decode($getCategory);
        foreach($dataCategory as $data){
            $get_category_name = Categories::select('category')->where('id', $data->category_id)->get();
            $category_name = json_decode($get_category_name)[0]->category;
            //Set Jumlah Category yang tersedia pada asset
        }


        return view('pages.home',['title' => 'Dashboard'])
            ->with('data_pemasukan',json_encode($pemasukanAsset))
            ->with('label_asset',json_encode($labelAsset));
    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('pages.contact');
    }
}