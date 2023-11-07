<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@include('user.include.header')
@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Summary for Investment: ${{ $dinfo[0]->deposit }}, Dated: {{ date("d M, Y", $dinfo[0]->createdate) }}</h4>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (All Statement)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Credit Date</th>
                                            <th class="text-center">ROI</th>
                                            <th class="text-right">Credit</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        
                                            $tot = count($list);
                                            $knt = $tot + 1;
                                            $tamt = $dinfo[0]['p_return'];
                                            $tot -= 1;
                                            $tam = 0;
                                        @endphp

                                      
                                        @foreach ($list as $key => $item)
                                            @php
                                           
                                                $tbal = $tam - $tamt;
                                                if ($key == 0) {
                                                    $tbal = $tamt * 1;
                                                }
                                                if ($key == $tot) {
                                                    //$tbal = $tamt;
                                                }
                                                 $tam += $item['amount'];
                                                // print_r( $tam );
                                            @endphp
                                            <tr>
                                                <td scope="row" class="text-center">{{ $knt -= 1 }}</td>
                                                <td>{{ date("l d, M Y", $item['createdate']) }}</td>
                                                <td class="text-center">{{ $item['roi'] }}%</td>
                                                <td class="text-right">${{ $item['amount'] }}</td>
                                                <td class="text-right">${{ $tbal }}</td>
                                            </tr>
                                        @endforeach

                                       
                                    </tbody>
                                </table>
                                {{-- Add your paging_2 function here --}}
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














