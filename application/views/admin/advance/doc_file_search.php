<?php $this->load->view("include/upload_js"); ?>	


 <?php $this->load->view('admin/theme/message'); ?>

	<!-- Main content -->
	<section class="content-header">
		<!-- <h1 class = 'text-info'> Text File Search</h1> -->
	</section>
	<section class="content">  
		<div class="row">	
			<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3" style="background:#3399FF;margin-bottom:0px;margin-top:40px;padding-left:15px;padding-top:15px;">
				<h3 style="margin-top:0px !important;color:white"><i class="fa fa-binoculars"></i> Doc/Docx/PDF File Search Panel (Multiple)</h3>
			</div>	
			<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3" style="margin-top:0px;background-color:#EEE;padding:30px 10px;">
				<form enctype="multipart/form-data" method="post" class="form-inline" id="new_search_form">
					<div class="form-group">
						<input type="file" name="myfile[]" id="myfile" multiple style="display:inline;" />
						<button class='btn btn-success' id="pull_data" style="display:inline;"><i class="fa fa-hourglass-start"></i> Start Searching</button> 						
					</div>
				</form>	
			</div>
		</div>   
	</section>



<!-- Start modal for response-->
<div id="modal_text_email_result" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title">Download Status</h4>
			</div><br/>


			<div id="new_search_view_body" class="modal-body">
				
			
				<div class="row"> 				
					<div class="col-xs-12" class="text-center" id="success_msg"></div>     
				</div> 
				<div class="row">
					<div class="col-xs-12 wow fadeInRight">		  
						<div class="loginmodal-container">
				
							<div id="email_csv_download_div" class="col-xs-6 text-center">				
							</div>

							<div id="email_text_download_div" class="col-xs-6 text-center">				
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


<script>

	$j("document").ready(function(){
   	

	$("#url_wise_download_btn").click(function(){
		var url = "<?php echo site_url('admin/url_wise_download');?>";
        var rows = $j("#tt").datagrid("getSelections");
        var info=JSON.stringify(rows); 
        if(rows == '')
        {
            alert("You haven't select any field");
            return false;
        }
        $.ajax({
            type:'POST',
            url:url,
            data:{info:info},
            success:function(response){
                if (response != '') {
                    
                    var redirect_url = "<?php echo site_url('home/download_page_loader');?>";                   
                    window.open(redirect_url);
                } else {
                    alert("Something went wrong, please try again.");
                }
            }
        });
	});

});

$("#pull_data").click(function(e){
		e.preventDefault();
		var site_url="<?php echo site_url();?>";  
		var queryString = new FormData($("#new_search_form")[0]);
		var base_url="<?php echo site_url(); ?>";	
		
			$("#email_csv_download_div").html('');
			$("#email_text_download_div").html('');
			$("#modal_text_email_result").modal();
			$("#success_msg").html('<img class="center-block" height="40px" width="100px" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
	
		
		$.ajax({
            url: site_url+'admin_advance/doc_read_file',
            type: 'POST',
            data: queryString,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){ 
            			$("#success_msg").html(response);
               	 	if(response != "Something is Wrong! Please select a file and try again."){
						$("#email_csv_download_div").html('<a href="<?php echo base_url()."download/text_file/text_email_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download CSV File</b></a>');
               	 		
						$("#email_text_download_div").html('<a href="<?php echo base_url()."download/text_file/text_email_{$this->user_id}_{$this->download_id}.txt" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download Text File</b></a>');
               	 	}
               	 	
               	 	}			
            
        });

	});


</script>