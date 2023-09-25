@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Total Network (Team)</h4>
        <p class="sub-title">Level wise list of the investors in your network</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (Levels & Network)</small></h5>
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
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $knt = 0; // Initialize $knt variable here
                                        @endphp
                                        @foreach ($rinfo as $user)
                                        @php
                                        $knt++;
                                        $status = DB::table('tbl_deposit')->where('userid', $user->id)->where('bActive', 'Y')->count() ? 'Paid' : 'Unpaid';
                                        $lvl = isset($levels[$user->id]) ? $levels[$user->id] : ''; // Set $lvl property based on the $levels array
                                        $userinv = DB::table('tbl_deposit')->where('userid', $user->id)->where('bActive', 'Y')->select(DB::raw('SUM(deposit) AS totdeposit'))->first();
                                        $totinvs = $userinv ? $userinv->totdeposit : 0;
                                        @endphp
                                        <tr>
                                            <th scope="row" class="text-center">{{ $knt + (isset($_GET['offset']) ? $_GET['offset'] : 0) }}</th>
                                            <td>
                                                <b><a href="?pg=profile&amp;id={{ base64_encode($user->id) }}&amp;token={{ md5($user->id) }}">{{ $user->loginid }}</a></b><br>
                                            </td>
                                            <td>
                                                <b>{{ $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname }}</b><br>
                                            </td>
                                            <td>
                                                <b>{{ date("d M Y h:i:s A", $user->createdate) }}</b><br>
                                            </td>
                                            <td>{{ ($lvl == 1 ? 'Direct' : 'Level ' . $lvl) }}</td>
                                            <td class="text-right">${{ $totinvs }}</td>
                                            <td class="text-center">{{ $status }}</td>
                                        </tr>
                                        @endforeach

                                        @if (count($rinfo) === 0)
                                        <tr>
                                            <td colspan="7" class="text-center">-no records-</td>
                                        </tr>
                                        @endif
                                    </tbody>

                                </table>
                                <?php
                                    $str = "pg=network";
                                  
                                ?>
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