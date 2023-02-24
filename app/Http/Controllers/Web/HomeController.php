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
        $year_monitoring = '2022'; 
        // $year_monitoring = date("Y"); 
        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Des"];
        $pemasukanAsset = array();
        $pemasukanAsset[0] = ['Month','Asset'];
        for ($i=1; $i <= $month_monitoring; $i++) { 
            $pemasukanAsset[$i] = [$month[$i-1], (int)count(Asset::select('id')
                                                            // ->where('assets.departement_id',Session::get('departement_id'))
                                                            ->whereMonth('asset_capitalized_on', $i)
                                                            ->whereYear('asset_capitalized_on', $year_monitoring)
                                                            ->get())];

                //dummy
                // $pemasukanAsset[$i] = [$month[$i-1], (int)rand(2,99)];
        }

        $labelAsset = array();
        $labelAsset[0] = ['Month','Asset'];
        for ($i=1; $i <= $month_monitoring; $i++) { 
            $labelAsset[$i] = [$month[$i-1], (int)count(Asset::select('id')
                                                    // ->where('assets.departement_id',Session::get('departement_id'))
                                                    ->whereIn('assets.id',Upload::select('asset_id'))
                                                    ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                                                    ->whereMonth('asset_capitalized_on', $i)
                                                    ->whereYear('asset_capitalized_on', $year_monitoring)
                                                    ->get())];

            //dummy
            // $labelAsset[$i] = [$month[$i-1], (int)rand(2,99)];
        }

        $dataByCategory = array();
        $dataByCategory[0] = ['Category','data'];
        $getCategory = Asset::select('category_id')
                        // ->where('departement_id',Session::get('departement_id'))
                        ->whereYear('asset_capitalized_on', $year_monitoring)
                        ->groupBy('category_id')->get();
        $getCategoryCode = array();
        $dataCategory = json_decode($getCategory);
        $i = 1;
        foreach($dataCategory as $data){
            $get_category_name = Categories::select('id','category')->where('id', $data->category_id)->get();
            $category_name = json_decode($get_category_name)[0]->category;
            $category_count = count(Asset::select('id')
                                ->where('departement_id',Session::get('departement_id'))
                                ->where('category_id',json_decode($get_category_name)[0]->id)
                                ->get());
            
            $dataByCategory[$i] = [$category_name, $category_count];

            // dummy
            // $dataByCategory[$i] = [$category_name, (int)rand(2,99)];
            
            $i++;
        }

        $getAllAsset = Asset::all();
        $countAsset = count($getAllAsset);

        $getPrice = Asset::select('asset_price')->get();
        $getPrice = json_decode($getPrice);
        $setPrice = 0;
        foreach($getPrice as $price){
            $data_price = (int)$price->asset_price;
            $setPrice = $data_price + $setPrice;
        }
        $setPrice = number_format($setPrice, 2);


        $getAllYear = '';
        $queryYear = Asset::select(DB::raw('YEAR(created_at) as year'))
                    ->groupBy('year')
                    ->get();

        $departement = json_decode(Departement::all());
        $dept_count_upload = array();
        $dept_count_non_upload = array();
        foreach ($departement as $data_dept) {

            $query_getnonupload = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                            ->where('departement_id', $data_dept->id)
                            ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                            ->whereNotIn('assets.id',Upload::select('asset_id'))
                            ->get();

            $query_getupload = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                            ->where('departement_id', $data_dept->id)   
                            ->whereNotIn('assets.id',MutationsDet::select('asset_id'))
                            ->whereIn('assets.id',Upload::select('asset_id'))
                            ->get();

            $dept_count_non_upload[] = ['dept' => $data_dept->department, 'total'=>count($query_getnonupload)];
            $dept_count_upload[] = ['dept' => $data_dept->department, 'total'=>count($query_getupload)];
        }

        return view('pages.home',['title' => 'Dashboard'])
            ->with('data_pemasukan',json_encode($pemasukanAsset))
            ->with('label_asset',json_encode($labelAsset))
            ->with('asset_by_category',json_encode($dataByCategory))
            ->with('asset_price',$setPrice)
            ->with('year',$queryYear)
            ->with('count_asset',$countAsset)
            ->with('count_asset_upload',$dept_count_upload)
            ->with('count_asset_noupload',$dept_count_non_upload);
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