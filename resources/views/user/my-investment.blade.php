<!-- investment_summary.blade.php -->
@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Your total Invested Amount Summary</h4>
        <p class="sub-title">List of Transactions for the investments you done with us</p>
    </div>
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (Invested Amount)</small></h5>
                              
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsivea">
                                  <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Investment Date</th>
                                            <th class="text-center">Order No.</th>
                                            <th class="text-center">Deposited Amount</th>
                                            <th class="text-center">Investment Amount</th>
                                            <th>Generated btc Address</th>
                                            <th class="text-center">Number of Days</th>
                                            <th class="text-center">Generated Interest</th>
                                            <th class="text-center">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($rinfo as $index => $info)
                                        <tr>
                                            <th scope="row" class="text-center">{{ $index + 1 }}</th>
                                            <td class="text-center">
                                                <b>{{ date("d M, Y h:i:s A", $info->createdate) }}</b><br>
                                            </td>
                                            <td class="text-center">{{ $info->label }}</td>
                                            <td class="text-center">${{ $info->p_deposit }}</td>
                                            <td class="text-center">${{ $info->deposit }}</td>
                                            <td>{{ $info->address }}</td>
                                            <td class="text-center">{{ $info->days }} Days</td>
                                            <td class="text-center">${{ $info->p_return }}</td>
                                            <td class="text-center">{{ $info->deposit_type === 'Re-Investment' ? 'Reinvest' : 'Invest' }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($rinfo->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center">No investments found.</td>
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