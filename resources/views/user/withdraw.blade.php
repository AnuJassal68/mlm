@include('user.include.header')
@section('content')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Request Payments</h4>
        <p class="sub-title">You can request your payment to your Bitcoin Accounts</p>
    </div>
    <section class="section pt-n">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel border-primary no-border border-3-top">
                        <div class="panel-heading">
                            <div class="panel-title">

                                <h5 class="text-center"><b>Balance in your account</b></h5><br>
                                <h2 class="text-center"><b>${{ $ret['binc'] * 1 }}</b></h2>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <form class="form-horizontal" id="withdraw_form" method="post" action="{{url('/request-Payments')}}">
                                                @csrf {{-- Add Laravel CSRF token to the form --}}
                                                <div class="form-group">
                                                    <label for="default" class="col-sm-4 control-label">Enter desired amount</label>
                                                    <div class="col-sm-4">
                                                        <input type="number" name="netamount" min="{{ $mindeposit }}" max="{{ $ret['binc'] }}" class="form-control input-lg" id="default" placeholder="e.g.100.00" required>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control input-lg" id="default" placeholder="{{ round($convertedCost, 5) }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    @if ($ret['binc'] >= $mindeposit)
                                                    @if ($uinfo)
                                                    <button id="btnsub" type="submit" class="btn btn-primary btn-wide btn-lg text-center btn-animated">
                                                        <span class="visible-content">Proceed</span>
                                                        <span class="hidden-content"><i class="fa fa-arrow-right"></i></span>
                                                    </button>
                                                    @else
                                                    {!! alert_box('Update your Bitcoin account for this request ! ', 'warning') !!}
                                                    @endif
                                                    @else
                                                    {!! alert_box('Your Request amount must be more than Net Balance $'.$mindeposit, 'error') !!}
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Your Bitcoin Account:
                                <span style="color: red; font-weight: bold">
                                    @if (!$uinfo)
                                    <a href="?pg=edit-profile">Click here</a> to update your Bitcoin Account !
                                    @else
                                    uptodate
                                    @endif
                                </span>
                            </p>
                            <p>Minimum Amount required: <span style="color:red; font-weight:bold">$ {{ $mindeposit }}.00</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
