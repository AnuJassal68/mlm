@extends('admin.layout')


@section('content')

<section class="content">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Closed Tickets</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <form action="{{ route('support-closed') }}" method="get">
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
                                                    <button type="submit" style="height: 33px;" name="searchqry" id="search-btn" class="btn btn-flat btn-info"><i class="fa fa-search text-center"></i></button>
                                                    @if(request()->has('q') || request()->has('fo') || request()->has('md') || request()->has('df') || request()->has('dt'))
                                                    <a href="{{route('support-closed')}}" type="submit" name="reset" id="reset-btn" class="btn btn-flat btn-danger" title="Reset Search Filters"><i class="fa fa-refresh"></i> </a>
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
                                        <th class="info"> Tic. No</th>
                                        <th class="info"> User Info</th>
                                        <th class="info"> Subject </th>
                                        <th class="info">Type</th>
                                        <th class="text-center">View</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center fs-2">
                                    @php
                                    $ded = ''; // Set an appropriate default value for $ded
                                    $modes = [
                                    1 => "Technical",
                                    2 => "Admin",
                                    3 => "eBank",
                                    4 => "shop",
                                    ];

                                    @endphp
                                    @foreach($rinfo as $ticket)
                                    <tr>
                                        <td class="text-center">{{ $ticket->ticketId }}</td>
                                        <td>
                                            <a href="{{ route('profile.edit', ['id' => $ticket->id]) }}?mode=support-closed" id="ui{{ $ticket->ticketId }}">
                                                {{ $ticket->firstname }} ({{ $ticket->loginid }})
                                            </a><br>
                                            <i class="fa fa-envelope"></i> {{ $ticket->emailid }}
                                        </td>
                                        <td>
                                            {{ $ticket->subject }}
                                        </td>
                                        <td>
                                            <span id="bat{{ $ticket->ticketId }}">{{ $modes[$ticket->mode] }}</span>
                                            <input type="hidden" id="ba{{ $ticket->ticketId }}" value="{{ $ticket->mode }}">
                                        </td>
                                        <td class="text-center">
                                            <a href="#{{ $ticket->ticketId }}" data-toggle="modal" data-target="#ticketModal" data-remote="false" class="btn  btn-info view-button">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <!-- Pagination links -->
                        </div>
                        </form>
                        <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><span id="uinfo_v"></span></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="retmsg"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="ticket_list" style="height:250px;overflow:auto"> </div>
                                            </div>
                                            <div class="col-md-12">
                                                <br />
                                                <textarea class="form-control" name="tmessage" id="tmessage" style="height:100px;">Dear Trader,</textarea>
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
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    window.csrfToken = "{{ csrf_token() }}";

    $(document).on('click', '.view-button', function() {
        var ticketId = $(this).attr('href').substring(1);
        console.log(ticketId)
        $.ajax({
            url: 'get-closed-ticket-data/' + ticketId
            , method: 'GET'
            , success: function(response) {
                console.log(response);
                if (response.ret) {
                    var modalContent = $('<div class="modal-content"></div>');
                    var userParts = response.user.split(' ');
                    var userIdPart = userParts[userParts.length - 1];
                    var userId = parseInt(userIdPart);

                    var modalHeader = $('<div class="modal-header"></div>');

                    modalHeader.append('<h4 class="modal-title" id="myModalLabel">Ticket <span id="uinfo_v"> : ' + response.user + '</span></h4>');
                    modalContent.append(modalHeader);

                    var modalBody = $('<div class="modal-body"></div>');
                    var timelineUl = $('<ul class="" ></ul>');
                    response.ret.forEach(function(message) {
                        console.log(response);
                        var timelineItem = $('<li><div class="timeline-item"></div></li>');
                        var timelineItemDiv = timelineItem.find('.timeline-item');

                        var formattedDateTime = formatDateTime(message.created_at);
                        timelineItemDiv.append('<span class="time"><i class="fa fa-clock-o"></i> ' + formattedDateTime + '</span>');

                        function formatDateTime(dateTime) {
                            var dateObj = new Date(dateTime);
                            var options = {
                                year: 'numeric'
                                , month: 'numeric'
                                , day: 'numeric'
                                , hour: 'numeric'
                                , minute: 'numeric'
                                , second: 'numeric'
                                , hour12: false
                            };
                            return dateObj.toLocaleDateString('en-US', options);
                        }

                        var senderName = (message.userId == 1) ? 'Support Team' : response.user;

                        timelineItemDiv.append('<h3 class="timeline-header"><a href="#">' + senderName + '</a></h3>');

                        var timelineFooter = $('<div class="timeline-footer"></div>');
                        var currentTicketMessageId = message.ticketMessageId;
                        timelineItemDiv.append('<div class="timeline-message">' + message.message + '</div>');
                        timelineItemDiv.append(timelineFooter);
                        timelineUl.append(timelineItem);
                    });


                    modalBody.append('<div id="ticket_list" style="height:250px;overflow:auto"></div>');
                    modalBody.find('#ticket_list').append(timelineUl);
                    modalBody.append('<br><textarea class="form-control" name="tmessage" id="tmessage" style="height:100px;">Dear Trader, </textarea>');
                    // Append Modal Body to Content
                    modalContent.append(modalBody);

                    // Modal Footer
                    var modalFooter = $('<div class="modal-footer" id="modal-footer"></div>');
                    var rowFooterDiv = $('<div class="row"></div>');
                    var col6FooterDiv = $('<div class="col-xs-6"></div>');
                    col6FooterDiv.append('<input type="hidden" name="ptype" id="ptype" value="0">');

                    var pendingButton = $('<input type="button" name="pendingticket" value="Move to Pending" id="pendingticket" class="btn btn-danger pull-left">');

                    pendingButton.data('ticket-id', ticketId);
                    pendingButton.on('click', function() {
                        var ticketId = pendingButton.data('ticket-id');
                        console.log(ticketId)
                        $.ajax({
                            url: '{{ route("moveToPending") }}'
                            , method: 'POST'
                            , data: {
                                _token: window.csrfToken
                                , ticketId: ticketId
                            }
                            , success: function(response) {
                                if (response.success) {
                                    console.log('Ticket moved to pending.');
                                    $('#ticketModal').modal('hide');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 100);
                                } else {
                                    console.error('Failed to move ticket to pending.');
                                }
                            }
                        });
                    });

                    col6FooterDiv.append(pendingButton);
                    rowFooterDiv.append(col6FooterDiv);
                    modalFooter.append(rowFooterDiv);
                    var closeButton = $('<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>');

                    closeButton.on('click', function() {
                        $('#ticketModal').modal('hide');
                    });

                    modalHeader.append(closeButton);

                    modalContent.append(modalFooter);

                    $('#ticketModal').find('.modal-content').html(modalContent);
                    $('#ticketModal').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.ecmsg', function() {
        var editButton = $(this);
        var timelineItemDiv = editButton.closest('.timeline-item');
        var messageDiv = timelineItemDiv.find('.timeline-message');
        var editTextarea = timelineItemDiv.find('.edit-textarea');

        editButton.hide();
        editButton.siblings('.ccmsg, .ucmsg').show();
        messageDiv.hide();

        editTextarea.val(messageDiv.text()).show().prop('disabled', false);
    });

    $(document).on('click', '.ccmsg', function() {
        var cancelButton = $(this);
        var timelineItemDiv = cancelButton.closest('.timeline-item');
        var messageDiv = timelineItemDiv.find('.timeline-message');
        var editTextarea = timelineItemDiv.find('.edit-textarea');

        cancelButton.hide();
        cancelButton.siblings('.ecmsg').show();
        cancelButton.siblings('.ucmsg').hide();
        messageDiv.show();
        editTextarea.hide().prop('disabled', true);
    });

    $(document).on('click', '.ucmsg', function() {
        var updateButton = $(this);
        var messageId = updateButton.data('message-id');
        var timelineItemDiv = updateButton.closest('.timeline-item');
        var messageDiv = timelineItemDiv.find('.timeline-message');
        var editTextarea = timelineItemDiv.find('.edit-textarea');
        var updatedMessage = editTextarea.val();

        $.ajax({
            url: '/update-ticket-message/' + messageId
            , data: {
                _token: window.csrfToken
                , msg: updatedMessage
            }
            , success: function(response) {
                if (response.success) {
                    console.log('Ticket message updated successfully.');

                    messageDiv.text(updatedMessage);

                    updateButton.hide();
                    updateButton.siblings('.ccmsg, .ecmsg').show();
                    messageDiv.show();
                    editTextarea.hide().prop('disabled', true);

                } else {
                    console.error('Failed to update ticket message.');
                }
            }
        });
    });

</script>






@endsection
