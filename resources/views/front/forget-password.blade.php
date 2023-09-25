@include('include.header')
<div id="login" class="padding ptb-xs-60 page-signin">
	<div class="container">
		<div class="row">
			<div class="main-body">
				<div class="body-inner">
					<div class="card bg-white">
						<form class="form-horizontal ng-pristine ng-valid" action="{{route('sendPasswordResetEmail')}}" method="post">
						@csrf
							<div class="card-content">
								<section class="logo text-center">
									<h2>Forgot Password</h2>
								</section>
							
								<fieldset>
									<div class="form-group">
										<div class="ui-input-group">
											<input type="text" required class="form-control" id="email" name="emailid" >
											<span class="input-bar"></span>
											<label>Email</label>
										</div>
									</div>
								</fieldset>
							</div>
							<div class="card-action no-border text-right">
								<button type="submit" name="submitlogin" style=" background: none;"><a class="color-primary">SUBMIT</a></button>
							</div>
						</form>
					</div>
					<div class="additional-info">
						<a href="signup.php">Register</a><span class="divider-h"></span><a href="/log-in">Login</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('include.footer')