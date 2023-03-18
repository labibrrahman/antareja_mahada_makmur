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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportAsset;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{

    /**
     * Show the application home.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      if ($request->ajax()) {
        $data = Asset::leftjoin('departments', 'departments.id', '=', 'assets.departement_id')
        ->leftjoin('categories', 'categories.id', '=', 'assets.category_id')
        ->leftjoin('counts', 'counts.id', '=', 'assets.count_id')
        ->orderBy('assets.asset_capitalized_on', 'DESC')
        ->get(['assets.*','departments.department as department','category','counts.count as count']);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                      $btn =  '<a href="" data-toggle="modal" onclick=getasset_ajax('.$row['id'].') data-target="#exampleModal" class="edit btn btn-success btn-sm">Add Price</a>&nbsp;'.
                              '<a href="" data-toggle="modal" onclick=getupdate_ajax('.$row['id'].') data-target="#updateAsset" class="edit btn btn-warning btn-sm">Edit</a>&nbsp;'.
                              '<a href="" data-toggle="modal" onclick=getupdate_image('.$row['id'].') data-target="#updateImage" class="edit btn btn-info btn-sm">Photo</a>&nbsp;'.
                              '<a href="" data-toggle="modal" onclick=confirmDelete('.$row['id'].') data-target="#deletedModal" class="edit btn btn-danger btn-sm">Deleted</a>';
                      return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
      }

      $count = json_decode(Counts::all());
      $categories = json_decode(Categories::all());
      $departement = json_decode(Departement::all());
      return view('pages.asset', ['title' => 'Asset'])
      ->with('count',$count)
      ->with('departement',$departement)
      ->with('categories',$categories);
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

    public function storePrice(Request $request)
    {
      $input = $request->all();

      $validator = Validator::make($input, [
        'id_asset' => 'required',
        'asset_price' => 'required',
      ]);

      if($validator->fails()){
        // return back()->with('warning', implode(" ", $validator->messages()->all()));
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => implode(" ", $validator->messages()->all()));
      }

      $asset = Asset::find($input['id_asset']);   
      $asset->asset_price = $input['asset_price'];
      $asset->save();

      if($asset){
        $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Input Asset Price successfully');
      }
      echo json_encode($array_alert);
    }

    public function store(Request $request) {
      $input = $request->all();
      // $input['asset_manager'] = Session::get('id');
      $input['asset_manager'] = "-";
      $input['asset_status'] = "-";
      if($input['asset_serial_number'] == ''){
        $input['asset_serial_number'] = "-";
      }
      $input['asset_po'] = $input['asset_po'] ?? "-";
      $input['created_at'] = date('Y-m-d H:i:s');
      $input['created_by'] = Session::get('id');

      $validator = Validator::make($input, [
          "asset_number" => "required",
          "asset_serial_number"=> "required",
          "asset_capitalized_on"=> "required",
          "asset_desc"=> "required",
          "asset_quantity"=> "required",
          "departement_id"=> "required",
          "count_id"=> "required",
          "category_id"=> "required",
      ]);

      if($validator->fails()){
        // return back()->with('warning', implode(" ", $validator->messages()->all()));
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => implode(" ", $validator->messages()->all()));
      }else{
        $asset = Asset::create($input);
        if($asset){
          $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Input Asset successfully');
        }
      }
      echo json_encode($array_alert);
    }

  public function update(Request $request) {
      
      $input = $request->all();
      $id = $input['id_asset'];
      $input['asset_manager'] = "-";
      $input['asset_status'] = "-";
      $input['asset_serial_number'] = $input['asset_serial_number'] ?? "-";
      $input['asset_po'] = $input['asset_po'] ?? "-";
      $input['updated_at'] = date('Y-m-d H:i:s');
      $input['updated_by'] = Session::get('id');

      $validator = Validator::make($input, [
        "asset_number" => "required",
        "asset_serial_number"=> "required",
        "asset_capitalized_on"=> "required",
        "asset_desc"=> "required",
        "asset_quantity"=> "required",
        "departement_id"=> "required",
        "count_id"=> "required",
        "category_id"=> "required",
        // "file0"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
        // "file1"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
        // "file2"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
      ]);

      if($validator->fails()){
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => implode(" ", $validator->messages()->all()));
      }

      // if((isset($input['file0'])) && ($input['file0'] != null)){
      //   if($input['id_upload0'] == null){
      //     //make new input upload
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1,
      //       'created_by' => Session::get('id'),
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file0');
      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;
          
      //     $update_asset = Asset::find($id);   
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //         $data = Upload::create($set_data_upload);
      //     }
      //   }else{
      //     //update data upload or image
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file0');

      //     $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload0'])->first());
      //     $stat = Storage::disk('public')->delete($get_image->upload_image);

      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;

      //     $update_asset = Asset::find($id);
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //       $upload = Upload::find($input['id_upload0']);
      //       $upload->asset_id = $set_data_upload['asset_id'];
      //       $upload->user_id = $set_data_upload['user_id'];
      //       $upload->upload_image = $set_data_upload['upload_image'];
      //       $upload->updated_by = $set_data_upload['user_id'];
      //       $upload->updated_at = date('Y-m-d H:i:s');
      //       $upload->save();
      //     }
      //   }
      // }

      // if((isset($input['file1'])) && ($input['file1'] != null)){
      //   if($input['id_upload1'] == null){
      //     //make new input upload
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1,
      //       'created_by' => Session::get('id'),
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file1');
      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;
          
      //     $update_asset = Asset::find($id);   
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //         $data = Upload::create($set_data_upload);
      //     }
      //   }else{
      //     //update data upload or image
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file1');

      //     $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload1'])->first());
      //     $stat = Storage::disk('public')->delete($get_image->upload_image);

      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;

      //     $update_asset = Asset::find($id);
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //       $upload = Upload::find($input['id_upload1']);
      //       $upload->asset_id = $set_data_upload['asset_id'];
      //       $upload->user_id = $set_data_upload['user_id'];
      //       $upload->upload_image = $set_data_upload['upload_image'];
      //       $upload->updated_by = $set_data_upload['user_id'];
      //       $upload->updated_at = date('Y-m-d H:i:s');
      //       $upload->save();
      //     }
      //   }
      // }

      // if((isset($input['file2'])) && ($input['file2'] != null)){
      //   if($input['id_upload2'] == null){
      //     //make new input upload
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1,
      //       'created_by' => Session::get('id'),
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file2');
      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;
          
      //     $update_asset = Asset::find($id);   
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //         $data = Upload::create($set_data_upload);
      //     }
      //   }else{
      //     //update data upload or image
      //     $set_data_upload = [
      //       'asset_id' => $id,
      //       'user_id' => Session::get('id'),
      //       'upload_status' => 1
      //     ];
      //     $uploadFolder = 'asset';
      //     $image = $request->file('file2');

      //     $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload2'])->first());
      //     $stat = Storage::disk('public')->delete($get_image->upload_image);

      //     $image_uploaded_path = $image->store($uploadFolder, 'public');
      //     $set_data_upload["upload_image"] = $image_uploaded_path;

      //     $update_asset = Asset::find($id);
      //     $update_asset->asset_status = "u";
      //     $update_asset->save();
  
      //     if($update_asset){
      //       $upload = Upload::find($input['id_upload2']);
      //       $upload->asset_id = $set_data_upload['asset_id'];
      //       $upload->user_id = $set_data_upload['user_id'];
      //       $upload->upload_image = $set_data_upload['upload_image'];
      //       $upload->updated_by = $set_data_upload['user_id'];
      //       $upload->updated_at = date('Y-m-d H:i:s');
      //       $upload->save();
      //     }
      //   }
      // }

      $asset = Asset::find($id);   
      $asset->asset_number = $input['asset_number'];
      $asset->asset_serial_number = $input['asset_serial_number'];
      $asset->asset_capitalized_on = $input['asset_capitalized_on'];
      $asset->asset_manager = $input['asset_manager'];
      $asset->asset_desc = $input['asset_desc'];
      $asset->asset_quantity = $input['asset_quantity'];
      $asset->asset_po = $input['asset_po'];
      $asset->asset_status = $input['asset_status'];
      $asset->departement_id = $input['departement_id'];
      $asset->category_id = $input['category_id'];
      $asset->count_id = $input['count_id'];
      $asset->asset_condition = $input['asset_condition'];
      $asset->location = $input['location'];
      
      $asset->save();

      if($asset){
        $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Edit Asset successfully');
      }
    echo json_encode($array_alert);
  }


  public function update_photo(Request $request) {
      
    $input = $request->all();

    $id = $input['id_asset'];
    $input['updated_at'] = date('Y-m-d H:i:s');
    $input['updated_by'] = Session::get('id');

    $validator = Validator::make($input, [
      "file0"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
      "file1"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
      "file2"=> "image|mimes:jpg,png,jpeg,gif,svg|max:5048",
    ]);

    if($validator->fails()){
      return back()->with('warning', implode(" ", $validator->messages()->all()));
    }

    if((isset($input['file0'])) && ($input['file0'] != null)){
      if($input['id_upload0'] == null){
        //make new input upload
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1,
          'created_by' => Session::get('id'),
          'created_at' => date('Y-m-d H:i:s'),
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file0');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;
        
        $update_asset = Asset::find($id);   
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
            $data = Upload::create($set_data_upload);
        }
      }else{
        //update data upload or image
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file0');

        $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload0'])->first());
        if(!str_contains($get_image->upload_image, "default_photo/")){
          $deleted_photo_storage = Storage::disk('public')->delete($get_image->upload_image);
        }
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;

        $update_asset = Asset::find($id);
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
          $upload = Upload::find($input['id_upload0']);
          $upload->asset_id = $set_data_upload['asset_id'];
          $upload->user_id = $set_data_upload['user_id'];
          $upload->upload_image = $set_data_upload['upload_image'];
          $upload->updated_by = $set_data_upload['user_id'];
          $upload->updated_at = date('Y-m-d H:i:s');
          $upload->save();
        }
      }
    }

    if((isset($input['file1'])) && ($input['file1'] != null)){
      if($input['id_upload1'] == null){
        //make new input upload
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1,
          'created_by' => Session::get('id'),
          'created_at' => date('Y-m-d H:i:s'),
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file1');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;
        
        $update_asset = Asset::find($id);   
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
            $data = Upload::create($set_data_upload);
        }
      }else{
        //update data upload or image
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file1');

        $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload1'])->first());
        if(!str_contains($get_image->upload_image, "default_photo/")){
          $deleted_photo_storage = Storage::disk('public')->delete($get_image->upload_image);
        }
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;

        $update_asset = Asset::find($id);
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
          $upload = Upload::find($input['id_upload1']);
          $upload->asset_id = $set_data_upload['asset_id'];
          $upload->user_id = $set_data_upload['user_id'];
          $upload->upload_image = $set_data_upload['upload_image'];
          $upload->updated_by = $set_data_upload['user_id'];
          $upload->updated_at = date('Y-m-d H:i:s');
          $upload->save();
        }
      }
    }

    if((isset($input['file2'])) && ($input['file2'] != null)){
      if($input['id_upload2'] == null){
        //make new input upload
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1,
          'created_by' => Session::get('id'),
          'created_at' => date('Y-m-d H:i:s'),
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file2');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;
        
        $update_asset = Asset::find($id);   
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
            $data = Upload::create($set_data_upload);
        }
      }else{
        //update data upload or image
        $set_data_upload = [
          'asset_id' => $id,
          'user_id' => Session::get('id'),
          'upload_status' => 1
        ];
        $uploadFolder = 'asset';
        $image = $request->file('file2');

        $get_image = json_decode(Upload::select('upload_image')->where('id',$input['id_upload2'])->first());
        if(!str_contains($get_image->upload_image, "default_photo/")){
          $deleted_photo_storage = Storage::disk('public')->delete($get_image->upload_image);
        }
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $set_data_upload["upload_image"] = $image_uploaded_path;

        $update_asset = Asset::find($id);
        $update_asset->asset_status = "u";
        $update_asset->save();

        if($update_asset){
          $upload = Upload::find($input['id_upload2']);
          $upload->asset_id = $set_data_upload['asset_id'];
          $upload->user_id = $set_data_upload['user_id'];
          $upload->upload_image = $set_data_upload['upload_image'];
          $upload->updated_by = $set_data_upload['user_id'];
          $upload->updated_at = date('Y-m-d H:i:s');
          $upload->save();
        }
      }
    }
    $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Update Photo successfully');
    
    echo json_encode($array_alert);
}

  public function destroy(Request $request){
    $input = $request->all();
    $id = $input['id_asset'];

    $get_mutation = json_decode(MutationsDet::select('*')->where('asset_id',$id)->get());
    if($get_mutation){
      foreach ($get_mutation as $data_mutation) {
        $mutation = MutationsDet::find($data_mutation->id);
        $mutation->delete();
      }
    }
    
    $get_upload = json_decode(Upload::select('*')->where('asset_id',$id)->get());
    foreach ($get_upload as $data_upload) {
      $upload = Upload::find($data_upload->id);
      $upload->delete();
      if($upload){
        if(!str_contains($data_upload->upload_image, "default_photo/")){
          $filesInFolder = Storage::disk('public')->exists($data_upload->upload_image); 
          if($filesInFolder == true){
            $stat = Storage::disk('public')->delete($data_upload->upload_image);
          }
        }
      }else{
        // return back()->with('warning', 'Deleted asset photo fail');
        $array_alert = array('status' => 'fail', 'error' => '', 'message' => 'Deleted Asset photo fail');
      }
      
    }

    $asset = Asset::destroy($id);
    if($asset){
      $array_alert = array('status' => 'success', 'error' => '', 'message' => 'Deleted Asset successfully');
    }else{
      $array_alert = array('status' => 'fail', 'error' => '', 'message' => 'Deleted Asset fail');
    }
    echo json_encode($array_alert);
  }

  public function import(Request $request)
  {
    $validator = Validator::make(
      [
          'file'      => $request->file,
          'extension' => strtolower($request->file->getClientOriginalExtension()),
      ],
      [
          'file'          => 'required',
          'extension'      => 'required|in:xlsx,xls',
      ]
    
    );
    if($validator->fails()){
      return back()->with('warning', 'file extension must xlsx or xls');
    }else{
      Excel::import(new ImportAsset(), $request->file('file'));
      return back()->with('success', "Asset has been imported");
    }
  }

  public function download(){
      $path = storage_path('app/public/sample_import_asset_excel/test.xlsx');
      return response()->download($path);
  }

  public function download_img(Request $request){
    $url = $request->all()['url'];
    $path = storage_path('app/public/'.$url);
    return response()->download($path);
  }

  public function deleted_photo_asset(Request $request){
    $input = $request->all();
    $get_image = json_decode(Upload::select('*')->where('id',$input['id'])->first());
    $upload = Upload::find($get_image->id);
    $upload->delete();
    if($upload){
      if(!str_contains($get_image->upload_image, "default_photo/")){
        $stat = Storage::disk('public')->delete($get_image->upload_image);
        $get_image->message = 'success';
      }else{
        $get_image->message = 'cannot deleted iamge default';
        return back()->with('warning', 'Deleted asset photo fail');
      }
    }else{
      $get_image->message = 'fail';
      return back()->with('warning', 'Deleted asset photo fail');
    }
    return $get_image;
  }

  public function get_name_file(){
    $datas = array();
    $filesInFolder = Storage::disk('public')->allFiles('asset');  
    $replace_asset = str_replace("asset/",'', $filesInFolder);
    // $replace_extension = str_replace(".jpeg",'', $replace_asset);
    foreach($replace_asset as $data){
      $splitExtension = explode(".",$data);
      $getNameFile = reset($splitExtension);
      $getSerialPhoto = explode(" ",$getNameFile);
      $serialNumberPhoto = (reset($getSerialPhoto));
      $sql_check_upload_exsist = json_decode(Upload::select('id')->where('upload_image', "asset/".$data)->first());
      if($sql_check_upload_exsist){
        $exist[] = $data;
      }else{
        $sql_check_asset_number = json_decode(Asset::select('id')->where('asset_number', $serialNumberPhoto)->first());
        // dd($sql_check_upload_exsist);
        if($sql_check_asset_number){
          $input = [
            'asset_id' => $sql_check_asset_number->id,
            'user_id' => 1,
            'upload_status' => 1,
            'upload_image' => "asset/".$data,
          ];
          $asset = Upload::create($input);
        }else{
          $kosong[] = $data;
        }
      }
    }
    // $getAllAssetNumber = json_decode(Asset::select('asset_number')->get());
    // foreach($getAllAssetNumber as $asset_number){
    //   $allAssetNumber[] = $asset_number->asset_number;
    // }
    // dd($sql_check_asset_number);
    $return = (['file yang tidak tersedia pada table asset' => $kosong]);
    echo json_encode($return);
    // var_dump($exist);
  }

  public function noImage(){
    $getAsset = json_decode(Asset::select('id')->whereNotIn('assets.id', Upload::select('asset_id'))->get());
    foreach($getAsset as $data){
      $asset_id = $data->id;
      $input = [
        'asset_id' => $asset_id,
        'user_id' => 1,
        'upload_status' => 1,
        'upload_image' => "default_photo/default.jpg",
      ];
      $asset = Upload::create($input);
    }
    if(isset($asset)){
      echo $asset;
    }else{
      echo "false";
    }
  }

  public function tinjauan_asset($departement_id , $date_from, $date_to){
    if(($date_from != "-") && ($date_to == "-")){
        $date_to = date("Y-m-d");
    }
    if(($date_from == "-") && ($date_to != "-")){
        $date_from = date("1990-01-01");
    }
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
                    ->get(['assets.*','departments.department','categories.id as category_id','categories.category','counts.count']);

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
    ->with('asset_data', $asset);

}

    /**
     * Show the application contact.
     *
     * @return \Illuminate\View\View
     */
}