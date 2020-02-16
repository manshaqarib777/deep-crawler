	<?php $this->load->view('admin/theme/message'); ?>

	<!-- Main content -->
	<section class="content-header">
		<h1 class = 'text-info'> Whois Search</h1>
	</section>
	<section class="content">  
		<div class="row" >
			<div class="col-xs-12">
				<div class="grid_container" style="width:100%; min-height:650px;">
					<table 
					id="tt"  
					class="easyui-datagrid" 
					url="<?php echo base_url()."admin_advance/whois_search_data"; ?>" 

					pagination="true" 
					rownumbers="true" 
					toolbar="#tb" 
					pageSize="10" 
					pageList="[5,10,20,50,100]"  
					fit= "true" 
					fitColumns= "true" 
					nowrap= "true" 
					view= "detailview"
					idField="id"
					>
					
					<!-- url is the link to controller function to load grid data -->					

					<thead>
						<tr>

							<th field="id"  checkbox="true"></th>
							<th field="domain_name" sortable="true">Domain</th>
							<th field="admin_email" sortable="true">Admin Email</th>
							<th field="tech_email" sortable="true">Tech. Email</th>
							<th field="admin_name" sortable="true">Admin Name</th>
							<th field="admin_phone" sortable="true">Admin Phone</th>
							<th field="registrant_name" sortable="true">Registrant Name</th>
							<th field="registrant_phone" sortable="true">Registrant Phone</th>
							<th field="namve_servers" sortable="true" >Name Server</th>
							<th field="sponsor" sortable="true">Sponsor</th>                      
							<th field="scraped_time" sortable="true">Scraped Date & Time</th>                      
							<th field="created_at" sortable="true">Created At</th>                      
							<th field="changed_at" sortable="true">Changed At</th>                      
							<th field="expire_at" sortable="true">Expire Date</th>                      
							<th field="is_registered" formatter="registration_status">Is Registered</th>
						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

				<button type="button" class="btn btn-info" id = "new_search_modal_open"><i class="fa fa-search"></i> New Search</button>
				<button type="button" class="btn btn-warning pull-right" id = "whois_search_download_btn"><i class="fa fa-cloud-download"></i> Download</button>
				<button type="button" class="btn btn-danger pull-right" id = "who_is_delete_btn" style = 'margin-right:10px'><i class="fa fa-times"></i> Delete</button>
				  

				<form class="form-inline" style="margin-top:20px">

					<div class="form-group">
						<input id="domain_name" name="domain_name" class="form-control" size="20" placeholder="Domain Name">
					</div>   

					<div class="form-group">
						<input id="namve_servers" name="namve_servers" class="form-control" size="20" placeholder="Name Server">
					</div>

					<div class="form-group">
						<input id="sponsor" name="sponsor" class="form-control" size="20" placeholder="Sponsor">
					</div>

					<div class="form-group">
						<input id="from_date" name="from_date" class="form-control datepicker" size="20" placeholder="Scraped From Date">
					</div>

					<div class="form-group">
						<input id="to_date" name="to_date" class="form-control  datepicker" size="20" placeholder="Scraped To Date">
					</div>                    

					<button class='btn btn-info'  onclick="doSearch(event)">Search</button>     

				</form> 

			</div>        
		</div>
	</div>   
</section>

<!-- Start modal for new search. -->
<div id="modal_new_search" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content modal-lg">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> Whois Search</h4>
			</div><br/>


			<div id="new_search_view_body" class="modal-body">
				<form enctype="multipart/form-data" method="post" class="form-inline" id="new_search_form" style="margin-bottom:10px">
					<div class="row">						
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<textarea id="whois_bulk_file" style="width:100%;padding:10px;" rows="7" placeholder="Put your domain names or upload text/csv file - comma / in new line separated"></textarea>					
							<br/><span style="margin-left:5%">OR</span><br/> 
						</div>
					</div>

					<div class="row">						
						<div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<input type="file" name="whois_upload" id="whois_upload" multiple/>
						</div>
						<div class="form-group col-xs-12 col-sm-12 col-md-7 col-lg-7 clearfix">						
							<button class='btn btn-success'  id = "pull_data"><i class="fa fa-upload"></i> Upload File</button>     
							<button type="button"  id="new_search_button" class="btn btn-info pull-right"><i class="fa fa-search"></i> Start Searching</button>   
						</div>
					</div>

				</form>
				
			
				<div class="row"> 
					
					<div class="col-xs-12" class="text-center" id="success_msg"></div>

					<div class="col-xs-12" class="text-center" id="progress_msg">
						<span id="progress_msg_text"></span>
						<div class="progress" style="display: none;" id="progress_bar_con"> 
							<div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped"><span>1%</span></div> 
						</div>
					</div>     

					<div class="col-sm-12 col-sm-12 col-lg-12 col-md-12 wow fadeInRight">		  
						<div class="loginmodal-container">
							
							<div id="email_download_div" class="text-center">
								
							</div>
							
							<ol id="email_list">
								
							</ol>                     
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

	$j(function() {
		$( ".datepicker" ).datepicker();
	});
	

	function registration_status(value, row, index)
	{
		var status = row.is_registered; 
		var str = '';   	

		if(status=='yes'){
			str = str+"<label class='label label-success'>Yes</label>"; 
		}
		
		else {
			str = str+"<label class='label label-danger'>No</label>";
		}
		

		return str;

	} 

	$("#new_search_modal_open").click(function(){
		$("#modal_new_search").modal();

	});

	$("#whois_search_download_btn").click(function(){
		var base_url="<?php echo base_url(); ?>";
		$('#whois_search_download_btn').html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');
		var url = "<?php echo site_url('admin_advance/whois_download');?>";
		var rows = $j("#tt").datagrid("getSelections");
		var info=JSON.stringify(rows); 
		if(rows == '')
		{
			$('#whois_search_download_btn').html('<i class="fa fa-cloud-download"></i> Download');
			alert("You haven't select any field");
			return false;
		}
		$.ajax({
			type:'POST',
			url:url,
			data:{info:info},
			success:function(response){
				if (response != '') {
					$('#whois_search_download_btn').html('<i class="fa fa-cloud-download"></i> Download');
					$('#modal_for_download_whois').modal();
					
				} else {
					alert("Something went wrong, please try again.");
				}
			}
		});
	});

	//section for Delete
	$("#who_is_delete_btn").click(function(){
		var result = confirm("Want to delete?");

		if(result == '1'){
			var base_url="<?php echo base_url(); ?>";		
			var url = "<?php echo site_url('admin_advance/who_is_delete');?>";
	        var rows = $j("#tt").datagrid("getSelections");
	        var info=JSON.stringify(rows); 

	         /***For deleteing rows ***/
			var rowsLength = rows.length;	
			var rr = [];
			for (i = 0; i < rowsLength; i++) {
			     rr.push(rows[i]);
			}
			/****Sengment end for deleting rows*****/
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

	            	/***For deleteing rows ***/
					
					$.map(rr, function(row){
						var index = $j("#tt").datagrid('getRowIndex', row);
						$j("#tt").datagrid('deleteRow', index);
					});
					
					/****Sengment end for deleting rows*****/ 
	            	$j('#tt').datagrid('reload'); 	              
	            }
	        });


		}//end of if.			

	});

	//End section for Delete.

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
				$("#whois_bulk_file").val(response);
			}
		});

	});

	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			domain_name     :     $j('#domain_name').val(), 
			namve_servers   :     $j('#namve_servers').val(),              
			sponsor  		:     $j('#sponsor').val(),		             
			from_date  		:     $j('#from_date').val(),    
			to_date    		:     $j('#to_date').val(),         
			is_searched		:      1
		});


	}



	function get_bulk_progress()
	{
		var base_url="<?php echo base_url(); ?>";			
		$.ajax({
			url:base_url+'scrape/whois_search_progress_count',
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
					$("#success_msg").html('<center><h3 style="color:olive;">Search Completed</h3></center>');					
					clearInterval(interval);
				}
				
				
			}
		});
		
	}
	
	var interval=""; 

	
	$j("document").ready(function(){
		
		var base_url="<?php echo base_url(); ?>";
		
		$("#new_search_button").on('click',function(){
			
			var urls=$("#whois_bulk_file").val();
			
			if(urls==''){
				alert("please enter url");
				return false;
			}
			
			$("#email_download_div").html("");
			$("#progress_bar_con div").css("width","3%");
			$("#progress_bar_con div").attr("aria-valuenow","3");
			$("#progress_bar_con div span").html("1%");
			$("#progress_msg_text").html("");				
			$("#progress_bar_con").show();				
			interval=setInterval(get_bulk_progress, 10000);
			
			$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
			
			$.ajax({
				url:base_url+'scrape/whois_search',
				type:'POST',
				data:{urls:urls},
				success:function(response){

					if(response == 2){
						var mseg = '<div class="alert alert-danger"><?php echo $this->lang->line("sorry, your bulk limit is exceeded for this module.")?>'+"<a href='"+"<?php echo site_url('payment/usage_history'); ?>'>"+"<?php echo $this->lang->line('click here to see usage log') ?>"+"</a></div>";

						$("#progress_bar_con div").css("width","100%");
						$("#progress_bar_con div").attr("aria-valuenow","100");
						$("#progress_bar_con div span").html("100%");
						$("#progress_msg_text").html("Completed");
						
						$("#success_msg").html(mseg);
						clearInterval(interval);
					}
					else if(response == 3){
						var mseg = '<div class="alert alert-danger"><?php echo $this->lang->line("sorry, your limit is exceeded for this module.")?>'+"<a href='"+"<?php echo site_url('payment/usage_history'); ?>'>"+"<?php echo $this->lang->line('click here to see usage log') ?>"+"</a></div>";

						$("#progress_bar_con div").css("width","100%");
						$("#progress_bar_con div").attr("aria-valuenow","100");
						$("#progress_bar_con div span").html("100%");
						$("#progress_msg_text").html("Completed");

						$("#success_msg").html(mseg);
						clearInterval(interval);
					}
					else {

						$("#success_msg").html('<center><h3 style="color:olive;">Search Completed</h3></center>');

						// $("#email_download_div").html(response);
						$("#progress_bar_con div").css("width","100%");
						$("#progress_bar_con div").attr("aria-valuenow","100");
						$("#progress_bar_con div span").html("100%");
						$("#progress_msg_text").html("Completed");
						
						$("#email_download_div").html('<a style="margin: 0px auto;" href="<?php echo base_url()."download/whois/domain_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download Domain Info</b></a>');
						
						$j("#tt").datagrid('reload');
						clearInterval(interval);
					}
						
					
				}
				
			});
			
			
		});
		
	});
	
	
</script>

<!-- Modal for download -->
<div id="modal_for_download_whois" class="modal fade">
	<div class="modal-dialog" style="width:65%;">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="" class="modal-title">Download Status</h4>
			</div>

			<div class="modal-body">
				<style>
				.box
				{
					border:1px solid #ccc;	
					margin: 0 auto;
					text-align: center;
					margin-top:10%;
					padding-bottom: 20px;
					background-color: #fffddd;
					color:#000;
				}
				</style>
				<!-- <div class="container"> -->
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
							<div class="box">
							<h2>Your file is ready to download</h2>
							<?php 
								echo '<i class="fa fa-2x fa-thumbs-o-up"style="color:black"></i><br><br>';
								echo "<a href='".base_url()."download/report/whois_email_{$this->user_id}_{$this->download_id}.csv' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";							
							?>
							</div>		
							
						</div>
					</div>
				<!-- </div>	 -->
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>