@include('include.header')
<div id="login" class="padding ptb-xs-60 page-signin">
	<div class="container">
		<div class="row">
			<div class="main-body">
				<div class="body-inner">
					<div class="card bg-white">
						<form class="form-horizontal ng-pristine ng-valid" role="form" name="form" id="regform" method="post" action="" onsubmit="return validateForm();">
							<div class="card-content">
								<section class="logo text-center">
									<h2>Register</h2>
								</section>
							
								<fieldset class="">
									<div class="form-group">
										<div class="ui-input-group">
											<input type="text" required class="form-control" name="referralid" id="refid"  autofocus>
											<span class="input-bar"></span>
											<label>Your Sponser ID</label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group error-message">
											<input type="text" required class="form-control" value="" disabled="disabled" id="viewrefid" placeholder="Sponsor Info">
											<span class="input-bar"></span>
											<label></label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group">
											<input type="text" required class="form-control" name="loginid" pattern=".{5,12}" title="Please enter Minimum of 5 Characters" maxlength="12"  id="text" onkeyup="nospaces(this)">
											<span class="input-bar"></span>
											<label>Your Username <span class="star">*</span></label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group">
											<input type="text" required class="form-control" id="text" name="firstname"  onkeyup="nospaces(this)">
											<span class="input-bar"></span>
											<label>Your First Name <span class="star">*</span></label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group">
											<input type="text" required class="form-control" id="text" name="lastname" onkeyup="nospaces(this)">
											<span class="input-bar"></span>
											<label>Your Last Name <span class="star">*</span></label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group">
											<input type="email" required  class="form-control" id="email" name="email" >
											<span class="input-bar"></span>
											<span id="memail" style="color:hsl(358, 66%, 45%);"></span>
											<label>Your Email</label>
										</div>
									</div>
									<div class="form-group">
										<div class="ui-input-group" id="staticParent">
											<input type="text"  required class="form-control" id="phone" name="mobile"  minlength="10" maxlength="10">
											<span class="input-bar"></span>
											<label>Your Mobile Number <span class="star">*</span></label>
										</div>
									</div>
									<div class="spacer"></div>
									<div class="form-group checkbox-field">
										<label for="check_box" class="text-small">
											<input type="checkbox" id="check_box" required>
											<span class="ion-ios-checkmark-empty22 custom-check"></span> By clicking on sign up, you agree to <a href="javascript:;"><i>terms</i></a> and <a href="javascript:;"><i>privacy policy</i></a>
										</label>
									</div>
								</fieldset>
							</div>
							<div class="card-action no-border text-right">
								<button type="submit" name="nregsubmit" style="background:none;float:left;"><a href="log-in.php" class="color-primary">SIGN IN</a></button>
								<button type="submit" name="nregsubmit" style="background:none;"><a class="color-primary ">SIGN UP</a></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('include.footer')