<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@include('user.include.header')
@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Support Tickets</h4>
        <p class="sub-title">We're here to help you anytime, please feel free</p>
        {{-- @include('user.layouts.alert_box', ['emsg' => $emsg, 'etype' => $etype]) --}}
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                @if (request('mode') == 'list')
                {{-- Your code for showing ticket details goes here --}}
                @else
                <div class="col-md-12">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <div class="row">
                                    <div class="col-md-5">
                                        <h4 class="title">
                                            <a href="{{route('new-ticket')}}" type="button" class="btn btn-primary">
                                                <i class="fa fa-plus"></i>Create New
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered sumtbl" id="transactionTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Username</th>
                                            <th>Date & Time</th>
                                            <th>Subject</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ticinfo as $index => $ticket)
                                        @php

                                        $knt = $index + 1 + request('offset');


                                        $status = '';
                                        $btnClass = '';

                                        if ($ticket->isSolved == 0 || $ticket->isSolved == 2) {
                                        $status = 'Pending';
                                        $btnClass = 'danger';
                                        } else {
                                        $status = 'Resolved';
                                        $btnClass = 'success';
                                        }
                                        @endphp
                                        <tr>
                                            <th scope="row" class="text-center">{{ $knt }}</th>
                                            <td>
                                                <b>
                                                    <a href="{{route('support-ticket')}}&amp;mode=list&amp;tid={{ $ticket->ticketId }}&sub={{ $ticket->subject }}">
                                                        {{ ucfirst(session('user_name')) }}

                                                    </a>
                                                </b>
                                                <br>
                                            </td>
                                            <td>
                                                <b>{{ $ticket->created_at }}</b>
                                                <br>
                                            </td>
                                            <td>{{ substr($ticket->subject, 0, 55) }}</td>
                                           
                                            <td class="text-center">
                                                <span class="label label-{{ $btnClass }} label-rounded label-bordered">
                                                    <a href="{{route('filesupport')}}?mode=list&tid={{ $ticket->ticketId }}&sub={{ $ticket->subject }}">
                                                        {{ $status }}
                                                    </a>
                                                </span>
                                            </td>                          
                                        </tr>
                                        @endforeach
                                        @if (empty($ticinfo))
                                        <tr>
                                            <td colspan="5" class="text-center">-no records-</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if (count($ticinfo))
                                @php
                                $str = "pg=support-ticket";
                                @endphp

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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

