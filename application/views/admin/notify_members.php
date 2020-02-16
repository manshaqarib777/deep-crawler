<?php $this->load->view('admin/theme/message'); ?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Send Notification To Members </h1>

</section>

<!-- Main content -->
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:700px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."admin/notify_members_data_loader"; ?>" 
            
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
                     <th field="id" checkbox="true"></th>                        
                     <th field="name" sortable="true">Name</th>   
                     <th field="email"  sortable="true">Email</th>
                     <th field="mobile" sortable="true" >Mobile</th>
                     <th field="address" sortable="true" >Address</th>
                     <th field="status" formatter="status_check" >Status</th>                  
                 </tr>
               </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">

      <a class="btn btn-warning"  title="Send Email" onclick="sms_send_email_ui()">
        <i class="fa fa-envelope"></i> Send Email
    </a>

 <form class="form-inline" style="margin-top:20px">

                <div class="form-group">
                    <input id="first_name" name="first_name" class="form-control" size="20" placeholder="Name">
                </div> 

                <button class='btn btn-info'  onclick="doSearch(event)">Search</button>
                      
            </form> 

        </div>        
    </div>
  </div>   
</section>






<!--Modal for Send SMS  Email-->
  
<div id="modal_send_sms_email" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="SMS" class="modal-title"> <i class="fa fa-envelope"></i> <b>Send Notification</b></h4>
      </div>

      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>

        <div class="form-group">
          <label for="sms_content">Subject *</label><br/>
          <input type="text" id="sms_subject" required class="form-control" placeholder="Message Subject"/>
        </div>

        <div class="form-group">
          <label for="sms_content">Message *</label><br/>
          <textarea name="sms_content" required style="width:100%;height:200px;" id="sms_content"></textarea>
        </div>

        <div id="text_count"></div>
     
      </div>

      <div class="modal-footer clearfix">
           <button id="send_sms_email" class="btn btn-warning pull-left" > <i class="fa fa-envelope"></i>  Send</button>
           <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script>   

 function sms_send_email_ui()
    {
      $("#modal_send_sms_email").modal();
    }    

    $j("document").ready(function() {
        $( ".datepicker" ).datepicker();

         $("#send_sms_email").click(function(){      
                  
          var subject=$("#sms_subject").val();
          var content=$("#sms_content").val();         
          var rows = $j('#tt').datagrid('getSelections');
          var info=JSON.stringify(rows);  
          
          if(rows=="") 
          {
            $("#show_message").addClass("alert alert-warning");
            $("#show_message").html("<b>You do not selected any member.</b>");
            return;
          }
          
          if(subject=="" || content=="")
          {
            $("#show_message").addClass("alert alert-warning");
            $("#show_message").html("Something is missing.");
            return;
          }

          $(this).attr('disabled','yes');
          $("#show_message").addClass("alert alert-info");
          $("#show_message").show().html('<i class="fa fa-spinner fa-spin"></i> Sending Email, please wait...');
          $.ajax({
          type:'POST' ,
          url: "<?php echo site_url(); ?>admin/send_email_member",
          data:{content:content,info:info,subject:subject},
          success:function(response){
            $("#send_sms_email").removeAttr('disabled');                     
            $("#show_message").addClass("alert alert-info");
            $("#show_message").html(response);
          }
        });   
      }); 
    });  

    var base_url="<?php echo site_url(); ?>";

    function status_check(value, row, index)
    {
      var status = row.status; 
      var str = '';     

      if(status == "1")
        str = str+"<label class='label label-success'>Active</label>";      

      if(status == '0')
        str = str+"<label class='label label-danger'>Inactive</label>";

      return str;
    }
    
   
    function doSearch(event)
    {
        event.preventDefault(); 
        $j('#tt').datagrid('load',{
          first_name:       $j('#first_name').val(),
          is_searched:      1
        });
    }  
</script>
