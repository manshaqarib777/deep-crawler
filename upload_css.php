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

#first, make sure all fields were submitted
if (!isset($top)) {
    $error_message = "Please make sure you've filled in all the form fields.  ";
} #now, check for proper formats for all the inputs
else {
    if (strlen(isset($mid)) > 50000) {
        $error_message = "Please make sure your bookmark mid is 250 characters or less.";
    } else {

        $success = true; //flag to determine success of transaction
        //start transaction
        $command = "SET AUTOCOMMIT=0";
        $result = mysqli_query($db, $command);
        $command = "BEGIN";
        $result = mysqli_query($db, $command);


        if (is_numeric($cms_id)) {
            include("upload-css.php");
        }

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

            echo "Changes Successful!";
        }
    }
}
// }

/* if ($cms_id) {
  }
  else { */
?>

<?php
include("upload-css.php");
// }
?>
</div></div></div></div>
<?php
include("footer.php");
?>
<!--Beginning of style--></div>
