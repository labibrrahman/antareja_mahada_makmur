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
    public function index(Request $request)
    {
        $titlechart = "Asset Masuk/Bulan";
        $month_monitoring = 12;
        $year_monitoring = date("Y");
        if (isset($request->all()['set_year'])) {
            $year_monitoring = $request->all()['set_year'];
        }
        $set_year = $year_monitoring;
        // $year_monitoring = '2022';
        // $year_monitoring = date("Y");

        $countAsset = $this->getAllAsset($year_monitoring);

        $setPrice = $this->setPrice($year_monitoring);

        $get_total_haraga_by_dept = $this->get_total_haraga_by_dept($year_monitoring);

        $departement = json_decode(Departement::all());
        $dept_count_upload = array();
        $dept_count_non_upload = array();
        foreach ($departement as $data_dept) {

            $query_getnonupload = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->where('departement_id', $data_dept->id)
                ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                    return $query->whereYear('asset_capitalized_on', $year_monitoring);
                })
                ->whereNotIn('assets.id', MutationsDet::select('asset_id'))
                ->whereNotIn('assets.id', Upload::select('asset_id'))
                ->get();

            $query_getupload = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->where('departement_id', $data_dept->id)
                ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                    return $query->whereYear('asset_capitalized_on', $year_monitoring);
                })
                ->whereNotIn('assets.id', MutationsDet::select('asset_id'))
                ->whereIn('assets.id', Upload::select('asset_id'))
                ->get();

            $dept_count_non_upload[] = ['dept' => $data_dept->department, 'total' => count($query_getnonupload)];
            $dept_count_upload[] = ['dept' => $data_dept->department, 'total' => count($query_getupload)];
        }

        $queryYear = Asset::select(DB::raw('YEAR(asset_capitalized_on) as year'))
            ->groupBy('year')
            ->get();

        if ($year_monitoring != 'all') {
            $setLable = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Des"];
            $max_data = $month_monitoring;
        } else {
            foreach (json_decode($queryYear) as $getYear) {
                $setLable[] = (string)$getYear->year;
            }
            $max_data = count($setLable);
        }
        $pemasukanAsset = array();
        $pemasukanAsset[0] = ['Month', 'Asset'];
        for ($i = 1; $i <= $max_data; $i++) {
            $pemasukanAsset[$i] = [$setLable[$i - 1], (int)count(Asset::select('id')
                // ->where('assets.departement_id',Session::get('departement_id'))
                ->when($year_monitoring != 'all', function ($query) use ($i, $year_monitoring) {
                    return $query->whereMonth('asset_capitalized_on', $i)
                        ->whereYear('asset_capitalized_on', $year_monitoring);
                })
                ->when($year_monitoring == 'all', function ($query) use ($i, $setLable) {
                    return $query->whereYear('asset_capitalized_on', $setLable[$i - 1]);
                })
                ->get())];

            //dummy
            // $pemasukanAsset[$i] = [$month[$i-1], (int)rand(2,99)];
        }

        $labelAsset = array();
        $labelAsset[0] = ['Month', 'Asset'];
        for ($i = 1; $i <= $max_data; $i++) {
            $labelAsset[$i] = [$setLable[$i - 1], (int)count(Asset::select('id')
                // ->where('assets.departement_id',Session::get('departement_id'))
                ->whereIn('assets.id', Upload::select('asset_id'))
                ->whereNotIn('assets.id', MutationsDet::select('asset_id'))
                ->when($year_monitoring != 'all', function ($query) use ($i, $year_monitoring) {
                    return $query->whereMonth('asset_capitalized_on', $i)
                        ->whereYear('asset_capitalized_on', $year_monitoring);
                })
                ->when($year_monitoring == 'all', function ($query) use ($i, $setLable) {
                    return $query->whereYear('asset_capitalized_on', $setLable[$i - 1]);
                })
                ->get())];

            //dummy
            // $labelAsset[$i] = [$month[$i-1], (int)rand(2,99)];
        }

        $dataByCategory = array();
        $dataByCategory[0] = ['Category', 'data'];
        $getCategory = Asset::select('category_id')
            // ->where('departement_id',Session::get('departement_id'))
            ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                return $query->whereYear('asset_capitalized_on', $year_monitoring);
            })
            ->groupBy('category_id')->get();
        $getCategoryCode = array();
        $dataCategory = json_decode($getCategory);
        $i = 1;
        if (empty($dataCategory)) {
            $dataByCategory[1] = ['', 0];
        } else {
            foreach ($dataCategory as $data) {
                $get_category_name = Categories::select('id', 'category')->where('id', $data->category_id)->get();
                $category_name = json_decode($get_category_name)[0]->category;
                $category_count = count(Asset::select('id')
                    // ->where('departement_id',Session::get('departement_id'))
                    ->where('category_id', json_decode($get_category_name)[0]->id)
                    ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                        return $query->whereYear('asset_capitalized_on', $year_monitoring);
                    })
                    ->get());

                $dataByCategory[$i] = [$category_name, $category_count];

                // dummy
                // $dataByCategory[$i] = [$category_name, (int)rand(2,99)];

                $i++;
            }
        }

        $arr_chart = [];
        foreach ($dept_count_upload as $i => $upload) {
            $arr_chart[$i]['dept'] = $upload['dept'];
            $arr_chart[$i]['upload'] = $upload['total'];
            foreach ($dept_count_non_upload as $nonUpload) {
                if ($upload['dept'] == $nonUpload['dept']) {
                    $arr_chart[$i]['non_upload'] = $nonUpload['total'];
                    $totalAsset =  $arr_chart[$i]['upload'] + $arr_chart[$i]['non_upload'];

                    $arr_chart[$i]['percentage'] = $totalAsset != 0 ? number_format(($arr_chart[$i]['upload'] / $totalAsset) * 100, 2, '.', '') : 0;

                    break;
                }
            }
        }

        // dd($arr_chart);

        return view('pages.home', ['title' => 'Dashboard'])
            ->with('total_asset', $countAsset)
            ->with('asset_price', $setPrice)
            ->with('get_total_haraga_by_dept', $get_total_haraga_by_dept)
            ->with('count_asset_noupload', $dept_count_non_upload)
            ->with('count_asset_upload', $dept_count_upload)
            ->with('data_pemasukan', json_encode($pemasukanAsset))
            ->with('label_asset', json_encode($labelAsset))
            ->with('asset_by_category', json_encode($dataByCategory))
            ->with('year', $queryYear)
            ->with('arr_chart', json_encode($arr_chart))
            ->with('set_year', $set_year);
    }

    public function getAllAsset($year_monitoring)
    {
        $getAllAsset = Asset::select('*')
            ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                return $query->whereYear('asset_capitalized_on', $year_monitoring);
            })
            ->get();
        return count($getAllAsset);
    }

    public function setPrice($year_monitoring)
    {
        $getPrice = Asset::select('asset_price')
            ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                return $query->whereYear('asset_capitalized_on', $year_monitoring);
            })
            ->get();
        $getPrice = json_decode($getPrice);
        $setPrice = 0;
        foreach ($getPrice as $price) {
            $data_price = (int)$price->asset_price;
            $setPrice = $data_price + $setPrice;
        }
        return number_format($setPrice, 2);
    }

    public function get_total_haraga_by_dept($year_monitoring)
    {
        $departement = json_decode(Departement::all());
        $harga_by_dept = array();
        foreach ($departement as $data_dept) {
            $query_getupload = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
                ->where('departement_id', $data_dept->id)
                ->when($year_monitoring != 'all', function ($query) use ($year_monitoring) {
                    return $query->whereYear('asset_capitalized_on', $year_monitoring);
                })
                ->whereNotIn('assets.id', MutationsDet::select('asset_id'))
                // ->whereIn('assets.id',Upload::select('asset_id'))
                ->get(['department', 'assets.asset_price']);

            $getPrice = json_decode($query_getupload);
            $setPrice = 0;
            foreach ($getPrice as $price) {
                $data_price = (int)$price->asset_price;
                $setPrice = $data_price + $setPrice;
            }
            $harga_by_dept[] = ['dept' => $data_dept->department, 'total_asset' => count($query_getupload), 'total' => 'Rp. ' . number_format($setPrice, 2)];
        }

        return $harga_by_dept;
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
