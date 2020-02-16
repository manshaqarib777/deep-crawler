<?php
include("includes/sessions.inc.php");

if (is_numeric($blogger_id)  && $blogger_login == $mylogin && $blogger_password == $mypassword) {
    include("Classes/navigate.php");
    $navigator = new Navigate();
    $navigator->navigator();

} else {
    echo "You are not logged in. Please <a href=\"login.php\">login!</a>";
    exit();
}

$db = public_db_connect();
if (count($_POST) > 0) {
    $top = $_POST['top'];
//$mid = $_POST['mid'];
    $footer = mysqli_real_escape_string($db, $_POST['footer']);
}

#first, make sure all fields were submitted
if (!isset($footer)) {
    // $error_message = "Please make sure you've filled in all the form fields.  ";
}
#now, check for proper formats for all the inputs

/* else if (strlen($mid) > 50000) {
  $error_message = "Please make sure your bookmark mid is 250 characters or less.";
  } */ else {
    $success = true; //flag to determine success of transaction
    //start transaction
    $command = "SET AUTOCOMMIT=0";
    $result = mysqli_query($db, $command);
    $command = "BEGIN";
    $result = mysqli_query($db, $command);


    //  if (is_numeric($my_blogger_id)) {
    $command = "SELECT footer FROM cms_footer WHERE id='1';";
    $result = mysqli_query($db, $command);

    if ($data = mysqli_fetch_object($result)) {
        $command = "UPDATE cms_footer SET footer='$footer' WHERE id='1';";
        $result = mysqli_query($db, $command);

        if ($result == false) {
            $success = false;
        }
    }

    // }

    if (!$success) {
        $command = "ROLLBACK";
        $result = mysqli_query($db, $command);
        $error_message = "We're sorry, there has been an error on our end.
                                Please contact us to report this bug.  ";
    } else {
        $command = "COMMIT";
        $result = mysqli_query($db, $command);
    }
    $command = "SET AUTOCOMMIT=1"; //return to autocommit
    $result = mysqli_query($db, $command);

    #If successful, redirect
    if ($success) {
        //header("conten.php");
        echo "Changes Successful!";
    }
}


?>

<span class="footer-1">
        <?
        if ($error_message) {
            echo $error_message;
        }
        ?>
    </span>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>">
    <table class="footer-2">
        <tr>
            <td class="addfiles-td">
                Footer:
            </td>
            <td class="addfiles-textarea">
                <textarea class="addfiles-textarea" name="footer"><?
                    $command = "SELECT footer FROM cms_footer where id='1';";
                    $result = mysqli_query($db, $command);
                    while ($data = mysqli_fetch_object($result)) {
                        echo stripslashes($data->footer);
                    }
                    ?></textarea>
            </td>
        </tr>

        <tr>
            <td colspan=2 align=center>
                <input type=submit value="SUBMIT" onClick="reloadsite()">
                <input type=submit value="REFRESH MENU" onClick="reloadsite()">
            </td>
        </tr>
    </TABLE>
    <br>
</form>


<!--Beginning of style-->
<?php
//$cms_id=2;
?>
</div>
<?php
//include("footer.php");
mysqli_close($db);
//}
?>
<script>
    function reloadsite() {
        setTimeout('window.location.reload()', 2000)
    }
</script>