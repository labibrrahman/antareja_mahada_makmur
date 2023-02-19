@extends('layouts.app', [
    'class' => '',
    'elementActive' => $title
])

@section('content')
<div class="card">
        <div class="card-body row">
          <div class="col-lg-6 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$count_asset}}</h3>
                <p>Total Asset Tahun 2022</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>Rp. {{$asset_price}}</h3>
                <p>Total Harga Asset Tahun 2022</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <div>
          <div id="pemasukan" class="page_speed_392943554"></div>
        </div>
        <br>
        <div>
          <div id="label" class="page_speed_392943554"></div>
        </div>
        <br>
        <div>
          <div id="category" class="page_speed_392943554"></div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  var DataPemasukan = <?php echo $data_pemasukan; ?>;
  var LabelAsset = <?php echo $label_asset; ?>;
  var asset_by_category = <?php echo $asset_by_category; ?>;
  
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(pemasukan);
  google.charts.setOnLoadCallback(label);
  google.charts.setOnLoadCallback(asset_category);
  
  function pemasukan() {
    var data = google.visualization.arrayToDataTable(DataPemasukan);
    var options = {
      title: 'Asset Masukan/Bulan Tahun 2022',
      curveType: 'function',
      legend: { position: 'bottom' },
      colors: ['#d14e49'],
    };
    var chart = new google.visualization.LineChart(document.getElementById('pemasukan'));
    chart.draw(data, options);
  }

  function label() {
    var data = google.visualization.arrayToDataTable(LabelAsset);
    var options = {
      title: 'Label Asset/Bulan Tahun 2022',
      curveType: 'function',
      legend: { position: 'bottom' },
      colors: ['#d14e49'],
    };
    var chart = new google.visualization.LineChart(document.getElementById('label'));
    chart.draw(data, options);
  }

  function asset_category() {
    var data = google.visualization.arrayToDataTable(asset_by_category);
    var options = {
      title: 'Asset By Category Tahun 2022',
      curveType: 'function',
      legend: { position: 'bottom' },
      hAxis: {format:''},
      colors: ['#d14e49'],
    };
    var chart = new google.visualization.BarChart(document.getElementById('category'));
    chart.draw(data, options);
  }
</script>
