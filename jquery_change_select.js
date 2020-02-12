<script>
$("#search_district").on('change',function(){        
     var base_url="<?php echo base_url();?>";
     var img_src=base_url+"assets/pre-loader/Fading squares.gif";
     var img= "<img src='"+img_src+ "' alt='Loading...'>";           
     $("#search_area_container").html(img);     
     var district_id=$("#search_district").val();

     if(district_id=='')
      district_id='Null';

      $.ajax
        ({
         type:'POST',
         async:false,
         url:"<?php echo site_url();?>"+'home/thana_select_as_district/'+district_id,
         success:function(response)
         {               
          $("#search_area_container").html(response);
        }                
      });                         
    });
</script>