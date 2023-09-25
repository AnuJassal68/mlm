<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@include('user.include.header')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Support Tickets</h4>
        <p class="sub-title">We're here to help you anytime, please feel free</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel border-primary no-border border-3-top">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h3 class="box-title">Your Question <a href="{{ route('support-ticket') }}" class="pull-right">&larr; go back </a></h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                @foreach ($uticinfo as $message)
                                @php
                                $fromus = ($message->image != '') ? ucfirst($userinfo[0]->loginid) : 'Admin';
                                $ptCls = ($message->image != '') ? 'danger' : 'success';
                                @endphp
                                <div class="box-body no-padding">
                                    <div class="mailbox-read-info">
                                        <h3>Subject: {{ $sub }}</h3><br>
                                        <h5><span class="btn-{{ $ptCls }}">&nbsp;From: {{ $fromus }}&nbsp;</span>
                                            <span class="mailbox-read-time pull-right">{{ date('d M, Y g:i A', strtotime($message->created_at)) }}</span></h5>
                                    </div>
                                    <div class="mailbox-read-message">
                                        <p>{!! nl2br(e($message->message)) !!}</p>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                <form role="form" method="post" action="{{route('replyToTicket')}}">
                                    @csrf
                                    <input type="hidden" name="tid" value="{{ $tid }}">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Reply to the above Ticket</label>
                                            <textarea class="form-control" rows="6" placeholder="Enter your message here...." id="message" name="message" required></textarea>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default btn-block btn-lg {{ $repBtn }}"><i class="fa fa-reply"></i> Reply</button>
                                    </div>
                                </form>
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
