    @extends('admin.layout')



    @section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
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

                            <div class="table-responsive">
                                <table id="order-listing" class="table table-responsive text-center table-bordered table-hover table-striped">
                                    <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                                        <tr>
                                            <th>Date</th>
                                            <th>User info</th>

                                            <th>Withdrawal</th>
                                            <th>Deduction</th>
                                            <th>Re-invest</th>
                                            <th>Net-Withdrawal</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($results as $result)
                                        @php
                                        if (isset($result->tds) && isset($result->admincharges) && isset($result->processamt)) {
                                        $Withdrawal = $result->processamt;
                                        $deducted = $result->tds + $result->admincharges ;
                                        $total = $Withdrawal- $deducted ;
                                        // echo $deducted;
                                        } else {
                                        echo "One or both of the values are not set.";
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ date('d/m/Y G:i:s', $result->createdate) }}</td>
                                            <td>{!! strtoupper($result->firstname . $result->lastname) !!} (<a href="{{ route('profile.edit', ['id' => $result->id]) }}?mode=paidlist">{{ $result->loginid }}</a>)</td>

                                            <td class="text-right">${{ $result->processamt }}</td>
                                            <td class="text-right">${{ $result->tds }}</td>
                                            <td class="text-right">${{ $result->admincharges }}</td>
                                            <td class="text-right">${{ $total }}</td>
                                            <!-- Status Column -->
                                            <td class="text-right">
                                                @if($result->status == 0)
                                                <span style="color: red">Pending</span>
                                                @elseif($result->status == 1)
                                                <span style="color: green">Paid</span>
                                                @elseif($result->status == 2)
                                                <span style="color: red">Cancel</span>
                                                @endif
                                            </td>

                                            <!-- Button Column -->
                                            <td class="text-right">
                                                @if($result->status == 0)
                                                <div class="dropdown">
                                                    <select class="form-select" onchange="handleAction(this.value, {{$result->spentid}}, '{{$result->bankdetails}}', '{{$result->processamt}}')">
                                                        <option selected>Select</option>
                                                        <option value="pay">Pay</option>
                                                        <option value="cancel">Cancel</option>
                                                    </select>
                                                </div>
                                                @elseif($result->status == 1)
                                                <i class="fa fa-check" style="color: green"></i>
                                                @elseif($result->status == 2)
                                                <i class="fa fa-times" style="color: red"></i>
                                                @endif
                                            </td>
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
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="update-form" action="" method="post">
                        @csrf
                        <input type="hidden" name="userid" id="userid" />
                        <div class="modal-header">
                            <h4 class="modal-title">Pay Amount To User</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="card">
                            <div class="modal-body">
                                <div class="form-group row mt-3">
                                    <div id="accountno" class="display:flex; ">

                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-sm-8" id="description" style="display: none; display:flex;">
                                        <p class="col-form-label">Description:-</p>
                                        <textarea class="col-9 form-control" name="description" rows="3" style="margin-left:30px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center" style="display: none;">
                            <button type="submit" class="btn btn-primary" name="submit" id="submitBtn" data-bs-dismiss="modal" onclick="subpay(event, 'pay')">Pay</button>
                            <button type="button" class="btn btn-secondary" name="cancel" id="cancelBtn" onclick="subpay(event, 'cancel')" data-bs-dismiss="modal">Submit</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var currentId;

        function handleAction(action, spentid, bankdetails, processamt) {
            currentId = spentid;
            console.log(currentId);
            if (action === 'pay') {
                openModal('pay', spentid, bankdetails, processamt);
            } else if (action === 'cancel') {
                openModal('cancel', spentid, bankdetails, processamt);
            }

        }


        function subpay(event, action) {
            event.preventDefault();
            var id = currentId;
            console.log(id);

            var form = document.createElement('form');
            form.method = 'post';
            form.action = '{{ url( 'pay' ) }}' + '/' + id;

            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action; // Use the passed action parameter
            form.appendChild(actionInput);

            // Get the value from the textarea
            var description = document.querySelector('#description textarea').value;

            // Add the description to the form data
            var descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);

            document.body.appendChild(form);
            form.submit();
        }

        //working on fetching data 


        function openModal(action, id, bankdetails, processamt) {

            if (action === 'pay') {
                $('#accountno').html('<p for="accountno" class="col-form-label">Account Address:- ' + bankdetails + '</p>');
                $('#description').hide();
                $('#description').prev().hide();
                $('#processamt').hide();
                $('#submitBtn').show();
                $('#cancelBtn').hide();
                $('#myModal').modal('show');
            } else if (action === 'cancel') {
                $('#accountno').html('<p for="accountno" class="col-form-label">Account Address:- ' + bankdetails + '</p>');
                $('#description').show();
                $('#description').prev().show();
                $('#processamt').hide();
                $('#submitBtn').hide();
                $('#cancelBtn').show();
                $('#myModal').modal('show');
            }
        }

    </script>
    @endsection
