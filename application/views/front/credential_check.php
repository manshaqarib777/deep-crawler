<div class="row" style="margin-top: 100px;">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
		<div class="well" style="border: 1px solid #2A95BE;">
			<div class="text-center"><h2 style="color: #F8971D; padding: 0px; margin: 0px;">Please Register Your Software</h2></div>
			<div style="margin-top: 15px;">
				<input type="text" class="form-control" placeholder="Enter Your Purchase Code Here" id="purchase_code">
			</div>
			<div id="success_msg"></div>
			<div class="text-center" style="margin-top: 10px;"><button class="btn btn-info" id="submit">Submit</button></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document.body).on('click','#submit',function(){
		var purchase_code = $("#purchase_code").val().trim();
		if(purchase_code == '') alert("Please Enter Your Purchase Code First !!");
		var domain_name = "<?php echo base_url(); ?>";

		$('#success_msg').html('<img class="center-block" style="margin-top:10px;" src="'+domain_name+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');

		$.ajax({
				type: "POST",
				url : "<?php echo site_url('home/credential_check_action'); ?>",
				data:{domain_name:domain_name,purchase_code:purchase_code},
				dataType: 'JSON',
				async: false,
				success:function(response){
					$("#success_msg").html('');
					if(response == "success"){
						var link = "<?php echo base_url('home/login'); ?>";
						window.location.assign(link);
					}
					else {
						$("#success_msg").html("<p class='alert alert-danger text-center' style='margin-top:15px;'>Error : "+response.reason+"</p>");
					}		
				}
			});


	});
</script>