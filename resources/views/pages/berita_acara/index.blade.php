
@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'contact'
])

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title">&nbsp;<b>Tinjauan Asset</b></h5>
  </div>
  <div class="card-body row" style="padding-bottom:0px">
    <div class="card-body" >
      <div class="form-group row" id = "select_filter_dept">
          <label class="mt-2">Show By : &nbsp;&nbsp;</label>
          <select class="status form-control" name="filter_dept" id="filter_dept" style="width:300px;" onchange="ba_bydept()">
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
  <div class="card-header">
    <h5 class="card-title">&nbsp;<b>Disposal Asset</b></h5>
  </div>
  <div class="card-body row" style="padding-bottom:0px">
    <div class="card-body" >
        <a href="#" id="disposalAsset" class="btn btn-sm btn-warning btnPrint"><i class="fa fa-print"></i> Print</a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="card-title">&nbsp;<b>Mutasi Asset</b></h5>
  </div>
  <div class="card-body row" style="padding-bottom:0px">
    <div class="card-body" >
        <a href="#" id="mutasiAsset" class="btn btn-sm btn-warning btnPrint"><i class="fa fa-print"></i> Print</a>
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
    $("#disposalAsset").printPage();
    $("#mutasiAsset").printPage();
  });
  function ba_bydept(){
    var filter_dept = document.getElementById('filter_dept').value;
    document.getElementById("tinjauanAsset").href='/berita_acara/tinjauan_asset/'+filter_dept;
  };

  window.onload = function(){  

    var filter_dept = document.getElementById('filter_dept').value;
    document.getElementById("tinjauanAsset").href='/berita_acara/tinjauan_asset/'+filter_dept;

    document.getElementById("disposalAsset").href='/berita_acara/disposal_asset/';

    document.getElementById("mutasiAsset").href='/berita_acara/mutasi_asset/';
    
  };

</script>