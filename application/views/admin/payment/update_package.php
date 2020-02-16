<section class="content-header">
   <section class="content">
     <div class="box box-info custom_box">
       <div class="box-header">
         <h3 class="box-title"><i class="fa fa-plus-pencil"></i> <?php echo $this->lang->line("edit")." - ".$this->lang->line("package settings"); ?></h3>
       </div><!-- /.box-header -->
       <!-- form start -->
       <form class="form-horizontal" action="<?php echo site_url().'payment/update_package_action';?>" method="POST">
         <div class="box-body">
           <div class="form-group">
             <label class="col-sm-3 control-label" for="name"> <?php echo $this->lang->line("package name")?> *</label>
             <div class="col-sm-9 col-md-6 col-lg-6">
               <input name="name" value="<?php echo $value[0]["package_name"];?>"  class="form-control" type="text">
               <span class="red"><?php echo form_error('name'); ?></span>
             </div>
           </div>
           <div class="form-group">
             <label class="col-sm-3 control-label" for="price"><?php echo $this->lang->line("price")?> - <?php echo $payment_config[0]['currency']; ?> *</label>
             <div class="col-sm-9 col-md-6 col-lg-6">
               <?php 
               if($value[0]['is_default']=="1") 
               { ?>
           		  <select name="price" id="price_default" class="form-control">
                     <option  value="Trial" <?php if( $value[0]["price"]=="Trial") echo 'selected="yes"'; ?>>Trial</option>
                     <option  value="0" <?php if( $value[0]["price"]=="0") echo 'selected="yes"'; ?>>Free</option>
                  </select>
           	   <?php
               }
               else
               { ?>
               	   <input name="price" value="<?php echo $value[0]["price"];?>"  class="form-control" type="text">
               <?php
               }
               ?>
               <span class="red"><?php echo form_error('price'); ?></span>
             </div>
           </div>
           <div class="form-group" id="hidden">
             <label class="col-sm-3 control-label" for="price"><?php echo $this->lang->line("validity");?> - <?php echo $this->lang->line("days"); ?> *</label>
             <div class="col-sm-9 col-md-6 col-lg-6">
               <input id="validity" name="validity" value="<?php echo $value[0]["validity"];?>"  class="form-control" type="text">

               <span class="red"><?php echo form_error('validity'); ?></span>
             </div>
           </div>


          <div class="form-group">
             <label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("modules")?> * </label>
             <div class="col-sm-9 table-responsive">

             <table class="table table-bordered table-condensed table-hover table-striped" style="width:auto;">
              <tr>
               <td colspan="5"><input  id="all_modules" type="checkbox"/> <font color="blue">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $this->lang->line("All Modules"); ?></b></font> [<?php echo $this->lang->line("0 means unlimited");?>]</td>
              </tr>
               <?php                   

                    $current_modules=array();
                    $current_modules=explode(',',$value[0]["module_ids"]); 
                    $monthly_limit=json_decode($value[0]["monthly_limit"],true);
                    $bulk_limit=json_decode($value[0]["bulk_limit"],true);

                    echo "<tr>";    
                        echo "<th class='text-center success'>"; 
                          echo $this->lang->line("modules");         
                        echo "</th>";
                        echo "<th class='text-center success' colspan='2'>"; 
                          echo $this->lang->line("Search Limit");         
                        echo "</th>";
                        echo "<th class='text-center success' colspan='2'>"; 
                          echo $this->lang->line("Bulk Limit");         
                        echo "</th>";
                    echo "</tr>"; 

                    foreach($modules as $module) 
                    {  
                     echo "<tr>";    
                        echo "<td>";
                          if(is_array($current_modules) && in_array($module['id'], $current_modules))
                          { ?>                  
                               <input  name="modules[]" class="modules" checked="checked" type="checkbox" value="<?php echo $module['id']; ?>"/>
                          <?php 
                          }
                          else
                          { ?>
                              <input  name="modules[]" class="modules"  type="checkbox" value="<?php echo $module['id']; ?>"/>
                           <?php 
                          }
                          echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".$this->lang->line($module['module_name'])."</b>";                
                      echo "</td>";

                      $xmonthly_val=0;
                      $xbulk_val=0;
                     
                      if(in_array($module["id"],$current_modules))
                      {
                        $xmonthly_val=$monthly_limit[$module["id"]];
                        $xbulk_val=$bulk_limit[$module["id"]];
                      }

                      if(in_array($module["id"],array(0)))
                      {
                        $type="hidden";
                        $limit="";

                      }
                      else
                      {
                          $type="number";
                          if($module["id"]=="1") $limit=$this->lang->line("Search Limit");
                          else $limit=$this->lang->line("Search Limit")." / ".$this->lang->line("Month");
                     }



                      echo "<td style='padding-left:10px'>".$limit."</td><td><input type='".$type."' value='".$xmonthly_val."' min='0' style='width:70px;' name='monthly_".$module['id']."'></td>";
                      
                      if(!in_array($module["id"],array(2,4,5,6,7,8,9)))
                      {
                        $type="hidden";
                        $limit="";
                      }
                      else
                      {
                        $type="number";
                          $limit=$this->lang->line("Bulk Limit")." / ".$this->lang->line("Analysis");
                      }

                      echo "<td style='padding-left:10px'>".$limit."</td><td><input type='".$type."' min='0' value='".$xbulk_val."' style='width:70px;' name='bulk_".$module['id']."'></td>";
                      echo "</tr>";                 
                   }                
                ?>            
              </table>     
               <span class="red" ><?php echo "<br/><br/>".form_error('modules'); ?></span>  
              </div> 
           </div>
           

          
           <input name="id" value="<?php echo $value[0]["id"];?>"  class="form-control" type="hidden">              
           <input name="is_default" value="<?php echo $value[0]["is_default"];?>"  class="form-control" type="hidden">              

           </div> <!-- /.box-body --> 
           <div class="box-footer">
            <div class="form-group">
             <div class="col-sm-12 text-center">
               <input name="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line('save'); ?>"/>         
               <input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line('cancel'); ?>" onclick='goBack("payment/package_settings",0)'/>
             </div>
           </div>
         </div><!-- /.box-footer -->         
         </div><!-- /.box-info -->       
       </form>     
     </div>
   </section>
</section>


<script type="text/javascript">
  $j(document).ready(function() {
  	if($("#price_default").val()=="0") $("#hidden").hide();
    else $("#validity").show();
    $("#all_modules").change(function(){
      if ($(this).is(':checked')) 
      $(".modules").prop("checked",true);
      else
      $(".modules").prop("checked",false);
    });
    $("#price_default").change(function(){
    	if($(this).val()=="0") $("#hidden").hide();
    	else $("#hidden").show();
    });
  });
</script>

<style type="text/css" media="screen">
  td,th{background:#fff}
</style>