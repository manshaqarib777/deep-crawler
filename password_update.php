<?php
include("includes/sessions.inc.php");

if (is_numeric($blogger_id)  && $blogger_login == $mylogin && $blogger_password == $mypassword) {

    if (count($_POST) > 0) {
        if (count($_POST['new_password']) > 0) {

            $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
            $command = "UPDATE raspberry_logins SET password = sha1('" . $new_password . "') WHERE my_blogger_id = 1 ";
            $result = mysqli_query($db, $command);
            if ($result == true) {
                ?>
                <div class="edit-1">PASSWORD SUCCESSFULLY UPDATED</div><br/>
            <?php
            }
        }
    }
    include("Classes/navigate.php");
    $navigator = new Navigate();
    $navigator->navigator();

    ?>
    <div class="dloads-27">
        <form action="<?php echo $_SERVER[SCRIPT_NAME]; ?>" method="POST">
            <div class="password-update"><input class="pix-fourteen" type="password" name="new_password"
                                                value=""/></div>
            <div class="dloads-28"><input class="pix-fourteen" type="submit" name="submit_type" value="Update"/></div>
        </form>
    </div>

<?php

} else {
    echo "You are not logged in. Please <a href=\"login.php\">login!</a>";
    exit();
}
?>
    </div></div></div>
<?php
include("footer.php");
