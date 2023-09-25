
@include('user.include.header')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Investment Pricing</h4>
    </div>
    <section class="section">
		<div class="container">
			<div class="row">
				
            </div>
            <!-- /.row -->
			<div class="row mt-30">
				<div class="col-sm-4">
					<div class="pricing-box my-primary">
						<div class="pricing-head">
							<span style="color:black;">$10.00 to $2999.00</span>
                            <h2><b>Prime</b></h2>
                            <h6>Try &amp; buy</h6>
							<i class="bg-icon fa fa-user"></i>
                        </div>
                        <!-- /.pricing-head -->
                        <div class="pricing-body">
							<ul>
								<li>1.25% Every day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
								<li>200% in 160 Working days <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Earning Start from 3rd day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Feature of Re Invest enabled</li>
							</ul>
                        </div>
                        <!-- /.pricing-body -->
                        <div class="pricing-foot">
							<a href="#"  class="visible-content" data-toggle="modal" data-target="#modal2">Buy Now</a>
                        </div>
                        <!-- /.pricing-foot -->
                    </div>
                    <!-- /.pricing-box -->
                </div>
                <!-- /.col-sm-4 -->
				
				<div class="col-sm-4">
					<div class="pricing-box popular my-secondary">
						<div class="pricing-head">
							<span class="">$3000.00 to $9999.00</span>
                            <h2>Deluxe</h2>
                            <h6>A Great Start</h6>
                            <i class="bg-icon fa fa-ticket"></i>
                        </div>
                        <!-- /.pricing-head -->
                        <div class="pricing-body">
							<ul>
								<li>2% Every day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>200% in 100 Working days <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Earning Start from 3rd day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Feature of Re Invest enabled</li>
                            </ul>
                        </div>
						<!-- /.pricing-body -->
                        <div class="pricing-foot">
							<a href="#"  class="visible-content" data-toggle="modal" data-target="#modal2">Buy Now</a>
                        </div>
                        <!-- /.pricing-foot -->
                    </div>
                    <!-- /.pricing-box -->
				</div>
                <!-- /.col-sm-4 -->
				
				<div class="col-sm-4">
					<div class="pricing-box my-red">
						<div class="pricing-head">
							<span class="" style="color:black;">$10000.00 to $30000.00 </span>
							<h2>Infinite</h2>
                            <h6>Amazing Opening</h6>
                            <i class="bg-icon fa fa-bank"></i>
						</div>
                        <!-- /.pricing-head -->
                        <div class="pricing-body">
							<ul>
								<li>2.5% Every day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
								<li>200% in 80 Working days <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Earning Start from 3rd day <i class="icon fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="Something more about this"></i></li>
                                <hr>
                                <li>Feature of Re Invest enabled</li>
							</ul>
						</div>
						<!-- /.pricing-body -->
						<div class="pricing-foot">
							<a href="#"  class="visible-content" data-toggle="modal" data-target="#modal2">Buy Now</a>
						</div>
						<!-- /.pricing-foot -->
					</div>
                    <!-- /.pricing-box -->
                </div>
				
				<div class="col-md-6">
					<!-- Modal -->
                    <div class="modal fade  draggable" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2Label">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header draggable-handle">
									<h4 class="modal-title text-center" id="modal2Label"> Enter the Amount you want to pay  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
								</div>
								<div class="modal-body">
									<form class="form-horizontal" role="form" id="deposit_form" method="post" action="{{route('submit-Deposit')}}">
										@csrf
										<div class="form-group">
											<label for="default" class="col-sm-2 control-label" style="font-size:20px;">$</label>
                                            <div class="col-sm-10">
												<input type="number" class="form-control input-lg" id="default" placeholder="e.g. 100.00" min="10" name="deposit" required>
											</div>
										</div>
										<div class="text-center">
											<button type="submit" name="submit_deposit" class="btn btn-primary btn-wide btn-lg text-center btn-animated">
												<span class="visible-content">Submit Value</span>
												<span class="hidden-content"><i class="fa fa-arrow-right"></i></span>
											</button>
                                        </div>
                                    </form>
								</div>
								<div class="modal-footer">
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-gray btn-wide btn-rounded text-center" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                                    </div>
                                    <!-- /.btn-group -->
								</div>
							</div>
						</div>
					</div>
					<!-- /.col-md-12 -->
				</div>
			</div>
		</div>
	</section>
</section>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>