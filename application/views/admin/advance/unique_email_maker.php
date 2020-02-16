<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
		<div class='text-center'><h4 class="text-info"><strong>Duplicate Email Filter</strong></h4></div>
		<form enctype="multipart/form-data" method="post" class="form-inline text-center" id="new_search_form" style="margin-top:60px margin-left:10px">
			<div class="row">				
				<div class="form-group col-xs-12">
					<textarea id="bulk_email" style="width:100%;padding:10px;" rows="10" placeholder="Put your Emails or upload text/csv file - comma / in new line separated"></textarea>
					<br/><span style="margin-left:5%">OR</span><br/>
				</div> 
			</div>
			
			<div class="row">						
				<div class="form-group col-xs-6">
					<input type="file" name="whois_upload" id="whois_upload" multiple/>
				</div>

				<div class="form-group col-xs-6 clearfix">
					<button class='btn btn-success'  id = "pull_data"><i class="fa fa-upload"></i> Upload File</button>     
					<button type="button"  id="new_search_button" class="btn btn-info pull-right"><i class="fa fa-search"></i> Start Searching</button>
				</div>
				
			</div>

		</form>
	</div>
</div>
				



<!-- Start modal for response-->
<div id="modal_unique_email_result" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title">Unique Email Maker Result</h4>
			</div><br/>


			<div id="new_search_view_body" class="modal-body">				
			
				<div class="row"> 				
					<div class="col-xs-12" class="text-center" id="success_msg"></div>     
				</div> 
				<div class="row">					
					<div class="col-xs-9 col-xs-offset-2 col-sm-9 col-sm-offset-2 col-md-9 col-md-offset-3 col-lg-9 col-lg-offset-3 wow fadeInRight">		  
						<div class="loginmodal-container">
							
							<div id="unique_email_download_div">
				
							</div>
							                 
						</div>
					</div>			
				</div>

				
				
				
			</div> <!-- End of body div-->

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- End modal for new search. -->



	<script type="text/javascript">
		$("#pull_data").click(function(e){
		e.preventDefault();
		var site_url="<?php echo site_url();?>";  
		var queryString = new FormData($("#new_search_form")[0]);
		$.ajax({
			url: site_url+'admin_advance/read_text_file',
			type: 'POST',
			data: queryString,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){            	
				$("#bulk_email").val(response);                	
			}
		});

	});
	
	
	$j("document").ready(function(){
		$("#unique_email_download_div").html('');
		var base_url="<?php echo base_url(); ?>";
		
		$("#new_search_button").on('click',function(){
			
			var emails=$("#bulk_email").val();
			
			if(emails==''){
				alert("please enter emails");
				return false;
			}
			
			$("#modal_unique_email_result").modal();
			
			$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
			
			$.ajax({
				url:base_url+'scrape/email_unique_maker',
				type:'POST',
				data:{emails:emails},
				success:function(response){
					
					$("#unique_email_download_div").html('<a href="<?php echo base_url()."download/unique_email/unique_email_{$this->user_id}_{$this->download_id}.txt" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download Unique Email</b></a>');
					$("#success_msg").html('<center><h3> '+response+' </h3></center>');
					
				}
				
			});
			
			
		});
		
	});
	
	
	

	</script> 