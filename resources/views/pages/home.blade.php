@extends('layouts.app', [
    'class' => '',
    'elementActive' => $title,
])


@section('content')
    <div class="card">
        <div class="card-body row" style="padding-bottom:0px">
            <form action="{{ route('dashboard') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label for="" class="mt-2">Select Year : &nbsp;</label>
                    <select id="set_year" name="set_year" class=" form-control" onchange="filterChange()">
                        <option value="">- This Year -</option>
                        <option value="all" <?php if ($set_year == 'all') {
                            echo 'selected';
                        } ?>>- All -</option>
                        @foreach ($year as $data_year)
                            <option value="{{ $data_year->year }}" <?php if ($set_year == $data_year->year) {
                                echo 'selected';
                            } ?>>{{ $data_year->year }}</option>
                        @endforeach
                    </select>
                    <button hidden type="submit" id="filter" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body row">
            <div class="col-lg-6 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $total_asset }}</h3>
                        <p>Total Asset <?= $set_year != 'all' ? 'Tahun ' . $set_year : 'Tahun' ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>
            <div class="col-lg-6 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp. {{ $asset_price }}</h3>
                        <p>Total Harga Asset <?= $set_year != 'all' ? 'Tahun ' . $set_year : 'Tahun' ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">&nbsp;Total Harga Asset By Departement
                            <?= $set_year != 'all' ? 'Tahun ' . $set_year : 'Tahun' ?></h3>
                        <div class="box-tools">
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Departement</th>
                                    <th>Total Asset</th>
                                    <th>Total Harga</th>
                                </tr>
                                <?php $i = 1; ?>
                                @foreach ($get_total_haraga_by_dept as $data)
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $data['dept'] ?></td>
                                        <td><?= $data['total_asset'] ?></td>
                                        <td><?= $data['total'] ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body row">
            <div class="col-lg-6 col-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">&nbsp;Total Asset By Departement
                            <?= $set_year != 'all' ? '' . $set_year : 'Tahun' ?> (No Label)
                        </h3>
                        <div class="box-tools">
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Departement</th>
                                    <th>Total</th>
                                </tr>
                                <?php $i = 1; ?>
                                @foreach ($count_asset_noupload as $data)
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $data['dept'] ?></td>
                                        <td><?= $data['total'] ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">&nbsp;Total Asset By Departement
                            <?= $set_year != 'all' ? '' . $set_year : 'Tahun' ?> (Label)
                        </h3>
                        <div class="box-tools">
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Departement</th>
                                    <th>Total</th>
                                </tr>
                                <?php $i = 1; ?>
                                @foreach ($count_asset_upload as $data)
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $data['dept'] ?></td>
                                        <td><?= $data['total'] ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <canvas id="stackedBarChart"></canvas>
    </div>

    <div class="card text-center">
        <div>
            <div id="pemasukan" class=""></div>
        </div>
        <br>
        <div>
            <div id="label" class=""></div>
        </div>
        <br>
        <div>
            <div id="category" class=""></div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
</script>

<script>
    $(document).ready(function() {
        Chart.register(ChartDataLabels);

        var dataCharts = <?php echo $arr_chart; ?>;
        var year = <?= $set_year != 'all' ? '' . $set_year : 'Tahun' ?>;
        const ctx = document.getElementById('stackedBarChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataCharts.map(row => row.dept),
                datasets: [{
                    label: 'Persentase Inventarisasi Per Departemen ' + year,
                    data: dataCharts.map(row => row.percentage),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(219, 52, 77, .2)',
                    borderWidth: 1
                }]
            },
            maintainAspectRatio: false,
            responsive: true,
            options: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: Math.round,
                    font: {
                        weight: 'bold',
                        size: 16
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                tooltip: {
                    // Tooltip will only receive click events
                    events: ['click']
                },
            }
        });
    });
</script>
<script type="text/javascript">
    function filterChange() {
        var button = document.getElementById('filter');
        button.form.submit();
    }

    var DataPemasukan = <?php echo $data_pemasukan; ?>;
    var LabelAsset = <?php echo $label_asset; ?>;
    var asset_by_category = <?php echo $asset_by_category; ?>;

    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(pemasukan);
    google.charts.setOnLoadCallback(label);
    google.charts.setOnLoadCallback(asset_category);

    function pemasukan() {
        var data = google.visualization.arrayToDataTable(DataPemasukan);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1, {
            calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation"
        }, ]);
        var options = {
            title: "Asset Masukan/<?= $set_year != 'all' ? 'Bulan Tahun ' . $set_year : 'Tahun' ?>",
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            colors: ['#d14e49'],
        };
        var chart = new google.visualization.LineChart(document.getElementById('pemasukan'));
        chart.draw(view, options);
    }

    function label() {
        var data = google.visualization.arrayToDataTable(LabelAsset);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1, {
            calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation"
        }, ]);
        var options = {
            title: "Label Asset/<?= $set_year != 'all' ? 'Bulan Tahun ' . $set_year : 'Tahun' ?>",
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            colors: ['#d14e49'],
        };
        var chart = new google.visualization.LineChart(document.getElementById('label'));
        chart.draw(view, options);
    }

    function asset_category() {
        var data = google.visualization.arrayToDataTable(asset_by_category);
        console.log(asset_by_category);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            }
        ]);
        var options = {
            title: "Asset By Category <?= $set_year != 'all' ? 'Tahun ' . $set_year : $set_year ?>",
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            hAxis: {
                format: ''
            },
            colors: ['#d14e49'],
            height: 300,
        };
        var chart = new google.visualization.BarChart(document.getElementById('category'));
        chart.draw(view, options);
    }
</script>
