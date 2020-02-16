	<?php $this->load->view('admin/theme/message'); ?>

	<!-- Main content -->
	<section class="content-header">
		<h1 class = 'text-info'> Search Engine Search</h1>
	</section>
	<section class="content">  
		<div class="row" >
			<div class="col-xs-12">
				<div class="grid_container" style="width:100%; min-height:550px;">
					<table 
					id="tt"  
					class="easyui-datagrid" 
					url="<?php echo base_url()."admin/searchengine_search_data"; ?>" 

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
							<th field="search_keyword" sortable="true">Keyword</th>
							<th field="search_engine_name" sortable='true'>Searcn Engine</th>
							<th field="search_in" sortable="true" >Social Network</th>
							<th field="country" sortable="true" >Country</th>
							<th field="language" sortable="true" >Language</th>
							<th field="last_scraped_time" sortable="true">Last Scraped Time</th>                      
							<th field="found_email" sortable="true">Found Email</th>                      
							<th field="view" formatter="view_details">Details</th>                      

						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

				<button type="button" class="btn btn-info" id = "new_search_modal_open"><i class="fa fa-search"></i> New Search</button>
				<button type="button" class="btn btn-warning pull-right" id = "searchengine_wise_download_btn"><i class="fa fa-cloud-download"></i> Download</button>
				<button type="button" class="btn btn-danger pull-right" id = "search_engine_delete_btn" style = 'margin-right:10px'><i class="fa fa-times"></i> Delete</button>
				 

				<form class="form-inline" style="margin-top:20px">
					<div class="form-group">
						<input id="keyword" name="keyword" class="form-control" size="20" placeholder="Keyword">
					</div>					

					<div class="form-group">					
						<?php 
						$searh_engine['']="Search Engine";
						echo form_dropdown('searchengine',$searh_engine,set_value('searchengine'),'class="form-control" id="searchengine"');  
						?>

					</div>

					<div class="form-group">						
						<?php 
						$social_network['']="Social Network";
						echo form_dropdown('social_network',$social_network,set_value('social_network'),'class="form-control" id="social_network"');  
						?>
					</div>   

					<div class="form-group">
						<input id="from_date" name="from_date" class="form-control datepicker" size="20" placeholder="From Date">
					</div>

					<div class="form-group">
						<input id="to_date" name="to_date" class="form-control  datepicker" size="20" placeholder="To Date">
					</div>                    

					<button class='btn btn-info'  onclick="doSearch(event)">Search</button>     

				</form> 

			</div>        
		</div>
	</div>   
</section>


<!-- Start modal for new search. -->
<div id="modal_new_search" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> Search in Search Engine</h4>
			</div><br/>

	<div id="new_search_view_body" class="modal-body">
		
			<form class="form-inline" style="margin-top:60px margin-left:10px">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<input id="scrape_keyword" style="width:100% !important;" name="scrape_keyword" class="form-control" placeholder="Keyword">
						</div> 
					</div>
					<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<?php 
							$searh_engine['']="Search Engine";
							echo form_dropdown('searchengine',$searh_engine,set_value('searchengine'),'class="form-control" id="scrape_searchengine" style="width:100% !important;"');  
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<?php 
							$social_network['']="Social Network";
							$social_network['others']="Others";
							echo form_dropdown('social_network',$social_network,set_value('social_network'),'class="form-control" id="scrape_social_network" style="width:100% !important;"');  
							?>
							<br/><input type="text" placeholder="Social Network URL" id="scrape_social_network_custom" class="form-control" style="display:none;width:100% !important;" >

						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<?php 
							$email_provider['']="Email Provider";
							$email_provider['others']="Others";
							echo form_dropdown('email_provider',$email_provider,set_value('email_provider'),'class="form-control" id="check_email_provider" style="width:100% !important;"');  
							?>
							<br/><input type="text" placeholder="Email Provider URL" id="check_email_provider_custom" class="form-control pull-right" style="display:none;width:100% !important;">
						</div>
					</div>
				</div>					

				<div class="row" style="margin-top:10px;">
					<!-- have to change here -->
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<?php 
							$country_name['']="Any Location";
							echo form_dropdown('country_name',$country_name,set_value('country_name'),'class="form-control" style="width:100% !important;" id="country_name" style="width:100% !important;"');  
							?>							
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<?php 
							$language_name['']="Any Language";
							echo form_dropdown('language_name',$language_name,set_value('language_name'),'class="form-control" id="language_name" style="width:100% !important;"');  
							?>
							
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group" style="width:100% !important;">
							<select name="page_range" id="page_range" class="form-control" style="width:100% !important;">
								<option value="0_5" >Search Range: Page 1-5</option>
								<option value="5_10">Search Range: Page 6-10</option>
								<option value="0_10">Search Range: Page 1-10</option>
							</select>							
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group">						
							<label class="checkbox-inline" ><input type="checkbox" id = "proxy_checkbox" value = "checked_proxy" ><span style = "font-size:15px"> Use Proxy Server </span></label>
						</div>
					</div>
					
					<div class="col-xs-12">
						<div class="form-group" style="width:100% !important;">
							<textarea  id="proxy_server" name="proxy_server" class="form-control" rows="4" placeholder="Put proxy IP with comma separate or every IP in a new line." style ="display:none;width:100% !important;margin-top:10px;margin-bottom:10px;padding:15px;"></textarea>
						</div>
					</div>

					<br/><br/>
					<div class="col-xs-12 clearfix text-center">
						<button type="button"  id="new_search_button" class="btn btn-info"><i class="fa fa-hourglass-start"></i> Start Scraping</button>
						<button onclick="kill()"  type="button" class="btn btn-warning"><i class="fa fa-stop"></i> Stop Scraping</button>
					</div>
				</div>

				

		</form>


		<div class="row">
		<div class="col-xs-12" class="text-center" id="success_msg1"></div>			
			<div class="col-xs-12" class="text-center" id="progress_msg">
				<span id="progress_msg_text"></span>
				<div class="progress" style="display: none;" id="progress_bar_con"> 
					<div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped"><span>1%</span></div> 
				</div>
			</div> 
		</div>
			<!-- <div id="success_msg"></div> -->
			
			<!--Load the scrapping script here .-->
			<iframe style="display:none;"  id="loadarea"></iframe>
			
			
			
			<!--	Displaying progress of scrapped url and email-->
			
			 <div class="row">  
            	<br/><br/><div class="col-xs-12 text-center" id="success_msg" style="margin-top:20px;"></div> 				
				
             	<div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 wow fadeInRight">		 
   				 <h3 class="font_family text-center wow fadeInDown start_quote" style="margin:0;padding:10px 0;display:inline">Found Emails</h3>
				 <div id="email_download_div" style="display:inline;margin-left:5%;"></div>
   				 <hr/>  
                  <div class="loginmodal-container">
				  
				   <!-- <div id="email_download_div">
 		
				  </div> -->
				  			    
					<ol id="email_list">
						
					</ol> 
					
             	  </div>
             	</div>		
				
					
            </div> 

	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</div>
</div>
</div>
<!-- End modal for new search. -->

<!-- Start modal for details report. -->
<div id="modal_domain_detail" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="domain_details_title" class="modal-title">Search Engine Search Details</h4>
			</div>

			<div class="clearfix" style="margin-top:10px; margin-right:10px">				
				<button type="button" class="btn btn-warning pull-right" id="download_details"><i class="fa fa-cloud-download"></i> Download</button>
			</div>

			<input id ='hidden_ulr_id' style= "display:none"/>

			<div id="domain_view_body" class="modal-body">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- End modal for details report. -->


<script>

	$j(function() {
		$( ".datepicker" ).datepicker();
	});

	//Function for URL wise all email Download*********************
	$("#download_details").click(function(){
		var base_url="<?php echo base_url(); ?>";
		$("#download_details").html('<img class="center-block" height="30px" width="100px" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
		var download_details_se_url = $("#hidden_ulr_id").val();
		var url_download = "<?php echo site_url('admin/search_engine_search_detailw_download'); ?>";
		$.ajax({
			url:url_download,
			type:'POST',
			data:{download_details_se_url:download_details_se_url},
			success:function(response){
				if (response != '') 
					{
						$('#download_details').html('<i class="fa fa-cloud-download"></i> Download');
						$('#modal_domain_detail').modal('hide');
						$('#search_engine_search_detail_download').modal();
					} 

				else 
					{
						alert("Something went wrong, please try again.");
					}				
			}
		});
	});

	//End of Function for URL wise all email Download*****************

	//section for Delete
	$("#search_engine_delete_btn").click(function(){
		var result = confirm("Want to delete?");

		if(result == '1'){
			var base_url="<?php echo base_url(); ?>";		
			var url = "<?php echo site_url('admin/search_engine_delete');?>";
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

	function availability_label(value, row, index)
	{
		var status = row.is_available; 
		var str = '';   	

		if(status == "1")
			str = str+"<label class='label label-success'>Active</label>";     	

		if(status == '0')
			str = str+"<label class='label label-danger'>Down</label>";

		return str;

	} 

	function view_details(value, row, index)
	{
		var search_engine_url_id = row.search_engine_url_id;
		var str="";

		str="<a title='Details' style='cursor:pointer' onclick='view_details_report(event,"+search_engine_url_id+")' >"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/magnifier.png");?>" alt="View">'+"</a>";        

		return str;
	}

	//Function for Display URL wise all emails in modal.************************

	function view_details_report(e,search_engine_url_id)
	{
		var details_url = "<?php echo site_url('admin/searchengine_wise_details'); ?>";
		$.ajax({
			url:details_url,
			type:'POST',
			data:{search_engine_url_id:search_engine_url_id},
			success:function(response){
				$("#modal_domain_detail").modal();
				$("#domain_view_body").html(response);
				$("#hidden_ulr_id").val(search_engine_url_id);
			}
		});
	}

	//End of Function for Display URL wise all emails in modal.*******************

	$("#new_search_modal_open").click(function(){
		$("#modal_new_search").modal();

	});

	$("#proxy_checkbox").click(function(){
	
		if($(this).is(":checked")){
			$("#proxy_server").show(400);
		}
		else{
			$("#proxy_server").hide(400);
		}

	});

	$("#searchengine_wise_download_btn").click(function(){
		var base_url="<?php echo base_url(); ?>";
		$('#searchengine_wise_download_btn').html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');

		var url = "<?php echo site_url('admin/searchengine_wise_download');?>";
		var rows = $j("#tt").datagrid("getSelections");
		var info=JSON.stringify(rows); 
		if(rows == '')
		{
			$('#searchengine_wise_download_btn').html('<i class="fa fa-cloud-download"></i> Download');
			alert("You haven't select any field");
			return false;
		}
		$.ajax({
			type:'POST',
			url:url,
			data:{info:info},
			success:function(response){
				if (response != '') {
					$('#searchengine_wise_download_btn').html('<i class="fa fa-cloud-download"></i> Download');
					$('#modal_for_download_searchengine').modal();
				} else {
					alert("Something went wrong, please try again.");
				}
			}
		});
	});

	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			keyword        	:     $j('#keyword').val(),               
			user_name       :     $j('#user_name').val(),               
			searchengine    :     $j('#searchengine').val(),               
			social_network  :     $j('#social_network').val(),               
			from_date  		:     $j('#from_date').val(),    
			to_date    		:     $j('#to_date').val(),         
			is_searched		:      1
		});


	} 

	function get_bulk_progress()
	{
		var base_url="<?php echo base_url(); ?>";			
		$.ajax({
			url:base_url+'scrape/search_engine_search_progress_count',
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
					$("#success_msg1").html('<center><h3 style="color:olive;">Completed</h3></center>');					
					clearInterval(interval);
				}
				
				
			}
		});
		
	}
	
	var interval="";    
	
	
	
	
	
	$j("document").ready(function(){
		
		var base_url="<?php echo base_url(); ?>";

		$("#scrape_social_network").on('click',function(){	
			if($(this).val()=="others") $("#scrape_social_network_custom").show();				
			else $("#scrape_social_network_custom").hide();			
		});

		$("#check_email_provider").on('click',function(){	
			if($(this).val()=="others") $("#check_email_provider_custom").show();				
			else $("#check_email_provider_custom").hide();			
		});
		
		
		$("#new_search_button").on('click',function(){	
			
			var keywrod=$("#scrape_keyword").val();
			var search_engine=$("#scrape_searchengine").val();
			
			var value1=$("#scrape_social_network").val();
			var value2=$("#check_email_provider").val();

			var language = $("#language_name").val();
			var country = $("#country_name").val();
			
			var page_range=$("#page_range").val();

			if(language == '') language = "all";
			if(country == '') country = "all";
			


			if(value1=="others") var social_network=$("#scrape_social_network_custom").val();
			else var social_network=$("#scrape_social_network").val();

			if(value2=="others") var checked_email_providers_string=$('#check_email_provider_custom').val();
			else var checked_email_providers_string=$('#check_email_provider').val();

				
			keywrod=escape(keywrod);
			search_engine=escape(search_engine);
			social_network=escape(social_network);
			checked_email_providers_string=escape(checked_email_providers_string);
			page_range=escape(page_range);
			
			if ($('#proxy_checkbox').is(':checked')) {
				var proxy_address=$("#proxy_server").val();
			}
			else{
				var proxy_address='';
			}
			
			if(proxy_address=='')
				proxy_address="no";
			
			if(keywrod=='' || search_engine=='' || social_network=='' || checked_email_providers_string==''){
				alert("Please enter keyword and select above fields");
				return false;
			}
			
			proxy_address=escape(proxy_address);
			
			checked_email_providers_string = checked_email_providers_string.replace(/\//gi,'____');
			checked_email_providers_string = escape(checked_email_providers_string);
			checked_email_providers_string=encodeURIComponent(checked_email_providers_string);
			social_network = social_network.replace(/\//gi,'____');
			
			
			
			
			$("#progress_bar_con div").css("width","3%");
			$("#progress_bar_con div").attr("aria-valuenow","3");
			$("#progress_bar_con div span").html("1%");
			$("#progress_msg_text").html("");				
			$("#progress_bar_con").show();				
			interval=setInterval(get_bulk_progress, 10000);
			
			$("#success_msg1").html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
			
			var url=base_url+"scrape/scrape_searchengine/"+keywrod+"/"+search_engine+"/"+social_network+"/"+proxy_address+"/"+checked_email_providers_string+"/"+country+"/"+language+"/"+page_range;
			
			if(keywrod!=''){
				document.getElementById('loadarea').src = url;
				
				/*$("#progress_bar_con div").css("width","100%");
				$("#progress_bar_con div").attr("aria-valuenow","100");
				$("#progress_bar_con div span").html("100%");
				$("#progress_msg_text").html("Completed");
				$("#success_msg1").html('<center><h3 style="color:olive;">Search Completed</h3></center>');*/
			}
			
		
		});
	
	});
	
	
	function kill(){
		 document.getElementById('loadarea').src = '';
		 $("#success_msg").html('<h3 class="text-center" style="color:red;">Scraping Stopped</h3>');
		$("#email_download_div").html('<a href="<?php echo base_url()."download/search_engine/email_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b>Download EMail</b></a>');
						  	 
	}
	
	
</script>

<!-- Modal for download -->
<div id="modal_for_download_searchengine" class="modal fade">
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
								echo "<a href='".base_url()."download/report/search_engine_wise_email_{$this->user_id}_{$this->download_id}.csv' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";							
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


<!-- Modal for detail download -->
<div id="search_engine_search_detail_download" class="modal fade">
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
								echo "<a href='".base_url()."download/report/search_engine_details_email_{$this->user_id}_{$this->download_id}.csv' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";							
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