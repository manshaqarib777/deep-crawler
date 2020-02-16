
<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">
<!--     <li class="dropdown messages-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
      </a>
      <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
          <ul class="menu">
              <a href="#">
                <div class="pull-left">
                  <img src="img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                </div>
                <h4>
                  Support Team
                  <small><i class="fa fa-clock-o"></i> 5 mins</small>
                </h4>
                <p>Why not buy a new awesome theme?</p>
              </a>
            </li>
            <li>
              <a href="#">
                <div class="pull-left">
                  <img src="img/user3-128x128.jpg" class="img-circle" alt="User Image" />
                </div>
                <h4>
                  AdminLTE Design Team
                  <small><i class="fa fa-clock-o"></i> 2 hours</small>
                </h4>
                <p>Why not buy a new awesome theme?</p>
              </a>
            </li>
            <li>
              <a href="#">
                <div class="pull-left">
                  <img src="img/user4-128x128.jpg" class="img-circle" alt="User Image" />
                </div>
                <h4>
                  Developers
                  <small><i class="fa fa-clock-o"></i> Today</small>
                </h4>
                <p>Why not buy a new awesome theme?</p>
              </a>
            </li>
            <li>
              <a href="#">
                <div class="pull-left">
                  <img src="img/user3-128x128.jpg" class="img-circle" alt="User Image" />
                </div>
                <h4>
                  Sales Department
                  <small><i class="fa fa-clock-o"></i> Yesterday</small>
                </h4>
                <p>Why not buy a new awesome theme?</p>
              </a>
            </li>
            <li>
              <a href="#">
                <div class="pull-left">
                  <img src="img/user4-128x128.jpg" class="img-circle" alt="User Image" />
                </div>
                <h4>
                  Reviewers
                  <small><i class="fa fa-clock-o"></i> 2 days</small>
                </h4>
                <p>Why not buy a new awesome theme?</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
      </ul>
    </li>
	

    <li class="dropdown notifications-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"  id="no_notification"> </span>
      </a>
      <ul class="dropdown-menu">
	  
	  	
        <li class="header" id="no_notification_str"> </li>	
		
        <li>          
          <ul class="menu">
		  
            <li>
              <a href="#">
                <i id="notification_new_tutor" class="fa fa-users text-aqua"></i> <span></span>
              </a>
            </li>
			
            <li>
              <a href="#">
                <i id="notification_new_payment" class="fa fa-warning text-yellow"></i><span></span>
				</a>
            </li>
			
			
            <li>
              <a href="#">
                <i id="notification_new_verify" class="fa fa-users text-red"></i> <span></span>
              </a>
            </li>
			
          </ul>
        </li>
      </ul>
    </li>
   -->
  
    <?php 
      $pro_pic=base_url().'assets/images/logo.png';
    ?>
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
       <i class="fa fa-user"></i>
        <span><?php echo $this->session->userdata('username'); ?></span>
      </a>
      <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">          
          <br/><br/>
          <center><img src="<?php echo $pro_pic;?>" class="img-responsive"/></center>
          <p>
            <?php echo $this->session->userdata('username');?>           
          </p>
        </li>
        <!-- Menu Body -->
        <!-- <li class="user-body">
          <div class="col-xs-4 text-center">
            <a href="#">Followers</a>
          </div>
          <div class="col-xs-4 text-center">
            <a href="#">Sales</a>
          </div>
          <div class="col-xs-4 text-center">
            <a href="#">Friends</a>
          </div>
        </li> -->
        <!-- Menu Footer-->
        <li class="user-footer border_gray">
          <div class="pull-left">
            <a href="<?php echo site_url('admin/reset_password_form') ?>" class="btn btn-info btn-flat">Change Password</a>
          </div>
          <div class="pull-right">
            <a href="<?php echo site_url('home/logout') ?>" class="btn btn-warning btn-flat">Sign out</a>
          </div>
        </li>
      </ul>
    </li>
    <!-- Control Sidebar Toggle Button -->
    <!-- <li>
      <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
    </li> -->
  </ul>
</div>