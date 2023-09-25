@include('user.include.header')
<section class="section">
    <div class="section-title text-center">
        <h4 class="title underline">Creat New Ticket</h4>
        <p class="sub-title">We're here to help you anytime, please feel free</p>
    </div>
	<section class="section pt-n">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="panel border-primary no-border border-3-top">
						<div class="panel-heading">
							<div class="panel-title">
								<h5 style="visibility:hidden;">Horizontal Form</h5>
							</div>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="{{route('send-ticket')}}">
                            @csrf
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Subject</label>
									<div class="col-sm-10">
										<input type="text" class="form-control input-lg" placeholder="your subject" id="subject" name="subject" maxlength="35" required>
									</div>
								</div>
								<div class="form-group">
									<label for="textarea" class="col-sm-2 control-label">Textarea</label>
									<div class="col-sm-10">
										<textarea class="form-control input-lg" id="message" placeholder="Leave your message" rows="5" name="message" required></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-10">
										<button type="submit" class="btn btn-primary btn-rounded btn-labeled" id="ticketSubmit" name="send"><span class="btn-label"><i class="fa fa-send-o"></i></span>Send Ticket</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$.noConflict();
	$(window).keydown(function(event){
		if(event.target.tagName != 'TEXTAREA') {
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		}
	});
});
</script>
<script src="users/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
>
