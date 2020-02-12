<?php $this->load->view('admin/theme/message'); ?>
<?php    
    $view_permission    = 1;
    $edit_permission    = 1;
    $delete_permission  = 1;
?>

<style type="text/css" media="screen">
  #button_place form button
  {
     height:50px !important;
     width: 141px !important;
  }
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> <?php echo $this->lang->line("payment option"); ?> </h1>

</section>

<!-- Main content -->
<section  style="margin:15px;background: #eee!important;border:2px solid #ccc;" class="alert alert-warning">
  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <?php
        $packages[""]=$this->lang->line("choose package");
        echo form_dropdown('choose_package',$packages,"",'class="form-control" id="choose_package" style="height:50px;font-size:20px;"'); 
      ?> 
      </div> 
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div id="button_place"></div>
      </div>
    </div>
</section>

<section class="content-header">
  <h1> <?php echo $this->lang->line("payment history"); ?> </h1>

</section>

<!-- Main content -->
<section class="content">  

  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:700px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."payment/member_payment_history_data"; ?>" 
            
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
                     <th field="first_name" sortable="true"><?php echo $this->lang->line("first name"); ?></th>                        
                     <th field="last_name"  sortable="true" ><?php echo $this->lang->line("last name"); ?></th>
                     <th field="name"  sortable="true" ><?php echo $this->lang->line("application name"); ?></th>
                     <th field="email" sortable="true" >Application Email </th>
					           <th field="paypal_email" sortable="true" >Paypal Email / Stripe </th>
                     <th field="payment_date"  sortable="true"><?php echo $this->lang->line("payment date"); ?></th>
                     <th field="paid_amount" sortable="true" ><?php echo $this->lang->line("paid amount"). "-".$currency; ?></th>
                     <th field="cycle_start_date" sortable="true" ><?php echo $this->lang->line("cycle start date"); ?></th>
                     <th field="cycle_expired_date" sortable="true" ><?php echo $this->lang->line("cycle expire date"); ?></th>                    
                 </tr>
               </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">



                          
            <form class="form-inline" style="margin-top:20px">

                <div class="form-group">
                    <input id="first_name" name="first_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line("first name"); ?>">
                </div> 

                <div class="form-group">
                    <input id="last_name" name="last_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line("last name"); ?>">
                </div> 

                <div class="form-group">
                    <input id="from_date" name="from_date" class="form-control datepicker" size="20" placeholder="<?php echo $this->lang->line("from date"); ?>">
                </div>

                <div class="form-group">
                    <input id="to_date" name="to_date" class="form-control  datepicker" size="20" placeholder="<?php echo $this->lang->line("to date"); ?>">
                </div>  

                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line("search"); ?></button>
                      
            </form> 

        </div>        
    </div>
  </div>   
</section>


<script>       
    $j(function() {
        $( ".datepicker" ).datepicker();

        $("#choose_package").change(function(){
           var package=$(this).val(); 
           var base_url="<?php echo site_url();?>"; 
           var img_src="<?php echo base_url();?>"+"assets/pre-loader/Moving blocks.gif";
           var img= "<img src='"+img_src+ "' alt='Loading...' style='margin-top:7px;'>";
           $("#button_place").html(img);
           $.ajax
           ({
               type:'POST',
               async:false,
               data:{package:package},
               url:base_url+'payment/payment_button/',
               success:function(response)
                {
                    $("#button_place").html(response);
                }
                   
            });      
        });
    });  

    var base_url="<?php echo site_url(); ?>"
   
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
