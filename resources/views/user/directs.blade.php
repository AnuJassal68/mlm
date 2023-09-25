@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
  <div class="section">
        <div class="section-title text-center">
            <h4 class="title underline">Direct Referrals</h4>
            <p class="sub-title">List of investors referred by your account.</p>
        </div>
        <div class="section pt-n">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel border-primary no-border border-3-top">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h5>Summary <small>(Direct Referred Investors)</small></h5>
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
                                                <th class="text-right">Investment Amount</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $knt = 0;
                                            @endphp

                                            @foreach ($rinfo as $user)
                                                @php
                                                    $knt++;
                                                @endphp
                                                <tr>
                                                    <th scope="row" class="text-center">{{ $knt }}</th>
                                                    <td>
                                                        <b>{{ $user->loginid }}</b><br>
                                                    </td>
                                                    <td>
                                                        <b>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</b><br>
                                                    </td>
                                                    <td>
                                                        <b>{{ date('d M Y h:i:s A', strtotime($user->createdate)) }}</b><br>
                                                    </td>
                                                    <td class="text-right">${{ $user->total_investments }}</td>
                                                    <td class="text-center">{{ $user->status }}</td>
                                                </tr>
                                            @endforeach

                                            @if (count($rinfo) === 0)
                                                <tr>
                                                    <td colspan="6" class="text-center">No direct referrals found.</td>
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