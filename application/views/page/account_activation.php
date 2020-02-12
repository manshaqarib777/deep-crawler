<br>
<style>#msg{text-align:center;}</style>
<style>#wait{padding-left:10px;}</style>
  <div class="row row-centered">
    <div class="col-sm-11 col-xs-11 col-md-8 col-lg-8 col-centered border_gray grid_content padded background_white">
    <h6 class="column-title"><i class="fa fa-key fa-2x blue"> Account Activation</i></h6>
    <div class="text-center account-wall" id='recovery_form'>
    <div id='msg'></div>      
        
      <form class="form-horizontal" action="<?php echo site_url();?>home/account_activation_action" method="POST">
          <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Account Activation Code</label>
              <div class='col-xs-10 col-sm-10 col-md-8 col-lg-8'>
                  <input class="form-control" type="text" id="code" placeholder="Account Activation Code" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1" id='old'></span>
          </div>
          <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3  control-label">Email Address</label>
              <div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
                  <input class="form-control" type="email" name="email" placeholder="Email Address" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1"></span>
          </div>
          <!-- <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Confirm New Password</label>
              <div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
                  <input class="form-control" type="password" name="new_password_confirm" placeholder="Confirm New Password" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1 text-left" id='conf'></span>
          </div> -->
          <div class="form-group text-center">
              <div class="col-xs-12 col-xs-offset-3">
                  <input type="submit" class='btn btn-warning btn-lg pull-left' name="submit" id="submit" value="Activate Account">
                  <span id='wait' class='pull-left'></span>
              </div> 
          </div>      
      </form>       
      
    </div>
  </div>
</div>


<script type="text/javascript">
$('document').ready(function(){

  $("#submit").click(function(e){
    e.preventDefault();

    $("#msg").removeAttr('class');
    $("#msg").html("");

    var is_code=$("#code").val();
    if(is_code=='')
    {
      $("#msg").attr('class','alert alert-warning');
      $("#msg").html("Please enter the code");
    }
    else
    {
      $("#wait").html("<img src='<?php echo base_url();?>assets/pre-loader/Ovals in circle.gif' height='20' width='20'>");
      var code=$("#code").val();
      var email=$("input[name='email']").val();
      $.ajax({
        type:'POST',
        url: "<?php echo base_url();?>home/account_activation_action",
        data:{code:code,email:email},
        success:function(response){
              $("#wait").html("");
              if(response=='0')
              {
                $("#msg").attr('class','alert alert-danger');
                $("#msg").html("Account activation code doesn't match");
              }
              if(response == '2')
              {
                var string="<div class='alert alert-success'>"+ 
                  "<p>"+
                    "Your account activated successfully.<br>"+
                  "</p>"+
                  "<br/><a href='<?php echo site_url();?>home/login' class='btn btn-primary btn-lg'>Login</a>"+
                "</div>";
                $("#recovery_form").slideUp();
                $("#recovery_form").html(string);
                $("#recovery_form").slideDown();
              }
          }
      });
    }
  });
});
</script>

