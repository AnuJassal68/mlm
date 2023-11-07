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
                               <h4 class="text-center">{{ round($convertedCost, 5) }} BTC</h4> 
                                            
                                                
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
                                                     <input type="number" name="netamount" min="{{ $mindeposit }}" class="form-control input-lg" id="default" placeholder="e.g.100.00" required>

                                                        
<div id="tds" class="text-center"></div>
<div id="admincharges" class="text-center"></div>
<div id="totalinitaited" class="text-center"></div>
<div id="btcValueDisplay" class="text-center"></div>
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
                                   
                                       {{$uinfo->accountno}};
                                   
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


{{-- <script>
  $("input").keyup(function(){
        const amount = $(this).val();
        const coindeskPromise = fetch(`https://api.coindesk.com/v1/bpi/currentprice.json`)
            .then(response => response.json())
                       .then(data => {
                const btcRate = data.bpi.USD.rate_float;
                let tds = 0; // Default value if tds is not available
                let admincharges = 0; // Default value if admincharges is not available

            })
            .catch(error => console.error('Error:', error));

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        const balanceInfoPromise = $.ajax({
            type: 'POST',
            url: '{{ url("/get-balance-info") }}',
            data: {
                'amount': amount,
                '_token':"{{ csrf_token() }}",
            },
           
        });

        $.when(coindeskPromise, balanceInfoPromise)
            .done(function(coindeskResponse, balanceInfoResponse) {
             const tds = balanceInfoResponse[0].tds ?? 0;
             
                const admincharges = balanceInfoResponse[0].admincharges ?? 0;
                document.getElementById("tds").innerText = `TDS: ${Number(tds).toFixed(2)}`;
                document.getElementById("admincharges").innerText = `Admin Charges: ${Number(admincharges).toFixed(2)}`;
            })
            .fail(function(error) {
                console.error(error);
            });
    });

</script> --}}

<script>
$("input").on('input', function(){
    const amount = $(this).val();
    console.log(amount);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

     if (!amount) {
            hideElements();
            return; // Return early if the amount is empty
        }

    fetch(`https://api.coindesk.com/v1/bpi/currentprice.json`)
        .then(response => response.json())
        .then(data => {
            const btcRate = data.bpi.USD.rate_float;

            $.ajax({
                type: 'POST',
                url: '{{ url("/get-balance-info") }}',
                data: {
                    'amount': amount,
                    '_token':"{{ csrf_token() }}",
                },
            })
           
            .done(function(balanceInfoResponse) {
                 console.log(balanceInfoResponse,'anu');
                console.log('Response from server:', balanceInfoResponse);
                const tds = balanceInfoResponse.tds || 0;
                console.log(tds);
                const admincharges = balanceInfoResponse.admincharges || 0;
                console.log(admincharges);
                const deduct = tds + admincharges;
                console.log('Deduction:', deduct);
                const remainingAmount = amount - deduct;
                const remainingBtcValue = remainingAmount / btcRate;

                document.getElementById("btcValueDisplay").innerText = `BTC Value: ${remainingBtcValue.toFixed(8)}`;
                document.getElementById("tds").innerText = `TDS: ${Number(tds).toFixed(2)}`;
                document.getElementById("admincharges").innerText = `Admin Charges: ${Number(admincharges).toFixed(2)}`;
                document.getElementById("totalinitaited").innerText = `Remaining Amount: ${remainingAmount.toFixed(2)}`;
            })
            .fail(function(error) {
                console.error('Error in AJAX request:', error);
            });
        })
        .catch(error => console.error('Error:', error));
        function hideElements() {
        document.getElementById("btcValueDisplay").style.display = 'none';
        document.getElementById("tds").style.display = 'none';
        document.getElementById("admincharges").style.display = 'none';
        document.getElementById("totalinitaited").style.display = 'none';
    }
});



</script>


{{-- 
<input type="number" name="netamount" min="{{ $mindeposit }}" class="form-control input-lg" id="default" placeholder="e.g.100.00" required oninput="calculateBtcValue(this.value)">

<script>
    function calculateBtcValue(amount) {
        $.ajax({
            type: 'POST',
            url: '/get-balance-info',
            data: {
                amount: amount,
            },
            success: function (response) {
                var tds = response.tds;
                var admincharges = response.admincharges;
                // Use tds and admincharges as needed
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script> --}}
