
@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

@section('content')
<div class="card">
  <div class="card-body row" style="padding-bottom:0px">
    <div class="card-body" >
      <div class="form-group row" id = "select_filter_dept">
          <label class="mt-2">Show By : &nbsp;&nbsp;</label>
          <select class="status form-control" name="filter_dept" id="filter_dept" style="width:50%;" onchange="ba_bydept()">
              <option value="0" selected id="option_show">-- Show All --</option>
              @foreach ($departement as $data)
                <option value="<?= $data->id ?>" id="option_show" ><?= $data->department ?></option>
              @endforeach
          </select>
      </div>
      <a href="#" id="tinjauanAsset" class="btn btn-sm btn-warning"><i class="fa fa-print"></i> Print</a>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-body row" style="padding-bottom:0px">
    <div class="card-body" >
        <a href="#" id="btn" class="btn btn-sm btn-warning btnPrint"><i class="fa fa-print"></i> Print</a>
    </div>
  </div>
</div>

@endsection
  <!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Datatables JS CDN -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(function(){
    $("#tinjauanAsset").printPage();
  });
  function ba_bydept(){
    var filter_dept = document.getElementById('filter_dept').value;
    document.getElementById("tinjauanAsset").href='/berita_acara/tinjauan_asset/'+filter_dept;
    
    // $('#btn').click(function(e){e.preventDefault();}).click();
    // $(".tinjauanAsset").printPage({
    //     url: '/berita_acara/tinjauan_asset/'+filter_dept, 
    //     attr: "href",
    //     message:"Your document is being created"
    // });
  };

  window.onload = function(){  
    var filter_dept = document.getElementById('filter_dept').value;
    document.getElementById("tinjauanAsset").href='/berita_acara/tinjauan_asset/'+filter_dept;
  };

</script>