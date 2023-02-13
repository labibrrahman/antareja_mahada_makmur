<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
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
        $month_1 = date("m"); 
        $month_2 = date("m", strtotime("-1 months", strtotime("NOW"))); 
        $month_3 = date("m", strtotime("-2 months", strtotime("NOW"))); 
        $month_4 = date("m", strtotime("-3 months", strtotime("NOW"))); 

        $month_name_1 = date("M")." ".date("Y"); 
        $month_name_2 = date("M", strtotime("-1 months", strtotime("NOW")))." ".date("Y", strtotime("-1 months", strtotime("NOW"))); 
        $month_name_3 = date("M", strtotime("-2 months", strtotime("NOW")))." ".date("Y", strtotime("-2 months", strtotime("NOW"))); 
        $month_name_4 = date("M", strtotime("-3 months", strtotime("NOW")))." ".date("Y", strtotime("-3 months", strtotime("NOW"))); 

        $asset_1 = count(Asset::select('id')->whereMonth('created_at', $month_1)->get());
        $asset_2 = count(Asset::select('id')->whereMonth('created_at', $month_2)->get());
        $asset_3 = count(Asset::select('id')->whereMonth('created_at', $month_3)->get());
        $asset_4 = count(Asset::select('id')->whereMonth('created_at', $month_4)->get());

        $result[0] = ['Month','Asset'];

        $result[1] = [$month_name_4, (int)$asset_4];
        $result[2] = [$month_name_3, (int)$asset_3];
        $result[3] = [$month_name_2, (int)$asset_2];
        $result[4] = [$month_name_1, (int)$asset_1];

        // $visitor = Asset::select(
        //     DB::raw("id as year"),
        //     DB::raw("count(asset_number) as total_click"),
        //     DB::raw("count(asset_manager) as total_viewer")) 
        // ->orderBy(DB::raw("created_at"))
        // ->groupBy("created_at","assets.id","asset_number","asset_manager")
        // ->get();

        // $result[] = ['Year','Click'];
        // foreach ($visitor as $key => $value) {
        // $result[++$key] = [$value->year, (int)$value->total_click];
        // }

        return view('pages.home',['title' => 'Dashboard'])
            ->with('visitor',json_encode($result));
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