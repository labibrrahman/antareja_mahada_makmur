@extends('layouts.app', [
    'class' => '',
    'elementActive' => $title
])

@section('content')
<div class="card">
        {{-- <div class="card-header">
          <h3 class="card-title">Home</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div> --}}
        <div class="card-body">
          
        </div>
        asdasdsadsa
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
      title: 'Asset Masukan/Bulan',
      curveType: 'function',
      legend: { position: 'bottom' },
    };
    var chart = new google.visualization.LineChart(document.getElementById('pemasukan'));
    chart.draw(data, options);
  }

  function label() {
    var data = google.visualization.arrayToDataTable(LabelAsset);
    var options = {
      title: 'Label Asset/Bulan',
      curveType: 'function',
      legend: { position: 'bottom' }
    };
    var chart = new google.visualization.LineChart(document.getElementById('label'));
    chart.draw(data, options);
  }

  function asset_category() {
    var data = google.visualization.arrayToDataTable(asset_by_category);
    var options = {
      title: 'Asset By Category',
      curveType: 'function',
      legend: { position: 'bottom' },
      hAxis: {format:''}
    };
    var chart = new google.visualization.BarChart(document.getElementById('category'));
    chart.draw(data, options);
  }
</script>
