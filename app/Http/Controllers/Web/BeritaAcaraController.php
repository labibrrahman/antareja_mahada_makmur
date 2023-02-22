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
        return view('pages.berita_acara.tinjauan_asset',['title' => 'Berita Acara']);

    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}