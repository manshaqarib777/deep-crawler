	<?php $this->load->view('admin/theme/message'); ?>

	<!-- Main content -->
	<section class="content-header">
		<h1 class = 'text-info'> URL Search</h1>
	</section>
	<section class="content">  
		<div class="row" >
			<div class="col-xs-12">
				<div class="grid_container" style="width:100%; min-height:650px;">
					<table 
					id="tt"  
					class="easyui-datagrid" 
					url="<?php echo base_url()."admin/url_search_data"; ?>" 

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
							<th field="url_name" sortable="true">URL</th>
							<th field="is_available" formatter='availability_label' >Availability</th>
							<th field="last_scraped_time" sortable="true" >Last Scraped Time</th>
							<th field="found_email" sortable="true">Found Email</th>                      
							<th field="view" formatter="view_details">Details</th>                      

						</tr>
					</thead>
				</table>                        
			</div>

			<div id="tb" style="padding:3px">

				<button type="button" class="btn btn-info" id = "new_search_modal_open"><i class="fa fa-search"></i> New Search</button>
				<button type="button" class="btn btn-warning pull-right" id = "url_wise_download_btn"><i class="fa fa-cloud-download"></i> Download</button> 

				<form class="form-inline" style="margin-top:20px">
					<div class="form-group">
						<input id="url" name="url" class="form-control" size="20" placeholder="URL">
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

<!-- Start modal for URL wise details report. -->

<div id="modal_domain_detail" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="domain_details_title" class="modal-title">URL Details</h4>
			</div>
			<div class="clearfix" style="margin-top:5px; margin-right:10px">				
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
<!-- End modal for URL wise details report. -->

<!-- Start modal for new search. -->
<div id="modal_new_search" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
				<h4 id="new_search_details_title" class="modal-title">New Search</h4>
			</div><br/>


			<div id="new_search_view_body" class="modal-body">
			
				<form enctype="multipart/form-data" method="post" class="form-inline" id="new_search_form" style="margin-top:60px margin-left:10px">

				<div class="form-group" style="margin-left:45px">
					<textarea id="bulk_urls" style="width:510px;height:200px" placeholder="Put Your Text Including Domain Name With Comma Separated."></textarea>
				</div><br/><span style="margin-left:65px">OR</span><br/> 


				<div class="form-group" style="margin-left:45px">
					<input type="file" name="myfile[]" id="myfile" multiple/>
				</div>

				<button class='btn btn-success'  id = "pull_data">Pull</button>     
				<input type="button"  id="new_search_button" class="btn btn-info" value="Search">
				
			</form>	
			
				

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
		var url_id = row.url_id;
		var str="";

		str="<a title='Details' style='cursor:pointer' onclick='view_details_report(event,"+url_id+")' >"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/magnifier.png");?>" alt="View">'+"</a>";        

		return str;
	}

	function view_details_report(e,url_id)
	{
		var details_url = "<?php echo site_url('admin/url_wise_details'); ?>";
		$.ajax({
			url:details_url,
			type:'POST',
			data:{url_id:url_id},
			success:function(response){
				$("#modal_domain_detail").modal();
				$("#domain_view_body").html(response);
				$("#hidden_ulr_id").val(url_id);
			}
		});
	}

	$("#new_search_modal_open").click(function(){
		$("#modal_new_search").modal();

	});

	$("#proxy_checkbox").click(function(){
		$("#proxy_server").toggle(400);

	});

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

	$("#pull_data").click(function(e){
		e.preventDefault();
		var site_url="<?php echo site_url();?>";  
		var queryString = new FormData($("#new_search_form")[0]);
		$.ajax({
            url: site_url+'test/multiple_read_file',
            type: 'POST',
            data: queryString,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){      
				    /*$("#bulk_urls").val('');	
                	$("#bulk_urls").val(response);*/

                	alert(response);
            }
        });

	});

	//Function for URL wise all email Download*********************
	$("#download_details").click(function(){
		var download_details_url = $("#hidden_ulr_id").val();
		var url_download = "<?php echo site_url('admin/url_wise_details_download'); ?>";
		$.ajax({
			url:url_download,
			type:'POST',
			data:{download_details_url:download_details_url},
			success:function(response){
				if (response != '') 
					{
						var redirect_url = "<?php echo site_url('home/download_page_loader');?>";                   
						window.open(redirect_url);
					} 

				else 
					{
						alert("Something went wrong, please try again.");
					}				
			}
		});
	});

	//End of Function for URL wise all email Download*****************


	function doSearch(event)
	{
		event.preventDefault(); 
		$j('#tt').datagrid('load',{
			url        :     $j('#url').val(),            
			from_date  :     $j('#from_date').val(),    
			to_date    :     $j('#to_date').val(),         
			is_searched:      1
		});


	}  
	
	
	
	$j("document").ready(function(){
		
		var base_url="<?php echo base_url(); ?>";
		
		$("#new_search_button").on('click',function(){
			
			var urls=$("#bulk_urls").val();
			
			if(urls==''){
				alert("please enter url");
				return false;
			}
			
			$("#success_msg").html('<img class="center-block" height="40px" width="100px" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching..."><br/>');
			
			$.ajax({
				url:base_url+'scrape/scrape_url',
				type:'POST',
				data:{urls:urls},
				success:function(response){
					$("#email_download_div").html(response);
					$("#success_msg").html('<center> completed </center>');
				}
			
			});
			
			
		});
	
	});
	
	
	
	
	  
</script>