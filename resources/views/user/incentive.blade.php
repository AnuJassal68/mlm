<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@include('user.include.header')
@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Your Daily Incentive</h4>
        <p class="sub-title">List of amount you're getting every day as a rate of interest</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small> (Rate of Interest)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsivea">
                               <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="text-center">#</th>
                                            <th width="14%">Investment Date</th>
                                            <th width="5%" class="text-right">Label</th>
                                            <th width="17%" class="text-right">Investment Amount</th>
                                            <th width="20%">Generated BTC Address</th>
                                            <th width="14%" class="text-center">Number of Days</th>
                                            <th width="18%" class="text-right">Generated Interest</th>
                                            <th width="9%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $knt = 0;
                                        @endphp
                                        @foreach ($rinfo as $info)
                                        @php
                                        $knt += 1;
                                        @endphp
                                        <tr>
                                          <th scope="row" class="text-center">{{ $knt + request()->query('offset', 0) }}</th>

                                            <td>
                                                <b>{{ date("d M Y h:i:s A", $info->createdate) }}</b><br>
                                            </td>
                                            <td class="text-right">{{ $info->label }}</td>
                                            <td class="text-right">${{ $info->deposit }}</td>
                                            <td>{{ $info->address }}</td>
                                            <td class="text-center">{{ $info->days }} Days</td>
                                            <td class="text-right">${{ $info->p_return }}</td>
                                            <td class="text-center">
                                                <a href="?pg=statement&amp;vid={{ base64_encode($info->id) }}">Details</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if(count($rinfo) == 0)
                                        <tr>
                                            <td colspan="8">No records found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{--
                                You need to implement the paging_2() function separately since it's not part of Laravel.
                                --}}
                                {{-- Example:
                                {{ $rinfo->links() }}
                                --}}
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