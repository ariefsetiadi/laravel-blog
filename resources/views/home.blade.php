@extends('Layouts.cms')

@push('css')
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $visitors }}</h3>

              <p>Total Pengunjung</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>{{ $articles }}</h3>

              <p>Total Artikel</p>
            </div>
            <div class="icon">
              <i class="fas fa-newspaper"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ $pageView }}</h3>

              <p>Total Artikel yang Dibaca</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-lg-6 col-12">
          <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">Browser Pengunjung</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>

        <div class="col-lg-6 col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Sistem Operasi Pengunjung</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <!-- Bar chart -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Bar Chart</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@push('js')
  <!-- ChartJS -->
  <script src="{{ asset('cms/plugins/chart.js/Chart.min.js') }}"></script>

  <script type="text/javascript">
    $(function () {
      // Browser Chart
      const browserNames = @json($browserName);
      const browserTotals = @json($browserTotal);

      var donutData = {
        labels: browserNames,
        datasets: [
          {
            data: browserTotals,
            backgroundColor : [
              '#f56954',
              '#00a65a',
              '#f39c12',
              '#00c0ef',
              '#3c8dbc',
              '#d2d6de',
            ],
          }
        ]
      }

      var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
      var pieData        = donutData;
      var pieOptions     = {
        maintainAspectRatio : false,
        responsive : true,
      }

      new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
      });

      // Platform Chart
      const platformNames = @json($platformName);
      const platformTotals = @json($platformTotal);

      var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
      var donutData        = {
        labels: platformNames,
        datasets: [
          {
            data: platformTotals,
            backgroundColor : [
              '#f56954',
              '#00a65a',
              '#f39c12',
              '#00c0ef',
              '#3c8dbc', 
              '#d2d6de'
            ],
          }
        ]
      }
      var donutOptions     = {
        maintainAspectRatio : false,
        responsive : true,
      }

      new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
      });

      //  Visitor Chart
      const labels = @json($visitorLabels);
      const areaChartData = {
        labels  : labels,
        datasets: [
          {
            label               : 'Jumlah Pengunjung',
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : @json($visitorData),
          },
        ]
      }

      var barChartCanvas = $('#barChart').get(0).getContext('2d')
      var barChartData = $.extend(true, {}, areaChartData)
      var temp0 = areaChartData.datasets[0]
      barChartData.datasets[0] = temp0

      var barChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision:0
            }
          }
        }
      }

      new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      });
    });
  </script>
@endpush