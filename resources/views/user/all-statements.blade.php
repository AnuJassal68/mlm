<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@include('user.include.header')

<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">All Statement</h4>
        <p class="sub-title">List of all credit and debit amount from your account.</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Summary <small>(All Statement)</small></h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsivea">
                                <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Credit Date</th>
                                            <th>Description</th>
                                            <th class="text-right">Credit</th>
                                            <th class="text-right">Debit</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $knt = 0;
                                        @endphp
                                        @foreach($list as $item)
                                            @php
                                                $knt++;
                                            @endphp
                                            <tr>
                                                <th scope="row" class="text-center">{{ ($knt + $_GET['offset']) }}</th>
                                                <td>
                                                    <b>{{ date("d, M Y", $ucrdt) }}</b><br>
                                                </td>
                                                <td>{{ $description }}</td>
                                                <td class="text-right">${{ $amount }}</td>
                                                <td class="text-right">${{ $debit }}</td>
                                                <td class="text-right">${{ round($total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @php
                                    $str = "pg=all-statement";
                                @endphp
                                @include('paging_2', ['str' => $str, 'width' => '50%'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add the DataTables JS -->
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#transactionTable').DataTable();
    });
</script>

@include('user.include.footer')

