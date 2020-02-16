<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $this->config->item('product_name')." | ".$page_title;?></title>
    <?php $this->load->view('include/css_include_back');?>
	  <?php $this->load->view('include/js_include_back');?>
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"> 	
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper" style="min-height:800px;">

      <?php $this->load->view('admin/theme/header');?>

      <!-- Left side column. contains the logo and sidebar -->
      <?php $this->load->view('admin/theme/sidebar'); ?>

      <!-- Content Wrapper. Contains page content --> 
      <div class="content-wrapper">
  		<?php 
        if($crud==1) 
			$this->load->view('admin/theme/theme_crud',$output); 
        else 
			$this->load->view($body);
      ?>  
      </div><!-- /.content-wrapper -->

      <!-- footer was here -->

      <!-- Control Sidebar -->
      <?php //$this->load->view('theme/control_sidebar');?>
      <!-- /.control-sidebar -->

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- Footer -->
      <?php $this->load->view('admin/theme/footer');?>
    <!-- Footer -->
  </body>
</html>
