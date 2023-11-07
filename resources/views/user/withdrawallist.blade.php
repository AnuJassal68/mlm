@include('user.include.header')
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Check your Withdrawal Payments</h4>
        <p class="sub-title">List of transactions which you've request Withdrawal.</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (Withdrawal Requests)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Account Address</th>
                                            <th>Withdrawal</th>
                                            <th>TDS</th>
                                            <th>Admincharges</th>
                                            <th class="text-right">initiated Amount (USD)</th>
                                            <th class="text-right">initiated Amount (BTC)</th>
                                            <th>Description</th>
                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($results as $results)
                                        @php
                                        if (isset($results->tds) && isset($results->admincharges) && isset($results->processamt)) {
                                            $Withdrawal = $results->processamt;
                                        $deducted =  $results->tds + $results->admincharges ;
                                        $total = $Withdrawal- $deducted ;
                                       // echo $deducted;
                                        } else {
                                        echo "One or both of the values are not set.";
                                        }
                                        @endphp

                                        <tr>
                                            <th scope="row" class="text-center">{{ date("d M Y", $results->createdate) }}</th>
                                            <td>
                                                {{$results->bankdetails}}
                                            </td>
                                            <td> {{$results->processamt}}</td>
                                            <td> {{$results->tds}}</td>
                                            <td> {{$results->admincharges}}</td>
                                            <td class="text-right">${{  $total }}</td>
                                            <td class="text-right">{{ $results->trandetails }}</td>
                                            <td class="text-right">{{ $results->description }}</td>
                                            <td class="text-right">
                                                @if($results->status == 0)
                                                <a href="#" class="text-danger">Pending</a>
                                                @elseif($results->status == 1)
                                                <a href="#" class="text-success">Paid</a>
                                                @elseif($results->status == 2)
                                                <a href="#" class="text-danger">Reject</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
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
