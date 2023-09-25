	<!-- jQuery 2.1.4 -->
	
		<!-- Bootstrap 3.3.2 JS -->
		
		
			<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="users/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
			<script>
			$(document).ready(function(){
				$('.wyeditor').wysihtml5();
			});
			</script>
	
	
		
			<script src="users/plugins/qrcode/jquery-qrcode-0.14.0.js"></script>
			
	
	
			
	
			<script src="users/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
			<script src="users/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
			<script src="users/plugins/datepicker/bootstrap-datepicker.js"></script>
			<!-- FastClick -->
			<script src='users/plugins/fastclick/fastclick.min.js'></script>
			<!-- AdminLTE App -->
			<script src="users/dist/js/app.min.js" type="text/javascript"></script>
			<script src="users/js/waypoint/waypoints.min.js"></script>
			<script src="users/js/counterUp/jquery.counterup.min.js"></script>
			<script>
				$(function(){

					// Counter for dashboard stats
					$('.counter').counterUp({
						delay: 10,
						time: 1000
					});

				});
				$(document).ready(function(){

					 $('#example').DataTable();
					
					$('.dataTable').dataTable( {
					  "ordering": false
					} );
					
					$('.sumtbl').dataTable( {
						"order": [[ 0, "desc" ]]
					} );

					 $(window).keydown(function(event){
						if(event.keyCode == 13) {
						  event.preventDefault();
						  return false;
						}
					  });

					 
					var sb = $("ul.sidebar-menu").height();
					var cw = $(".content-wrapper").height();
					if(sb>cw){
						$(".content-wrapper").height(sb+'px');
					}
					
					$(".acbut").show();
					$("#copy_per").click(function(){
						if ($('input#copy_per').prop('checked')) {
							
							$("#c_address").val($("#address").val());
							
							$("#c_landmark").val($("#landmark").val());
							
							$("#c_country").html($("#country").html());
							$("#c_country").val($("#country").val());
							
							$("#c_state").html($("#state").html());
							$("#c_state").val($("#state").val());
							
							$("#c_city").html($("#city").html());
							$("#c_city").val($("#city").val());
							
							if($('#pincode').css('display') == 'none'){
								$("#c_pincode").hide();
								$("#c_apincode").show();
								$("#c_apincode").html($("#apincode").html());
								$("#c_apincode").val($("#apincode").val());
							}
							else {
								$("#c_pincode").val($("#pincode").val());
							}
							
						}
						else {
							$("#c_address").val("");
							$("#c_landmark").val("");
							
							$("#c_country").val("");
							
							$("#c_state").val(0);
							
							$("#c_city").val(0);
							
							$("#c_pincode").val("");
							
							$("#c_pincode").show();
							$("#c_apincode").hide();
							$("#c_apincode").html('');
							
						}
					});
					$("#lead_status").change(function(){
						if(this.value==2){
							$("#addtoclient").show("slow");
							$(".acli").show("slow");
						}
						else {
							$("#addtoclient").hide("slow");
							$(".acli").hide("slow");
						}
					})

					$( "#withdraw_form" ).submit(function( event ) {
						var amount = $("input[name=netamount]").val();
						if(amount == ''){
							$("input[name=netamount]").css('border','1px solid red');
							 event.preventDefault();
						}
						else{
							var r = confirm("Are you sure want to continue?");
							if (r == true) {
							   
							} else {
								 event.preventDefault();
							}
						}
						
					});
					
				})
			</script>
		
		
		
			<script src="users/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
			<script src="users/plugins/datepicker/bootstrap-datepicker.js"></script>
			<!-- FastClick -->
			<script src='users/plugins/fastclick/fastclick.min.js'></script>
			<!-- AdminLTE App -->
			<script src="users/dist/js/app.min.js" type="text/javascript"></script>

			<script>
				$(document).ready(function(){

					

					 $(window).keydown(function(event){
						if(event.keyCode == 13) {
						  event.preventDefault();
						  return false;
						}
					  });

					 
					var sb = $("ul.sidebar-menu").height();
					var cw = $(".content-wrapper").height();
					if(sb>cw){
						$(".content-wrapper").height(sb+'px');
					}
					
					$(".acbut").show();
					$("#copy_per").click(function(){
						if ($('input#copy_per').prop('checked')) {
							
							$("#c_address").val($("#address").val());
							
							$("#c_landmark").val($("#landmark").val());
							
							$("#c_country").html($("#country").html());
							$("#c_country").val($("#country").val());
							
							$("#c_state").html($("#state").html());
							$("#c_state").val($("#state").val());
							
							$("#c_city").html($("#city").html());
							$("#c_city").val($("#city").val());
							
							if($('#pincode').css('display') == 'none'){
								$("#c_pincode").hide();
								$("#c_apincode").show();
								$("#c_apincode").html($("#apincode").html());
								$("#c_apincode").val($("#apincode").val());
							}
							else {
								$("#c_pincode").val($("#pincode").val());
							}
							
						}
						else {
							$("#c_address").val("");
							$("#c_landmark").val("");
							
							$("#c_country").val("");
							
							$("#c_state").val(0);
							
							$("#c_city").val(0);
							
							$("#c_pincode").val("");
							
							$("#c_pincode").show();
							$("#c_apincode").hide();
							$("#c_apincode").html('');
							
						}
					});
					$("#lead_status").change(function(){
						if(this.value==2){
							$("#addtoclient").show("slow");
							$(".acli").show("slow");
						}
						else {
							$("#addtoclient").hide("slow");
							$(".acli").hide("slow");
						}
					})

					$( "#withdraw_form" ).submit(function( event ) {
						var amount = $("input[name=netamount]").val();
						if(amount == ''){
							$("input[name=netamount]").css('border','1px solid red');
							 event.preventDefault();
						}
						else{
							var r = confirm("Are you sure want to continue?");
							if (r == true) {
							   
							} else {
								 event.preventDefault();
							}
						}
						
					});
					
				})
			</script>
			<script type="text/javascript">
			function myFunction() {
				var txt;
				if (confirm("Are you sure?") == true) {
					$("#deposit_form").submit(); 		
				} else {
					return false;
				}
			}
			</script>
			<script type="text/javascript">
			function hideFunction() {
				var txt;
				$("#withdraw_form").submit();
				$('#btnsub').remove();					
			}
			</script>
		
	</body>
</html>