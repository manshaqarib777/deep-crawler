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
$cms_id = '';
if (count($_POST) > 0) {
    $top = mysqli_real_escape_string($db, trim($_POST['top']));
//$mid = trim($_POST['mid']);
//$mid = str_replace("</textareatag>", "</textarea>", $mid);
//$mid = mysqli_real_escape_string($db, $mid);
//$footer = mysqli_real_escape_string($db, trim($_POST['footer']));
    $mid = '';
    $footer = '';
    $title = trim($_POST['title']);
    $url = mysqli_real_escape_string($db, trim($_POST['url']));

    $url_ext = ".php";
    if (isset($url)) {
        $url = $url . $url_ext;
    }

    include('includes/banned_urls.php');

    if (!isset($my_blogger_id)) {
        $error_message = "Please make sure you've filled in all the form fields.  ";
    } #now, check for proper formats for all the inputs

    else {

        if (isset($title) && strlen($title) < 3) {
            //$error_message = "Please make sure you have a url of 3 characters or more.";

        } else {
            if (in_array($url, $banned_urls)) {
                die("You cannot use that title! It is saved for system files!");
            } else {

                $success = true; //flag to determine success of transaction

                //start transaction
                $command = "SET AUTOCOMMIT=0";
                $result = mysqli_query($db, $command);
                $command = "BEGIN";
                $result = mysqli_query($db, $command);


                $command = "INSERT INTO cms (top, mid, footer, title, description, keywords, url, date) VALUES ('$top', '','','$title', '', '', '$url', now());";
                $result = mysqli_query($db, $command);
                $last_cms_id = mysqli_insert_id($db);

                if ($result == false) {
                    $success = false;
                }

                if (!$success) {
                    $command = "ROLLBACK";
                    $result = mysqli_query($db, $command);
                    $error_message = "We're sorry, there has been an error or you need to delete the previous file with the same name.";
                } else {
                    $command = "COMMIT";
                    $result = mysqli_query($db, $command);
                }
                $command = "SET AUTOCOMMIT=1"; //return to autocommit
                $result = mysqli_query($db, $command);

                #If successful, redirect
                if ($success) {

                    echo "Changes Successful!";

// here is the source file
                    $file = file_get_contents('page_template_no_header_footer.php', true);

                    $mytitle = $url /*.$ext*/
                    ;

//write or rewrites the file here
                    $fh = fopen($mytitle, 'w+');
                    fwrite($fh, $file);
                    fclose($fh);

                }
            }
        }
    }

    if ($cms_id) {
        ?>
        <h4>Edit Your Homepage:</h4>
    <?php
    } else {

    }


    ?>

    <span class="add-1">
<?php
if (isset($error_message)) {
    echo $error_message;
}
?>
</span>
<?php
}
?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>">
    <table class="add-2">

        <tr>
            <td class="addfiles-td">Complete<br/>File:</td>
            <td class="addfiles-textarea">
                <textarea class="addfiles-textarea" name="top">
                </textarea>

            </td>
        </tr>

        <tr>
            <td class="addfiles-td">Title:</td>
            <td class="addfiles">
                <input type="text" name="title" value="">

            </td>
        </tr>

        <tr class="addfiles-td">
            <td class="addfiles-td">
                URL:
            </td>
            <td class="addfiles">
                <input type="text" name="url" value="">

            </td>
        </tr>


        <tr>
            <td colspan=2 align=center>
                <input type="hidden" name="cms_id" value="<?php echo htmlentities($cms_id); ?>">
                <input type=submit value="SUBMIT">
            </td>
        </tr>
    </TABLE>
    <br>
</form>
</div></div>
<!--Beginning of style-->
</body>
</html>