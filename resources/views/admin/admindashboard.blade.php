@extends('admin.layout') {{-- Assuming you have a layout template --}}

@section('content')
<!-- Your existing content -->
<section class="content">
    <div class="row ">
        <div class="col-12 d-flex mb-3">
            <div class="col-4 bg-success text-white me-2 rounded">
                <div class="inner p-4">
                    <h3>{{ $uinfo}} </h3>
                    <p>Total Clients</p>
                </div>
                <div class="icon"><i class="ion ion-person-add"></i></div>
            </div>


            <div class="col-4 bg-secondary text-white me-2 rounded">
                <div class="inner p-4">
                    <h3> ${{ $dinfo}}</h3>

                    <p>Total Investment</p>
                </div>
                <div class="icon"><i class="ion ion-stats-bars"></i></div>
            </div>


            <div class="col-4 bg-warning text-white me-5 rounded">
                <div class="inner p-4">
                    <h3>${{ $sinfo}}</h3>
                    <p>Total Withdrawal</p>
                </div>
                <div class="icon"><i class="ion ion-pie-graph"></i></div>
            </div>
        </div>
    </div>
    <div class="row ms-1">

        <div class="card" style=" max-width: 100%;">
            <div class="card-body">
                <h4 class="text-center">Activity Log</h4>
                <div class="table-responsive" style=" max-width: 100%;">
                    <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
                        <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                            <tr>
                                <th width="15%">Date Time</th>
                                <th width="13%">IP Address</th>
                                <th width="16%">Admin User</th>
                                <th width="13%">Section</th>
                                <th width="43%">Action Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop through qinfo and display data --}}
                            @forelse($qinfo as $log)
                            <tr>
                                <td>{{ date("d/m/Y g:i A", $log->createdate) }}</td>
                                <td>{{ $log->ipaddress }}</td>
                                <td>{{ $log->userid}}</td>
                                <td>{!! $log->section!!}</td>
                                <td>
                                    {{ substr(strip_tags($log->title . ' : ' . $log->action), 0, 50) }}
                                    <div style="display:none">
                                        <div id="view{{ $log->id }}">
                                            <a href="{{ $log->href }}" target="_blank">
                                                <strong>{{ $log->title }}</strong>
                                            </a><br>
                                            {{ $log->action }}
                                        </div>
                                    </div>


                                    {{-- <a href="#view{{ $log->id }}" class="pull-right btn btn-sm btn-info inlineb {{ ($log->action == 'New Record' || $log->action == 'Record Deleted') ? 'hidden' : '' }}">Log</a> --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">-no records-</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- Pagination --}}
                    {{-- Use Laravel pagination methods --}}
                </div>

            </div>
        </div>
    </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Get the chart labels and counts from the PHP variables
    var chartLabels = @json(['Total Investment', 'Total Withdrawal']);
    var chartCounts = @json([$dinfo, $sinfo]);

    // Create a donut chart using Chart.js
    var ctx = document.getElementById('donutChart').getContext('2d');
    var donutChart = new Chart(ctx, {
        type: 'doughnut'
        , data: {
            labels: chartLabels
            , datasets: [{
                data: chartCounts
                , backgroundColor: [

                    'rgb(0, 204, 204)'
                    , 'rgb(0, 102, 102)',
                    // Add more colors if needed
                ]
            , }]
        , }
        , options: {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var count = dataset.data[tooltipItem.index];
                        return dataset.label + ': ' + count;
                    }
                , }
            , }
        , }
    , });

</script>
@endsection





