	<?php $this->load->view('admin/theme/message'); ?>

	<!-- Main content -->
	<section class="content-header">
		<h1 class = 'text-info'> Website Search</h1>
	</section>
	<section class="content">  
		<div class="row" >
			<div class="col-xs-12">
				<div class="grid_container" style="width:100%; min-height:650px;">
					<table 
					id="tt"  
					class="easyui-datagrid" 
					url="<?php echo base_url()."admin/website_search_data"; ?>" 

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
							<th field="domain_name" sortable="true">Domain Name</th>
							<!-- <th field="is_available" formatter='availability_label' >Availability</th> -->
							<!-- <th field="name" sortable="true">User Name</th> -->
							<th field="last_scraped_time" sortable="true" >Last Scraped Time</th>
							<th field="found_email" sortable="true">Found Email</th>                      
							<th field="view" formatter="view_details">Details</th>                      

						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

				<button type="button" class="btn btn-info" id = "new_search_modal_open"><i class="fa fa-search"></i> New Search</button>
				<button type="button" class="btn btn-warning pull-right" id = "website_wise_download_btn"><i class="fa fa-cloud-download"></i> Download</button>
				<button type="button" class="btn btn-danger pull-right" id = "website_wise_delete_btn" style = 'margin-right:10px'><i class="fa fa-times"></i> Delete</button>


				<form class="form-inline" style="margin-top:20px">
					<div class="form-group">
						<input id="domain_name" name="domain_name" class="form-control" size="20" placeholder="Domain Name">
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

<!-- Start modal for website wise details report. -->

<div id="modal_domain_detail" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="domain_details_title" class="modal-title">Website Scraping Details</h4>
			</div>

			<div class="clearfix" style="margin-top:5px; margin-right:10px;margin-bottom:0px;">				
				<button type="button" class="btn btn-warning pull-right" id="download_details"><i class="fa fa-cloud-download"></i> Download</button>
			</div>

			<input id ='hidden_domain_id' style= "display:none"/>

			<div id="domain_view_body" class="modal-body">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- End modal for website wise details report. -->

<!-- Start modal for new search. -->
<div id="modal_new_search" class="modal fade">
	<div class="modal-dialog" style="width:70%;">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> Search in website</h4>
			</div><br/>

			<div id="new_search_view_body" class="modal-body">
			
				<div class="row" style="margin-bottom:10px;">
					<form class="form-inline">
						<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<input id="website_address" name="website_address" class="form-control" placeholder="Website Address" style="width:100%;">
						</div>  

						<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3">						
							<label class="checkbox-inline"><input type="checkbox" id = "proxy_checkbox" value = "checked_proxy"> Use Proxy Server </label>
						</div>

						<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3" id="proxy_server_container" style="display:none;"> 
							<input id="proxy_server" name="proxy_server" class="form-control" placeholder="Proxy Server" style="width:100%;">
						</div>  

						<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-3 clearfix">							
							<button type="button"  id="new_search_button" class="btn btn-info">Search</button>
							<a onclick="kill()"  type="button" class="btn btn-warning pull-right" style="width:60%;"><i class="fa fa-stop"></i> Stop Scraping</a>
						</div>


					</form>
				</div>
					

			<!--Load the scrapping script here .-->
			<iframe style="display:none"  id="loadarea"></iframe>
			
			
			<!--	Displaying progress of scrapped url and email-->
			
			 <div class="row">  
            	<div class="col-xs-12" class="text-center" id="success_msg" style="margin:10px 0px;"></div>  
            	<div class="col-xs-12" class="text-center" id = 'success_num' style="margin:10px 0px; display: none;"><span class= 'lead'>Scrapped URL Number : </span><span id="success_url" class = 'lead'>0</span></div>           
            	<div class="col-sm-12 col-sm-12 col-lg-6 col-md-6 wow fadeInRight">		 
   				 <h3 class="font_family wow fadeInDown start_quote" style="margin:0;padding-top:10px;padding-bottom:0px;">
   				 	<div id="url_download_div" style="display:inline;"></div> &nbsp Scraped URLs
   				 </h3><hr/> 
                  <div class="loginmodal-container">
				  								    
					
					<ol id="url_list">						
					</ol>  

             	  </div>
             	</div>	

             	<div class="col-sm-12 col-sm-12 col-lg-6 col-md-6 wow fadeInRight">		 
   				 <h3 class="font_family wow fadeInDown start_quote" style="margin:0;padding-top:10px;padding-bottom:0px;">
   				 	Scraped Emails &nbsp <div id="email_download_div" style="display:inline;"></div>
   				 </h3><hr/>
                  <div class="loginmodal-container">
				  
				  		    
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

<script>

	$j(function() {
		$( ".datepicker" ).datepicker();
	});

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
		var domain_id = row.domain_id;        


		var str="";

		str="<a title='Details' style='cursor:pointer' onclick='view_details_report(event,"+domain_id+")' >"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/magnifier.png");?>" alt="View">'+"</a>";        

		return str;
	}

	function view_details_report(e,domain_id)
	{
		var details_url = "<?php echo site_url('admin/website_wise_details'); ?>";
		$.ajax({
			url:details_url,
			type:'POST',
			data:{domain_id:domain_id},
			success:function(response){
				$("#modal_domain_detail").modal();
				$("#domain_view_body").html(response);
				$("#hidden_domain_id").val(domain_id);
			}
		});
	}

	$("#new_search_modal_open").click(function(){
		$("#modal_new_search").modal();

	});

	$("#proxy_checkbox").click(function(){
		$("#proxy_server_container").toggle(400);

	});

	$("#website_wise_download_btn").click(function(){
		var base_url="<?php echo base_url(); ?>";
		$('#website_wise_download_btn').html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');
		
		var url = "<?php echo site_url('admin/website_wise_download');?>";
        var rows = $j("#tt").datagrid("getSelections");
        var info=JSON.stringify(rows); 
        if(rows == '')
        {
        	$('#website_wise_download_btn').html("<i class='fa fa-cloud-download'></i> Download");
            alert("You haven't select any field");
            return false;
        }
        $.ajax({
            type:'POST',
            url:url,
            data:{info:info},
            success:function(response){
                if (response != '')
                 {    
                 	$('#website_wise_download_btn').html("<i class='fa fa-cloud-download'></i> Download"); 
                    $("#modal_for_download").modal();
                } 

                else 
                {
                    alert("Something went wrong, please try again.");
                }
            }
        });
	});

//section for Delete
	$("#website_wise_delete_btn").click(function(){
		var result = confirm("Want to delete?");

		if(result == '1'){
			var base_url="<?php echo base_url(); ?>";		
			var url = "<?php echo site_url('admin/website_delete');?>";
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

	//Function for Domain wise all email Download*********************
	$("#download_details").click(function(){
		var download_details_url = $("#hidden_domain_id").val();
		var url_download = "<?php echo site_url('admin/domain_wise_details_download'); ?>";
		$.ajax({
			url:url_download,
			type:'POST',
			data:{download_details_url:download_details_url},
			success:function(response){
				if (response != '') 
					{
						$('#modal_domain_detail').modal("hide");
						$('#modal_for_details_download').modal();
					} 

				else 
					{
						alert("Something went wrong, please try again.");
					}				
			}
		});
	});

	//End of Function for Domain wise all email Download*****************

	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			domain_name:     $j('#domain_name').val(),               
			user_name  :     $j('#user_name').val(),               
			from_date  :     $j('#from_date').val(),    
			to_date    :     $j('#to_date').val(),         
			is_searched:      1
		});


	}    
	
	
	
	$j("document").ready(function(){
		
		var base_url="<?php echo base_url(); ?>";
		
		$("#new_search_button").on('click',function(){
			
			var web_address=$("#website_address").val();
			if ($('#proxy_checkbox').is(':checked')) {
				var proxy_address=$("#proxy_server").val();
			}
			else{
				var proxy_address='';
			}
			
			if(web_address==''){
				alert("Please enter web address");
				return false;
			}
		
		$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');	
		$('#success_num').show();	
			web_address=escape(web_address);
			proxy_address=escape(proxy_address);
			
			web_address = web_address.replace(/\//gi,'____');
			
			var url=base_url+"scrape/scrape_website/"+web_address+"/"+proxy_address;
			
			if(web_address!=''){
				document.getElementById('loadarea').src = url;
			}
			
		});
	
	});
	
	
	function kill(){
		 document.getElementById('loadarea').src = '';
		 $("#success_msg").html('<h3 class="text-center" style="color:red;">Scraping Stopped</h3>');
		 
		 $("#url_download_div").html('<a href="<?php echo base_url()."download/website/url_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-warning"><i class="fa fa-cloud-download"></i> <b>Download URL</b></a>');
						   
		$("#email_download_div").html('<a href="<?php echo base_url()."download/website/email_{$this->user_id}_{$this->download_id}.csv" ?>" target="_blank" class="btn btn-warning"><i class="fa fa-cloud-download"></i> <b>Download EMail</b></a>');
						  	 
	}
	
	
	
</script>

<!-- Modal for download -->
<div id="modal_for_download" class="modal fade">
	<div class="modal-dialog" style="width:65%;">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="" class="modal-title">Download Status</h4>
			</div>

			<div class="modal-body clearfix">
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
								echo "<a href='".base_url()."download/report/website_wise_email_{$this->user_id}_{$this->download_id}.csv' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";							
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

<!-- modal for details download -->
<div id="modal_for_details_download" class="modal fade">
	<div class="modal-dialog" style="width:50%;">
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
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
							<div class="box">
							<h2>Your file is ready to download</h2>
							<?php 
								echo '<i class="fa fa-2x fa-thumbs-o-up"style="color:black"></i><br><br>';
								echo "<a href='".base_url()."download/report/domain_details_email_{$this->user_id}_{$this->download_id}.csv' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i> Download</a>";							
							?>
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