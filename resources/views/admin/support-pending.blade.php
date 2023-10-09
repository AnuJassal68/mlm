@extends('admin.layout')


@section('content')
<section class="content">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pending Tickets</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <form action="{{ route('support-pending') }}" method="get">
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
                                                    <a href="{{route('support-pending')}}" type="submit" name="reset" id="reset-btn" class="btn btn-flat btn-danger" title="Reset Search Filters"><i class="fa fa-refresh"></i> </a>
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
                                        <th> Tic. No. </th>
                                        <th> User Info </th>
                                        <th>Subject </th>
                                        <th> Type</th>
                                        <th width="8%" class="text-center">View</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php
                                    $ded =0;
                                    @endphp
                                    @foreach ($rinfo as $row)
                                    <tr>
                                        <td>{{ $row->ticketId }}</td>
                                        <td>
                                            <a href="{{ route('profile.edit', ['id' => $row->id]) }}?mode=support-pending" id="ui{{ $row->ticketId }}">
                                                {{ $row->firstname }} ({{ $row->loginid }})
                                            </a><br><i class="fa fa-envelope"></i> {{ $row->emailid }}
                                        </td>
                                        <td>{{ $row->subject }}</td>
                                        <td>
                                            <span id="bat{{ $row->ticketId }}">{{ $modes[$row->mode] }}</span>
                                            <input type="hidden" id="ba{{ $row->ticketId }}" value="{{ $row->mode }}">
                                        </td>
                                        <td class="text-center">
                                            <a href="#{{ $row->ticketId }}" data-toggle="modal" data-target="#ticketModal" data-remote="false" class="btn btn-xs btn-info view-button">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if (count($rinfo) === 0)
                                    <tr>
                                        <td colspan="7" class="text-center">-no records-</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
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
        $.ajax({
            url: 'get-ticket-details/' + ticketId
            , method: 'GET'
            , success: function(response) {
                if (response.ret) {
                    var modalContent = $('<div class="modal-content"></div>');
                    var userParts = response.user.split(' ');
                    var userIdPart = userParts[userParts.length - 1];
                    var userId = parseInt(userIdPart);
                    var modalHeader = $('<div class="modal-header"></div>');
                    modalHeader.append('<h4 class="modal-title" id="myModalLabel">Ticket <span id="uinfo_v"> : ' + response.user + '</span></h4>');
                    modalContent.append(modalHeader);
                    var modalBody = $('<div class="modal-body"></div>');
                    var timelineUl = $('<ul class=""></ul>');
                    response.ret.forEach(function(message) {
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
                            , };
                            return dateObj.toLocaleDateString('en-US', options);
                        }

                        if (message && message.userId == 1) {
                            // Message is from the admin (user ID <= 1)
                            timelineItemDiv.append('<h3 class="timeline-header"><a href="#">Support Team</a></h3>');

                            var editButton = $('<button type="button" class="btn btn-xs btn-warning ecmsg m-1" data-message-id="' + message.ticketMessageId + '">Edit</button>');
                            var cancelButton = $('<button type="button" class="btn btn-xs btn-danger dnone ccmsg m-1" data-message-id="' + message.ticketMessageId + '">Cancel</button>');
                            var updateButton = $('<button type="button" class="btn btn-xs btn-success dnone ucmsg m-1" data-message-id="' + message.ticketMessageId + '">Update</button>');
                            var editTextarea = $('<textarea class="form-control edit-textarea mt-2 mb-2" name="msg" style="height:100px; display:none;"></textarea>');
                            var messageDiv = $('<div class="timeline-message">' + message.message + '</div>');
                            timelineItemDiv.append(messageDiv, editButton, cancelButton, updateButton, editTextarea);
                        } else {
                            // Message is from a user
                            timelineItemDiv.append('<h3 class="timeline-header"><a href="#">' + response.user + '</a></h3>');
                            timelineItemDiv.append('<div class="timeline-message">' + message.message + '</div>');
                        }

                        var timelineFooter = $('<div class="timeline-footer"></div>');
                        var currentTicketMessageId = message.ticketMessageId;
                        timelineItemDiv.append(timelineFooter);
                        timelineUl.append(timelineItem);
                    });

                    
                    modalBody.append('<div id="ticket_list" style="height:250px;overflow:auto"></div>'); // Placeholder for timeline
                    modalBody.find('#ticket_list').append(timelineUl);
                    modalBody.append('<br><textarea class="form-control" name="tmessage" id="tmessage" style="height:100px;">Dear Trader, </textarea>');
                    modalContent.append(modalBody);
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
                        event.preventDefault();
                        $.ajax({
                            url: '{{url("/process-ticket")}}'
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
                                    setTimeout(function() {
                                        location.reload();
                                    }, 100);
                                } else {
                                    console.error('Ticket reply failed: ' + response.message);
                                }
                            }
                        });
                    });
                    col6FooterDiv.append(replyButton);
                    var col6FooterDiv2 = $('<div class="col-xs-6"></div>');
                    col6FooterDiv2.append('<div class="form-group"></div>');
                    col6FooterDiv.append(replyButton);
                    rowFooterDiv.append(col6FooterDiv);
                    rowFooterDiv.append(col6FooterDiv2);
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
