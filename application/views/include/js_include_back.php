<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/colorbox/jquery.colorbox.js"></script>

<script>
    	var $colorbox = $.noConflict();
		$colorbox(".image_preview_colorbox").colorbox();
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/grid/jquery.easyui.min.js"></script>

<script>
    	var $j= jQuery.noConflict();
</script> 

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo base_url();?>plugins/morris/morris.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="<?php echo base_url();?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?php echo base_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>plugins/knob/jquery.knob.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?php echo base_url();?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>js/app.min.js" type="text/javascript"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url();?>js/pages/dashboard.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>js/demo.js" type="text/javascript"></script>
<!-- added 20/9/2015 -->
<script src="<?php echo base_url();?>plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/common.js" type="text/javascript"></script>


<script src="<?php echo base_url();?>plugins/chartjs/Chart.js" type="text/javascript"></script>


<!-- for tab -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>-->



<script>
// grid formatter
function status(value,row,index)
{   
    if(value==1) return "<label class='label label-success'>Active</label>";            
    else return "<label class='label label-warning'>Inactive</label>";            
}   
//  grid formatter

function goBack(link,insert_or_update) //used to go back to list as crud
{
	if (typeof(insert_or_update)==='undefined') insert_or_update = 0;

    var mes='';
	if(insert_or_update==0)
	mes='The data you had insert may not be saved.\nAre you sure you want to go back to list?';
		else
		mes='The data you had change may not be saved.\nAre you sure you want to go back to list?';
	var ans=confirm(mes); 
	link="<?php echo site_url();?>"+link;
	if(ans) window.location.assign(link);
}
// Code that uses other library's $ can follow here.


</script>



