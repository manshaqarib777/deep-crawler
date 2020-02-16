<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
		<div class='text-center'><h4 class="text-info"><strong>Email Validation Check</strong></h4></div>
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
<div id="modal_valid_email_result" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title">Valid Email Check Result</h4>
			</div><br/>


			<div id="new_search_view_body" class="modal-body">
				
			
				<div class="row"> 				
					<div class="col-xs-12" class="text-center" id="success_msg"></div>     
				</div> 
				<div class="row">
					<div class="col-xs-9 col-xs-offset-2 col-sm-9 col-sm-offset-2 col-md-9 col-md-offset-2 col-lg-9 col-lg-offset-2 wow fadeInRight">		  
						<div class="loginmodal-container">
				
							<div id="email_download_div" style="display:inline">
				
							</div>
							
							<div id="valid_email_download_div" style="display:inline">
				
							</div>
							                 
						</div>
					</div>
					<div class="col-xs-12" class="text-center" id="success_msg"></div>
					<div class="col-xs-12" class="text-center" id="progress_msg">
						<span id="progress_msg_text"></span>
						<div class="progress" style="display: none;" id="progress_bar_con"> 
							<div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped"><span>1%</span></div> 
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

	function get_bulk_progress()
	{
		var base_url="<?php echo base_url(); ?>";			
		$.ajax({
			url:base_url+'scrape/email_validator_progress_count',
			type:'POST',
			dataType:'json',
			success:function(response){
				var search_complete=response.search_complete;
				var search_total=response.search_total;
				var latest_record=response.latest_record;
				$("#progress_msg_text").html(search_complete +" / "+ search_total +" Completed");
				var width=(search_complete*100)/search_total;
				width=Math.round(width);					
				var width_per=width+"%";
				if(width<3)
				{
					$("#progress_bar_con div").css("width","3%");
					$("#progress_bar_con div").attr("aria-valuenow","3");
					$("#progress_bar_con div span").html("1%");
				}
				else
				{
					$("#progress_bar_con div").css("width",width_per);
					$("#progress_bar_con div").attr("aria-valuenow",width);
					$("#progress_bar_con div span").html(width_per);
				}

				if(width==100) 
				{
					$("#success_msg").html('<center><h3 style="color:olive;">Completed</h3></center>');					
					clearInterval(interval);
				}
				
				
			}
		});
		
	}
	
	var interval=""; 
	
	
	$j("document").ready(function(){
		
		var base_url="<?php echo base_url(); ?>";
		
		$("#new_search_button").on('click',function(){
			$("#email_download_div").html('');
			$("#valid_email_download_div").html('');
			var emails=$("#bulk_email").val();
			
			if(emails==''){
				alert("please enter emails");
				return false;
			}
			
			$("#modal_valid_email_result").modal();
			
			$("#email_download_div").html("");
			$("#progress_bar_con div").css("width","3%");
			$("#progress_bar_con div").attr("aria-valuenow","3");
			$("#progress_bar_con div span").html("1%");
			$("#progress_msg_text").html("");				
			$("#progress_bar_con").show();				
			interval=setInterval(get_bulk_progress, 10000);
			
			$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Please Wait..."><br/>');
			
			$.ajax({
				url:base_url+'scrape/email_validator',
				type:'POST',
				data:{emails:emails},
				success:function(response){
					
						$("#progress_bar_con div").css("width","100%");
						$("#progress_bar_con div").attr("aria-valuenow","100");
						$("#progress_bar_con div span").html("100%");
						$("#progress_msg_text").html("Completed");
						// $("#success_msg").html('<center><h3 style="color:olive;">Search Completed</h3></center>');
						
						$("#email_download_div").html('<a href="<?php echo base_url()."download/email_validator/email_validator_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download Info</b></a>');
						
						$("#valid_email_download_div").html('<a href="<?php echo base_url()."download/email_validator/email_validator_{$this->user_id}_{$this->download_id}.txt" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download Valid Email</b></a>');
						
						$("#success_msg").html('<center><h3> '+response+' </h3></center>');
						clearInterval(interval);					
					
					
				}
				
			});
			
			
		});
		
	});
	

</script>