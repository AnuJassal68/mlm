@include('user.include.header')



    <section class="section">
        <div class="section-title text-center">
            <h4 class="title underline">Re-Invest here</h4>
            <p class="sub-title">Re-Invest your earned amount here, get daily incentive as you pay with Bitcoins.</p>
        </div>
        <div class="container-fluid">
            <div class="row mt-15">
                <div class="col-md-8 col-md-offset-2">             
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5 class="text-center"><b>Balance in your account</b></h5><br>
                                <h2 class="text-center"><b>${{ $cbal }}</b></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <form role="form" id="deposit_form" method="post" action="" class="form-horizontal">
                                           @csrf
                                            <div class="form-group">
                                                <label for="default" class="col-sm-4 control-label">Enter desired amount</label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="deposit" class="form-control input-lg" id="default" min="10" max="{{ $cbal }}" placeholder="e.g. 100.00">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-wide btn-lg text-center btn-animated">
                                                    <span class="visible-content">Proceed</span>
                                                    <span class="hidden-content"><i class="fa fa-arrow-right"></i></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

