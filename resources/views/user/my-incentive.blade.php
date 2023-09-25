<!-- level_incentives.blade.php -->
@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section" >
    <div class="section-title text-center">
        <h4 class="title underline">Your Level Incentive</h4>
        <p class="sub-title">List of amount you're getting on the Invested amount of your Referral</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (User list)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                  <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Investment Date & Time</th>
                                            <th>Level</th>
                                            <th class="text-right">Investment Amount</th>
                                            <th class="text-right">Level Incentive</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                        $knt = 0;
                                        // Get the offset from the $_GET array or set it to 0 if not present
                                        $offset = $_GET['offset'] ?? 0;
                                        @endphp
                                        @foreach ($rinfo as $index => $info)
                                        @php
                                        $amountDeposit = json_decode($info->incomelog);
                                        $knt++;
                                        @endphp
                                        <tr>

                                            <th scope="row" class="text-center">{{ $index + 1 + $offset }}</th>
                                            <td>
                                                <b>{{ $info->loginid }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ $info->firstname.' '.$info->middlename.' '.$info->lastname }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ date("d, M Y h:i:s A", $info->createdate) }}</b><br>
                                            </td>
                                            <td>{{ $info->incometype }}</td>
                                            <td class="text-right">${{ $amountDeposit->deposit }}</td>
                                            <td class="text-right">${{ $info->income }}</td>
                                        </tr>
                                        @endforeach
                                        @if ($rinfo->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <b>-no records-</b><br>
                                            </td>
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
