

    @include('user.include.header')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Pay here</h4>
        <p class="sub-title" style="visibility: hidden">Just add <code>data-sortable="true"</code> to .panel to make them sortable</p>
    </div>

    <div class="container-fluid">
        <div class="row mt-15">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Your Bitcoin Transaction<small></small></h5>
                        </div>
                    </div>
                    <div class="panel-body">
                    

                        <form role="form" id="deposit_form" method="post" action="">
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Order No. <span style="color:red;"><b>#{{ $dinfo[0]['label'] }}</b></span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Amount <span style="color:red;"><b>{{ $dinfo[0]['deposit'] }}</b></span></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <h3>Pay <span style="color:red; font-weight:bold; font-style:italic;">{{ round($convertedCost, 5) }} btc</span> to the below address</h3>
                                </div>
                                <div class="col-md-4">
                                    <img src="" style="width: 50px; height:auto;">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <p style="color:black; font-weight:bold;">{{ $dinfo[0]['address']}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group col-sm-6">
                                        <!---div id="qrcode"></div--->
                                        <img src="http://chart.googleapis.com/chart?chs=125x125&cht=qr&chl=bitcoin:{{ $dinfo[0]['address'] }}?amount={{ round($convertedCost, 5) }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p style="color:black; font-weight:bold;">The generated address will be available for 15 minutes, after that need to click the below button to track the payment, or you can do this later in Requested Payments</p>

                            @if ($dinfo[0]['address'] != 'error')
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="submit" name="check_deposit" value="Click here if you deposited the Amount and your account has not Activated yet." class="btn btn-success"> 
                                    </div>
                                </div>
                                <hr>
                            @else
                              
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="path-to-bootstrap-datepicker.js"></script>

@include('user.include.footer')