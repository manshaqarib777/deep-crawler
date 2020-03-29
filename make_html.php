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

if (count($_POST) > 0) {
    $top = mysqli_real_escape_string($db, $_POST['top']);
    $mid = mysqli_real_escape_string($db, $_POST['mid']);
    $footer = mysqli_real_escape_string($db, $_POST['footer']);
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $url = mysqli_real_escape_string($db, $_POST['url']);
}

#first, make sure all fields were submitted
if (!($my_blogger_id)) {
    $error_message = "Please make sure you've filled in all the form fields.  ";
} else {
    $success = true; //flag to determine success of transaction
    //start transaction
    $command = "SET AUTOCOMMIT=0";
    $result = mysqli_query($db, $command);
    $command = "BEGIN";
    $result = mysqli_query($db, $command);


    $dir = getcwd();

    $files1 = scandir($dir);
    foreach ($files1 as $file) {
        if (strlen($file) >= 3) {
            $foil = strstr($file, 'php'); // As of PHP 5.3.0

            if ($foil == true) {
                //echo $file."<br/>";
                $file_array[] = $file;
            }
        }
    }

    $command = "SELECT DISTINCT url FROM cms ORDER BY cms_id DESC ";
    $result = mysqli_query($db, $command);
    while ($row = mysqli_fetch_assoc($result)) {
        $url = $row['url'];

        if (!in_array($url, $file_array)) {

            $command2 = "DELETE FROM cms WHERE url = '$url'";
            $result2 = mysqli_query($db, $command2);
            continue;
        }

        $urls[] = $url;
    }


    if ($result == false) {
        $success = false;
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

    #If successful, do this
    if ($success) {
        if (!empty($urls)) {
            foreach ($urls as $url) {
                //echo $url;
                if ($url == "downloads.php") {
                    continue;
                }

                $myurl = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                $myurl = str_replace("make_html.php", $url, $myurl);
                $htmla = file_get_contents($myurl);
                $html = str_replace(".php", ".html", $url);

                $file = "html/" . $html;
                $fp = fopen($file, 'w');
                fwrite($fp, $htmla);

                fclose($fp);
            }
        }

        echo "Changes Successful!";
        echo "<br/> Please note that you can also clone a website with cloner.php. Alternatively, you can use any Linux machine and run httrack to clone a website.";
    }
    // images folder creation
    $mydir = dirname(__FILE__) . "/html/images";
    if (!is_dir($mydir)) {

        mkdir("html/images");
    }
    // Move all images files
    $files = array();
    $files = glob("images/*.*");
    if (!empty($files) && count($files) > 0) {
        // print_r($files);
        foreach ($files as $file) {
            $file_to_go = str_replace("images/", "html/images/", $file);
            copy($file, $file_to_go);
        }
    }
    // images 2 folder creation
    $mydir2 = dirname(__FILE__) . "/html/img";
    if (!is_dir($mydir2)) {
        mkdir("html/img");
    }
    // Move all img files
    $files = array();
    $files = glob("img/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("img/", "html/img/", $file);
            copy($file, $file_to_go);
        }
    }
    ## begin img subdirectories
    // subdirectory 1 creation
    $mydir2aa = dirname(__FILE__) . "/html/img/stock";
    if (!is_dir($mydir2aa)) {
        mkdir("html/img/stock");
    }
    // Move all img files
    $files = array();
    $files = glob("img/stock/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("img/stock/", "html/img/stock/", $file);
            copy($file, $file_to_go);
        }
    }
// subdirectory 2 creation
    $mydir2a = dirname(__FILE__) . "/html/img/placeholders";
    if (!is_dir($mydir2a)) {
        mkdir("html/img/placeholders");
    }
    // Move all img files
    $files = array();
    $files = glob("img/placeholders/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("img/placeholders/", "html/img/placeholders/", $file);
            copy($file, $file_to_go);
        }
    }
// subdirectory 3 creation
    $mydir2b = dirname(__FILE__) . "/html/img/nivo";
    if (!is_dir($mydir2b)) {
        mkdir("html/img/nivo");
    }
    // Move all img files
    $fiels = array();
    $files = glob("img/nivo/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("img/nivo/", "html/img/nivo/", $file);
            copy($file, $file_to_go);
        }
    }
// subdirectory 4 creation
    $mydir2b = dirname(__FILE__) . "/html/img/colorbox";
    if (!is_dir($mydir2b)) {
        mkdir("html/img/colorbox");
    }
    // Move all img files
    $files = array();
    $files = glob("img/colorbox/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("img/colorbox/", "html/img/colorbox/", $file);
            copy($file, $file_to_go);
        }
    }
    ## end img subdirectories
    // css folder creation
    $mydir3 = dirname(__FILE__) . "/html/css";
    if (!is_dir($mydir3)) {
        mkdir("html/css");
    }
    // Move all css files
    $files = array();
    $files = glob("css/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("css/", "html/css/", $file);
            copy($file, $file_to_go);
        }
    }
    // Move root css files
    $files = array();
    $files = glob("*.css*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("", "html/", $file);
            copy($file, $file_to_go);
        }
    }
    // js folder creation
    $mydir4 = dirname(__FILE__) . "/html/js";
    if (!is_dir($mydir4)) {
        mkdir("html/js");
    }
    // Move all js files
    $files = array();
    $files = glob("js/*.*");
    if (!empty($files) && count($files) > 0) {
        foreach ($files as $file) {
            $file_to_go = str_replace("js/", "html/js/", $file);
            copy($file, $file_to_go);
        }
    }
}

/*
$command = "SELECT top FROM cms where
cms_id=2;";
$result = mysqli_query($db, $command);
while ($data = mysqli_fetch_object($result)) {
    echo stripslashes($data->top);
}

$command = "SELECT mid FROM cms where 
cms_id='$cms_id';";
$result = mysqli_query($db, $command);
while ($data = mysqli_fetch_object($result)) {
    echo stripslashes($data->mid);
}
*/
?></div></div></div>
<!--Beginning of style-->
<?php include("footer.php");
