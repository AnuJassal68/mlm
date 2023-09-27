@extends('admin.layout')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Include Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS (You may already have this) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- Initialize Datepicker (Put this in your JavaScript section) -->
<script>
    $(document).ready(function() {
        $('.picdate').datepicker();
    });

</script>
@section('content')
<section class="content">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Open Tickets</h4>
            <form action="{{ route('support') }}" method="get">
                <div class="box-body">
                    <div class="row">
                        {{-- Date From --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date from:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="height: 32px;">
                                            <i class="fa fa-calendar text-dark"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control pull-right picdate" name="df" value="{{ $df }}" />
                                </div>
                            </div>
                        </div>
                        {{-- Date To --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date to:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" style="height: 32px;">
                                            <i class="fa fa-calendar text-dark"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control pull-right picdate" name="dt" value="{{ $dt }}" />
                                </div>
                            </div>
                        </div>
                        {{-- Search Keyword --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Search Keyword</label>
                                <div class="input-group">
                                    <div class="input-group-btn bg-light border" style="height: 33px;">
                                        <select name="fo" id="fo" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                            <option value="">All</option>
                                            <option value="username" {{ $fo == 'username' ? 'selected' : '' }}>Username</option>
                                            <option value="network" {{ $fo == 'network' ? 'selected' : '' }}>Network</option>
                                        </select>
                                    </div>
                                    <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Search..." />
                                    <span class="input-group-btn">
                                        <button type="submit" name="searchqry" id="search-btn" class="btn btn-flat btn-info"><i class="fa fa-search"></i></button>
                                        @if(request()->has('q') || request()->has('fo') || request()->has('md') || request()->has('df') || request()->has('dt'))
                                        <a href="{{route('support')}}" type="submit" name="reset" id="reset-btn" class="btn btn-flat btn-danger" title="Reset Search Filters"><i class="fa fa-refresh"></i> </a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="box-body">
                <table id="order-listing" class="table table-bordered table-hover table-striped" id="example">
                    <thead class="thead  text-white text-center" style="background: #0b3547;" height="50px;">
                        <tr>
                            {{-- <th><input type="checkbox" id="select_all" class="chk-users" name="checkbox[]" />
                                    </th> --}}
                            {{-- <th> Serial No</th> --}}

                            <th>
                                Tic. No.
                            </th>
                            <th>
                                User Info
                            </th>
                            <th>
                                Subject
                            </th>
                            <th>
                                Type
                            </th>
                            <th class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                        $knt = 0;
                        $ded =0;
                        @endphp
                        @foreach($rinfo as $r)
                        @php
                        $knt+=1;
                        @endphp
                        <tr>
                            {{-- <td class="text-center">
                                    <input type="checkbox" name="del[]" value="{{ $r->transactionId }}" class="delids">
                            <input type="hidden" value="{{ $ded }}" name="ded[]">
                            </td> --}}

                            <td>{{ $r->ticketId }}</td>
                            <td>
                                <span id="ui{{ $r->ticketId }}">
                                    <a href="{{ route('profile.edit',['id' =>$r->id ])}}?mode=support">{{ $r->firstname }} ({{ $r->firstname }})</a>
                                </span><br>
                                <i class="fa fa-envelope"></i> {{ $r->emailid }}
                            </td>
                            <td>{{ $r->subject }}</td>
                            <td>
                                <span id="bat{{ $r->ticketId }}">{{ $modes[$r->mode] }}</span>
                                <input type="hidden" id="ba{{ $r->ticketId }}" value="{{ $r->mode }}">
                            </td>
                            <td class="text-center">
                                <a href="#{{ $r->ticketId }}" class="btn btn-primary view-button" data-bs-toggle="modal" data-bs-target="#ticketModal" data-ticket-id="{{ $r->ticketId }}">View</a>

                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
            </div>
            <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                           <!-- Custom Close Button -->

                            <h4 class="modal-title" id="myModalLabel">Ticket <span id="uinfo_v"></span></h4>
                        </div>
                        <div class="modal-body">
                            <div id="retmsg"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="ticket_list" style="height:250px;overflow:auto"> </div>
                                </div>
                                <div class="col-md-12">
                                    <br />
                                    <textarea class="form-control" name="tmessage" id="tmessage" style="height:100px;">Dear Trader, </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" id="modal-footer">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="hidden" name="ptype" id="ptype" value="0" />
                                    <input type="hidden" name="reqid" id="reqid" value="" />
                                    <input type="button" name="updateticket" value="Reply Ticket" id="updateticket" class="btn btn-success pull-left" />
                                    <input type="button" name="pendingticket" value="Move to Pending" id="pendingticket" class="btn btn-danger pull-left" />
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



</section>
<!-- Include the necessary JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.11.0/dist/js/bootstrap-datepicker.min.js"></script> --}}

<script>
    window.csrfToken = "{{ csrf_token() }}";
    $(document).on('click', '.view-button', function() {
        var ticketId = $(this).attr('href').substring(1);

        $.ajax({
            url: 'get-ticket-details/' + ticketId
            , method: 'GET',


            success: function(response) {

                if (response.ret) {
                    var modalContent = $('<div class="modal-content"></div>');
                    var userParts = response.user.split(' '); // Split the user value into parts
                    var userIdPart = userParts[userParts.length - 1]; // Assuming the user ID is the last part

                    // Convert the user ID part to an integer
                    var userId = parseInt(userIdPart);
                    // Modal Header
                    var modalHeader = $('<div class="modal-header"></div>');
                  
                    modalHeader.append('<h4 class="modal-title" id="myModalLabel">Ticket <span id="uinfo_v"> : ' + response.user + '</span></h4>');
                    modalContent.append(modalHeader);

                    // Modal Body
                    var modalBody = $('<div class="modal-body"></div>');
                    var timelineUl = $('<ul class=""></ul>');

                    response.ret.forEach(function(message) {
                        var timelineItem = $('<li><div class="timeline-item"></div></li>');
                        var timelineItemDiv = timelineItem.find('.timeline-item');


                        var formattedDateTime = formatDateTime(message.created_at);
                        timelineItemDiv.append('<span class="time"><i class="fa fa-clock-o"></i> ' + formattedDateTime + '</span>');
                        // Function to format date and time
                        function formatDateTime(dateTime) {
                            var dateObj = new Date(dateTime);
                            var options = {
                                year: 'numeric'
                                , month: 'numeric'
                                , day: 'numeric'
                                , hour: 'numeric'
                                , minute: 'numeric'
                                , second: 'numeric'
                                , hour12: false // Use 24-hour format
                            };
                            return dateObj.toLocaleDateString('en-US', options);
                        }
                        timelineItemDiv.append('<h3 class="timeline-header"><a href="#">' + response.user + '</a></h3>');
                        var timelineFooter = $('<div class="timeline-footer"></div>');
                        var editTextarea = $('<textarea class="form-control edit-textarea" name="msg" style="height:100px;"></textarea>');
                        var currentTicketMessageId = message.ticketMessageId;
                        // Attach click event to Edit button
                        timelineItemDiv.append('<div class="timeline-message">' + message.message + '</div>');
                        timelineItemDiv.append(timelineFooter);

                        timelineUl.append(timelineItem);
                    });

                    modalBody.append('<div id="ticket_list" style="height:250px;overflow:auto"></div>'); // Placeholder for timeline
                    modalBody.find('#ticket_list').append(timelineUl);
                    modalBody.append('<br><textarea class="form-control" name="tmessage" id="tmessage" style="height:100px;">Dear Trader, </textarea>');

                    // Append Modal Body to Content
                    modalContent.append(modalBody);

                    // Modal Footer
                    var modalFooter = $('<div class="modal-footer" id="modal-footer"></div>');
                    var rowFooterDiv = $('<div class="row"></div>');
                    var col6FooterDiv = $('<div class="col-xs-6"></div>');
                    col6FooterDiv.append('<input type="hidden" name="ptype" id="ptype" value="0">');
                    col6FooterDiv.append('<input type="hidden" name="reqid" id="reqid" value="198">');
                    var replyButton = $('<input type="button" name="updateticket" value="Reply Ticket" id="updateticket" class="btn btn-success pull-left">');
                    replyButton.attr('data-ticket-id', ticketId);
                    replyButton.data('user_id', response.ret[0].userId);

                    replyButton.on('click', function() {
                        var ticketId = response.ret[0].ticketId;
                        var userId = response.ret[0].userId;
                        var replyMessage = $('#tmessage').val();
                        console.log(ticketId)
                        $.ajax({
                            url: '/process-ticket'
                            , method: 'POST'
                            , data: {
                                _token: window.csrfToken
                                , ticketId: ticketId
                                , tmessage: replyMessage
                                , userId: userId
                            }
                            , success: function(response) {
                                if (response.success) {
                                    console.log('Ticket reply successful.');
                                    $('#ticketModal').modal('hide');
                                } else {
                                    console.error('Ticket reply failed: ' + response.message);
                                }
                            }
                        });
                    });

                    col6FooterDiv.append(replyButton);
                    var ticketId = response.ret[0].ticketId;
                    var pendingButton = $('<input type="button" name="pendingticket" value="Move to Pending" id="pendingticket" class="btn btn-danger pull-left">');

                    pendingButton.data('ticket-id', ticketId);
                    pendingButton.on('click', function() {
                        var ticketId = pendingButton.data('ticket-id'); // Assuming you have a data attribute for ticketId
                        console.log(ticketId);
                        $.ajax({
                            url: '{{ route("moveToPending") }}', // Use the named route
                            method: 'POST'
                            , data: {
                                _token: window.csrfToken
                                , ticketId: ticketId
                            }
                            , success: function(response) {
                                if (response.success) {
                                    console.log('Ticket moved to pending.');
                                    $('#ticketModal').modal('hide'); // Close the modal or perform other actions
                                } else {
                                    console.error('Failed to move ticket to pending.');
                                }
                            }
                        });
                    });

                    col6FooterDiv.append(pendingButton);
                    var col6FooterDiv2 = $('<div class="col-xs-6"></div>');
                    col6FooterDiv2.append('<div class="form-group"></div>');
                    col6FooterDiv.append(replyButton);
                    rowFooterDiv.append(col6FooterDiv);
                    rowFooterDiv.append(col6FooterDiv2);
                    modalFooter.append(rowFooterDiv);
  var closeButton = $('<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>');

                // Add a click event handler to close the modal when the button is clicked
                closeButton.on('click', function() {
                    $('#ticketModal').modal('hide');
                });

                // Append the custom close button to the modal header
                modalHeader.append(closeButton);
                    // Append Modal Footer to Content
                    modalContent.append(modalFooter);

                    // Set Modal Content and Show Modal
                    $('#ticketModal').find('.modal-content').html(modalContent);
                    $('#ticketModal').modal('show');
                }
            }
        });
    });

</script>

@endsection
