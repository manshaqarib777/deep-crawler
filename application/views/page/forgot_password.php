<br>
<style>#recovery_form{text-align:center;}</style>
<style>#wait{padding-top:20px;padding-left:10px;}</style>

  <div class="row row-centered">
    <div class="col-sm-11 col-xs-11 col-md-8 col-lg-8 col-centered border_gray grid_content padded background_white">
    <h6 class="column-title"><i class="fa fa-key fa-2x blue"> Password Recovery</i></h6>
    <div class="account-wall" id='recovery_form'> 
        <div class="form-group">
           <div id='msg'></div>
           <label class="col-xs-12" style="margin-left:0;padding-left:0;">Enter Your Email</label>
           <input required type="email" class="form-control col-xs-12" id="email" placeholder="Enter Your Email">          
        </div>       
        <div class="form-group">
          <button type="button" id="submit" style="margin-top:20px" class="btn btn-warning btn-lg">Send Recovery Data</button>
          <span id='wait' ></span>  
        </div>      
    </div>
  </div>
  </div>



<script type="text/javascript">
$('document').ready(function(){
  $("#submit").click(function(){
    $("#msg").removeAttr('class');
    $("#msg").html("");

    var email=$("#email").val();
    var mobile= $('#mobile').val();
    if(email=='' || mobile == '')
    {
      $("#msg").attr('class','alert alert-warning');
      $("#msg").html("Please enter an email address and mobile number.");
    }
    else
    {
      $("#wait").html("<img src='<?php echo site_url();?>assets/pre-loader/Ovals in circle.gif' height='20' width='20'>");
      $.ajax({
        type:'POST',
        url: "<?php echo site_url();?>home/code_genaration",
        data:{email:email},
        success:function(response){
          $("#wait").html("");
          if(response=='0')
          {
            $("#msg").attr('class','alert alert-danger');
            $("#msg").html("This email is not associated with any member");
          }
          else
          {
            var string="<div class='well'>"+ 
              "<p>"+
                "An email containing a url and a password recovery code is sent to your email.<br>"+
                "Check your inbox and perform the following steps:"+
              "</p>"+
              "<ol>"+
                "<li>Go to the url</li>"+
                "<li>Enter the code</li>"+
                "<li>Reset password</li>"+
              "</ol>"+
              "<h4>The link and the code will expire after 24 hours.</h4>"+
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

