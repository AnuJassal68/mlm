
@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Check your Pending Deposits</h4>
        <p class="sub-title">List of transactions which you've initiated but not paid yet.</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (Pending Deposits)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                               <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Date</th>
                                            <th>Order No.</th>
                                            <th>Generated btc Address</th>
                                            <th class="text-right">initiated Amount (USD)</th>
                                            <th class="text-right">initiated Amount (BTC)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $knt = 0;
                                            $currenttotal = count($rinfo);
                                            $offset = request()->input('offset', 0); // Get the 'offset' query parameter or set default value 0
                                        @endphp

                                        @foreach ($rinfo as $deposit)
                                            @php
                                                $knt++;
                                                $cnt = ($currenttotal - $knt) - $offset + 1;
                                                $usdCost = $deposit->deposit;
                                                $convertedCost = $usdCost / $btcValue;
                                            @endphp
                                            <tr>
                                                <th scope="row" class="text-center">{{ $cnt }}</th>
                                                <td>
                                                    <b>{{ date("d M Y", $deposit->createdate) }}</b><br>
                                                </td>
                                                <td><a href="?pg=btcvalue&token={{ base64_encode($deposit->id) }}">{{ $deposit->label }}</a></td>
                                                <td>{{ $deposit->address }}</td>
                                                <td class="text-right">${{ $deposit->deposit }}</td>
                                                <td class="text-right">{{ round($convertedCost, 5) }}</td>
                                            </tr>
                                        @endforeach

                                        @if (count($rinfo) === 0)
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <b>-no records-</b><br>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @php
                                    $str="pg=my-deposits";
                                    // Adjust the value based on your requirements.
                                
                                @endphp
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="users/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="users/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#transactionTable').DataTable();
    });
</script>