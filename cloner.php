<?php

include("includes/sessions.inc.php");

if (is_numeric($blogger_id)  && $blogger_login == $mylogin && $blogger_password == $mypassword) {

    if (count($_POST) > 0) {
        $url = htmlspecialchars($_POST['url'], ENT_QUOTES, "utf-8");

        //CREATE CLONE
        shell_exec('sudo httrack http://' . $url . '/  -O "/home/pi/' . $url . '"  -%v -%e0 2>&1;');

        //ZIP CLONE
        shell_exec('sudo zip -r /home/pi/' . $url . '.zip /home/pi/' . $url . '/' . $url . ';');

        //REDIRECT
        // header('Location: http://example.com');

    }

    ?>

    <form name="myform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>"
          method="post">

        <input class="index-second" type="text" name="url"/>

        <input class="index-fourth" type="submit"
               name="mysubmit"
               value="Clone It"/>
    </form>
<?php
} else {
    echo "You are not authenticated. Please login!";
}

?>
    <div class="dloads-clear"></div>
    </div>

    </div>

    </div>
<?php
include_once('footer.php'); // Require the HTML footer.
