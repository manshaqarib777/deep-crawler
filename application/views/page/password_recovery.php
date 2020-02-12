<br>
<style>#msg{text-align:center;}</style>
<style>#wait{padding-left:10px;}</style>
  <div class="row row-centered">
    <div class="col-sm-11 col-xs-11 col-md-8 col-lg-8 col-centered border_gray grid_content padded background_white">
    <h6 class="column-title"><i class="fa fa-key fa-2x blue"> Password Recovery</i></h6>
    <div class="text-center account-wall" id='recovery_form'>
    <div id='msg'></div>      
        
      <form class="form-horizontal" action="<?php echo site_url();?>home/password_change_action" method="POST">
          <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Password Recovery Code</label>
              <div class='col-xs-10 col-sm-10 col-md-8 col-lg-8'>
                  <input class="form-control" type="text" id="code" placeholder="Password Recovery Code" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1" id='old'></span>
          </div>
          <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3  control-label">New Password</label>
              <div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
                  <input class="form-control" type="password" name="new_password" placeholder="New Password" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1"></span>
          </div>
          <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Confirm New Password</label>
              <div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
                  <input class="form-control" type="password" name="new_password_confirm" placeholder="Confirm New Password" required>
              </div>
              <span class="col-sm-2 col-xs-2 col-md-1 col-lg-1 text-left" id='conf'></span>
          </div>
          <div class="form-group text-center">
              <div class="col-xs-12 col-xs-offset-3">
                  <input type="submit" class='btn btn-warning btn-lg pull-left' name="submit" id="submit" value="Reset Password">
                  <span id='wait' class='pull-left'></span>
              </div> 
          </div>      
      </form>       
      
    </div>
  </div>
</div>


<script type="text/javascript">
$('document').ready(function(){
    var confirm_match=0;
    $("input[name='new_password_confirm']").keyup(function(){
      var new_pass=$("input[name='new_password']").val();
      var conf_pass=$("input[name='new_password_confirm']").val();

      if(new_pass==conf_pass)
      {
          confirm_match=1;
          $("#conf").html("<i class='glyphicon glyphicon-ok' style='color:green;'></i>");
      }
      else
      {
          $("#conf").html("<img src='<?php echo base_url();?>assets/pre-loader/Ovals in circle.gif' height='20' width='20'>");
          confirm_match=0;
      }

  });


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
    else if(confirm_match==0)
    {
        $("#msg").attr('class','alert alert-warning');
        $("#msg").html("New password and confirm password don't match");
        confirm_match=0;
    }
    else
    {
      $("#wait").html("<img src='<?php echo base_url();?>assets/pre-loader/Ovals in circle.gif' height='20' width='20'>");
      var code=$("#code").val();
      var newp=$("input[name='new_password']").val();
      var conf=$("input[name='new_password_confirm']").val();
      $.ajax({
        type:'POST',
        url: "<?php echo base_url();?>home/recovery_check",
        data:{code:code,newp:newp,conf:conf},
        success:function(response){
                                      $("#wait").html("");
                                      if(response=='0')
                                      {
                                        $("#msg").attr('class','alert alert-danger');
                                        $("#msg").html("Password recovery code doesn't match");
                                      }
                                      else if(response=='1')
                                      {
                                        $("#msg").attr('class','alert alert-danger');
                                        $("#msg").html("Password recovery code is expired");                                        
                                      }
                                      else
                                      {
                                        var string="<div class='alert alert-success'>"+ 
                                          "<p>"+
                                            "Password is updated successfully.<br>"+
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

