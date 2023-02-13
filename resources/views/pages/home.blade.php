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
        <div>
          <div id="linechart" class="page_speed_392943554"></div>
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
  var visitor = <?php echo $visitor; ?>;
  console.log(visitor);
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable(visitor);
    var options = {
      title: 'Grafik Pemasukan Asset Selama 4 Bulan',
      curveType: 'function',
      legend: { position: 'bottom' }
    };
    var chart = new google.visualization.ColumnChart(document.getElementById('linechart'));
    chart.draw(data, options);
  }
</script>
