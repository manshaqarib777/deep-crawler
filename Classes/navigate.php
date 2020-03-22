<?php

class Navigate
{

    public function navigator()
    {
        echo '<div>

<div style="float:left;"><a class="no-lines" href="edit.php"><img style="width:100%;" src="admin-blogify/images/edit_page.jpg" /></a></div>
<div style="float:left;text-align:left;margin-left:10px;">Edit content</div>

<div style="float:left;padding-left:20px;"><a class="no-lines" href="login.php"><img style="width:100%;" src="admin-blogify/images/add_delete_products.jpg" /></a></div><div style="float:left;text-align:left;margin-left:10px;">Update</div>

<div style="float:left;padding-left:20px;margin-bottom:20px"><a class="no-lines" href="logout.php"><img style="width:100%;" src="admin-blogify/images/logout.jpg" /></a></div><div style="float:left;text-align:left;margin-left:10px;">Logout</div>
</div>

<div style="clear:both;"></div>';
    }

}