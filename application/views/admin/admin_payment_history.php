<?php $this->load->view('admin/theme/message'); ?>
<?php    
    $view_permission    = 1;
    $edit_permission    = 1;
    $delete_permission  = 1;
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Payment History </h1>

</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:700px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."payment/admin_payment_history_data"; ?>" 
            
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
            
               <thead>
                 <tr>
                     <!-- <th field="id" checkbox="false"></th> -->
                     <th field="first_name" sortable="true">First Name</th>                        
                     <th field="last_name"  sortable="true" >Last Name</th>
                     <th field="name"  sortable="true" >Application Name</th>
                     <th field="email"  sortable="true" >Application Email</th>
                     <th field="paypal_email"  sortable="true">PayPal Email/ Stripe</th>
                     <th field="paid_amount" sortable="true" >Paid Amount</th>
                      <th field="payment_date"  sortable="true"><?php echo $this->lang->line("payment date"); ?></th>
                     <th field="cycle_start_date" sortable="true" >Cycle Start Date</th>
                     <th field="cycle_expired_date" sortable="true" >Cycle Expire Date</th>                  
                 </tr>
               </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">
            <h4 style="color:olive">Total Paid Amount : <?php echo $total_paid_amount; ?></h4> 
            <form class="form-inline" style="margin-top:20px">

                <div class="form-group">
                    <input id="first_name" name="first_name" class="form-control" size="20" placeholder="First Name">
                </div> 

                <div class="form-group">
                    <input id="last_name" name="last_name" class="form-control" size="20" placeholder="Last Name">
                </div> 

                <div class="form-group">
                    <input id="from_date" name="from_date" class="form-control datepicker" size="20" placeholder="Payment From Date">
                </div>

                <div class="form-group">
                    <input id="to_date" name="to_date" class="form-control  datepicker" size="20" placeholder="Payment To Date">
                </div>  

                <button class='btn btn-info'  onclick="doSearch(event)">Search</button>
                      
            </form> 

        </div>        
    </div>
  </div>   
</section>


<script>       
    $j(function() {
        $( ".datepicker" ).datepicker();
    });  

    var base_url="<?php echo site_url(); ?>"
    
    // function action_column(value,row,index)
    // {               
    //     var details_url=base_url+'admin/view_details/'+row.id;        
    //     var edit_url=base_url+'admin/update_book/'+row.id;
    //     var delete_url=base_url+'admin/delete_book_action/'+row.id;
        
    //     var str="";
    //     var view_permission="<?php echo $view_permission; ?>";        
    //     var edit_permission="<?php echo $edit_permission; ?>";   
    //     var delete_permission="<?php echo $delete_permission; ?>";   
        
    //     if(view_permission==1)     
    //     str="<a title='"+'<?php echo $this->lang->line("view") ?>'+"' style='cursor:pointer' href='"+details_url+"'>"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/magnifier.png");?>" alt="View">'+"</a>";
        
    //     if(edit_permission==1)
    //     str=str+"&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer' title='"+'<?php echo $this->lang->line("edit") ?>'+"' href='"+edit_url+"'>"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/edit.png");?>" alt="Edit">'+"</a>";

    //     if(delete_permission == 1)
    //     str=str+"&nbsp;&nbsp;&nbsp;&nbsp;<a style='cursor:pointer' title='"+'<?php echo $this->lang->line("delete") ?>'+"' href='"+delete_url+"'>"+' <img src="<?php echo base_url("plugins/grocery_crud/themes/flexigrid/css/images/close.png");?>" alt="Delete">'+"</a>";
        
    //     return str;
    // }  

    // function paid_amount_show(value,row,index)
    // {
    //   var str = '';
    //   $.ajax({
    //      type:"POST",
    //      url:base_url+"payment/paid_amount_show",
    //      async:false,
    //      success:function(response){
    //         str = response;
    //      }
    //   });
    //   return str;
    // }
   
    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          first_name:       $j('#first_name').val(),
          last_name:        $j('#last_name').val(),
          from_date:        $j('#from_date').val(),
          to_date:          $j('#to_date').val(),
          is_searched:      1
        });
    }  
</script>
