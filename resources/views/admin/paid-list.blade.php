    @extends('admin.layout')



    @section('content')
    <section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card" style=" max-width: 95%;">
                <div class="card-body">
                    <h4 class="card-title">View Withdrawal</h4>
                    <div class="row">

                        <div class="col-sm-4 pull-right">
                            <div class="form-group">
                                <div class="input-group">

                                    <span class="input-group-btn">

                                        <a href="{{route('payouts.excel')}}"> <button type="submit" name="excel" id="excel-btn" class="btn btn-flat btn-success" title="Excel Download">excel</button></a>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" >
                           <table id="order-listing" class="table table-responsive text-center table-bordered table-hover table-striped">
                                <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                    <tr>
                                        <th>Date</th>
                                        <th>User info</th>
                                        <th>Bit Account</th>
                                        <th>Withdrawal</th>
                                        <th>Deduction</th>
                                        <th>Re-invest</th>
                                        <th>Net-Withdrawal</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)

                                    <tr>
                                        <td>{{ date('d/m/Y G:i:s', $result->createdate) }}</td>
                                        <td>{!! strtoupper($result->firstname . $result->lastname) !!} (<a href="{{ route('profile.edit', ['id' => $result->id]) }}?mode=paidlist">{{ $result->loginid }}</a>)</td>

                                        <td>{{ $result->bankdetails }}</td>
                                        <td class="text-right">${{ $result->processamt }}</td>
                                        <td class="text-right">${{ $result->tds }}</td>
                                        <td class="text-right">${{ $result->admincharges }}</td>
                                        <td class="text-right">${{ $result->chargedamount }}</td>

                                    </tr>
                                    @endforeach
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
</section>
    @endsection
