<header class="main-header">
  <!-- Logo -->
  <a href="<?php echo base_url(); ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b><?php echo $this->config->item("product_short_name");?></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b><img style="height:45px;margin-top:3px;" src="<?php echo base_url();?>assets/images/deepcrawllogo.svg" alt="<?php echo $this->config->item('product_name');?>" class="img-responsive">
  </b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <?php $this->load->view("admin/theme/notification"); ?>
  </nav>
</header>