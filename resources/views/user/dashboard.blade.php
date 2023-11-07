@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-primary my-primary" href="{{route('deposit')}}">
                    <div class="stat-content">
                        <span class="bg-icon"><i class="fa fa-bitcoin fa-lg"></i></span><br>
                        <span class="name" style="font-size:20px;"><strong>Add Bitcoins</strong></span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> 2.5% growth in 24 hours</span>
                </a>

            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-danger my-secondary" href="{{url('re-investment')}}">
                    <div class="stat-content">
                        <span class="bg-icon"><i class="fa fa-usd fa-lg"></i></span><br>
                        <span class="name" style="font-size:20px;"><strong>Re-Invest Funds</strong></span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-down color-success"></i> 2.5% growth in 24 hours</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-warning my-red" href="#">
                    <div class="stat-content">
                        <span class="bg-icon"><i class="fa fa-bitcoin fa-lg"></i></span><br>
                        <span class="name" style="font-size:20px;"><strong>Request Withdrawal</strong></span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i>Will be credited immediately</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-success my-green" href="#">
                    <div class="stat-content">
                    @php
   
    $permissionsArray = session('deduct');



@endphp

@if ($permissionsArray)

    {{-- <span class="number counter">{{ 10 }}</span> --}}
    <span class="number counter">{{ number_format($ret['binc'] * 1 , 2) }}</span>
@else
    <span class="number counter">{{ number_format($ret['binc'] * 1, 2) }}</span>
@endif

                       
                        </span>
                        <span class="name">Account Balance</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-down color-danger"></i> Amount you can withdraw anytime</span>
                </a>

            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-danger" href="{{ route('my-investment') }}">
                    <div class="stat-content">
                        <span class="number counter">{{ number_format($ret['toti'] * 1, 2) }}</span>
                        <span class="name">My Investment</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> Total you invested till now</span>
                </a>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-danger my-blue" href="{{route('my-incentive')}}">
                    <div class="stat-content">
                        <span class="number counter">{{number_format($ret['dinc'] * 1, 2)}}</span>
                        <span class="name">Total Daily Incentive</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-down color-success"></i>more than 2.5% growth everyday</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-warning" href="#">
                    <div class="stat-content">
                        <span class="number counter">{{number_format($ret['inc'] * 1, 2)}}</span>
                        <span class="name">Total Credits</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> Total amount from all Income</span>
                </a>

            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-success" href="#">
                    <div class="stat-content">
                        <span class="number counter">{{number_format($ret['wid'] * 1, 2)}}</span>
                        <span class="name">Total Withdrawn</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-down color-danger"></i> Total Amount, you have got already</span>
                </a>

            </div>
        </div>
    </div>
</section>
<section class="section pt-n">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel border-primary no-border border-3-top">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Statement <small> (Latest Transactions)</small></h5>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsivea">
                            <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th class="text-right">Credit</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $knt = 0;
                                    $total = 0;
                                    $offset = request()->input('offset', 0); // Get the 'offset' query parameter or set default value 0
                                    @endphp

                                    @foreach ($trinfo as $transaction)
                                    @php
                                    $knt++;
                                    $desc = $transaction->tinfo;
                                    if ($transaction->tarea == 'spent') {
                                    $desc = 'Withdrawal';
                                    } elseif ($transaction->tarea == 'roi') {
                                    $desc = $desc . ' Order Id: '. $ords[$transaction->id] . '';
                                    } elseif ($transaction->tarea == 'reroi') {
                                    $desc = $desc . ' Order Id:' . '';
                                    } elseif ($transaction->tarea == 'income') {
                                    $desc = $desc . ' ' . '';
                                    }
                                    $cr = $transaction->credit;
                                    $dr = $transaction->debit;
                                    $total += $cr - $dr;
                                    @endphp
                                    <tr>
                                        <th scope="row" class="text-center">{{ $knt + $offset }}</th>
                                        <td>
                                            <b>{{ date("d, M Y G:i", $transaction->createdate) }}</b><br>
                                        </td>
                                        <td>{{ $desc }}</td>
                                        <td class="text-right">${{ $cr }}</td>
                                        <td class="text-right">${{ $dr }}</td>
                                        <td class="text-right">${{ round($total, 2) }}</td>
                                    </tr>
                                    @endforeach

                                    @if (count($trinfo) === 0)
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
</section>



<div id="myModal" class="modal fade" data-backdrop-color="blue">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Important News</h4>
            </div>
            <div class="modal-body">
                <h4>Dear,</h4><br>
                <p>As informed earlier, New Percentage R.O.I. Plan has been developed and is ready to start. </p>
                <p>Please check concept section for more information on Affiliate Commission and Daily Incentive, further we would like to assure our members that changes made for development and for future growth.</p>
                <p>Happy earning</p>

                <p>Technical Team, CW</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right bg-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="users/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="users/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#transactionTable').DataTable();
    });

</script>

@include('user.include.footer')
