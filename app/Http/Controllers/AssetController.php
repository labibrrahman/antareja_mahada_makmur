<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AssetController extends Controller
{
    public function asset() {
        $data = "Data All Asset";
        return response()->json($data, 200);
    }

    public function assetAuth() {
        $data = "Welcome " . Auth::user()->name;
        return response()->json($data, 200);
    }
}