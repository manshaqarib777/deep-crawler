<?php

if (!(is_numeric(
        $my_blogger_id
    )) || $my_blogger_id != 1 || $blogger_login != $mylogin || $blogger_password != $mypassword
) {
    die("Sorry, you are not logged in.");
}

##START DLOADS
$counter = 1; //This number sets the amount of upload and description boxes

if (isset($_POST['mysubmit']) && !empty($_POST['myhid']) && $_POST['submit']) {

    /*
    * Only Allow One Dot In The filename
     * PDF, txt, jpeg, gif, png, html, javascript and css
    */
    if (!preg_match(
        '/^[a-zA-Z0-9_]+([a-zA-Z0-9_]*(\-)*[a-zA-Z0-9_]*)+(\.|){1}(jpg|jpeg|png|gif|bmp|html|htm|txt|js|css)$/i',
        $_FILES["upload"]["name"]
    )
    ) {
        die("All You Need Is Love Love ... love is all you need!");
    }

   // for ($i = 0; $i < $counter; $i++) {

        $filename = 'upload' ;
        $description = 'description' ;
        $category = 'category';
        $directory = 'css';


        if (isset($_FILES[$filename]) && ($_FILES[$filename]['error'] != 4)) {

            // file needs to be pdf or word doc and 1 MB max
            if (($_FILES[$filename]['type'] == "application/pdf" || $_FILES[$filename]['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $_FILES[$filename]['type'] == "application/zip" || $_FILES[$filename]['type'] == "application/x-zip" || $_FILES[$filename]['type'] == "application/x-zip-compressed" || $_FILES[$filename]['type'] == "application/multipart/x-zip" || $_FILES[$filename]['type'] == "application/application/x-compress" || $_FILES[$filename]['type'] == "application/application/x-compressed" || $_FILES[$filename]['type'] == "application/application/octet-stream" || $_FILES[$filename]['type'] == "text/xml" || $_FILES[$filename]['type'] == "text/plain" || $_FILES[$filename]['type'] == "text/html" || $_FILES[$filename]['type'] == "text/css" || $_FILES[$filename]['type'] == "application/x-javascript" || $_FILES[$filename]['type'] == "image/jpeg" || $_FILES[$filename]['type'] == "image/pjpeg" || $_FILES[$filename]['type'] == "image/jpg" || $_FILES[$filename]['type'] == "image/pjpg" || $_FILES[$filename]['type'] == "image/gif" || $_FILES[$filename]['type'] == "image/x-png") && ($_FILES[$filename]["size"] < 1000000)) {

                if (!empty($_POST[$description])) {
                    $desc = mysqli_real_escape_string($db, $_POST[$description]);
                } else {
                    $desc = 'None';
                }

                $name = $_FILES[$filename]['name'];
                $size = $_FILES[$filename]['size'];
                $type = $_FILES[$filename]['type'];
                if(isset($_POST['category'])){
                $cat = mysqli_real_escape_string($db, $_POST['category']);
                }

                if (move_uploaded_file($_FILES[$filename]['tmp_name'], "$directory/$name")) {

                    echo '<p><span class="upload-css">File name ' . $name . ' has been uploaded!</span></p>';
                    echo '<p><span class="upload-css"><a href="' . $_SERVER["PHP_SELF"] . '">Upload another file </a></span></p>';

                    echo '<p><span class="upload-css"><a href="' . $_SERVER["PHP_SELF"] . '">Back to downloads.</a></span></p>';
                } else {

                    echo '<p><font color="red">File number ' . ($i + 1) . ' could not be moved.</font></p>';

                    $query = "DELETE FROM uploads WHERE upload_id = $upload_id";
                    $result = mysqli_query($db, $query);
                }

            } else {
                echo '<p><span class="upload-css">We only accept the following documents:<br/>Word, PDF, txt, jpeg, gif, png, html, javascript and css that are less than 1 mb!</span></p>';
                echo '<p><span class="upload-css"><a href="' . $_SERVER[PHP_SELF] . '">Try again</a></span></p>';
            }
        }
   // }

    // exit();
} else {
//echo "Please add a description";
    ?>
    <form enctype="multipart/form-data"
          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>"
          method="post">

        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <input type="hidden" name="myhid" value="hi">
        <style type="text/css">
            textarea#ta {
                width: 450px;
                display: block;
                border-radius: 10px;
                border: 1px solid #36624C;
                padding: 15px;
                font-family: arial;
                height: 50px;
            }
        </style>
        <?php
       // for ($i = 0; $i < $counter; $i++) {
            $command_cat = "SELECT DISTINCT category FROM uploads_categories ORDER BY category ASC ";
            $result_cat = mysqli_query($db, $command_cat);
            while ($row = mysqli_fetch_assoc($result_cat)) {
                $filecat = $row['category'];
                $cats[] = $filecat;
            }
            echo '<div class="upload-css-2"><strong><span class="upload-css-3">UPLOAD CSS FILE (File should have a name with 3 letters or more)</span></strong></div><div class="dloads-clear"></div>';

            echo '<div class="upload-css-5">
		<div class="upload-css-6"><p class="upload-css-7"><b>Upload Directory:</b><br/> <input type="text" name="dir" value="" /></p>';

            echo '<div class="upload-css-8">
		<div class="upload-css-9"><p class="upload-css-7"><b>Upload File:</b> <input type="file" name="upload" /></p>

	</div><div class="dloads-clear"></div>

	';
            //echo "The for loop is done twice";
      //  }
        ?>
        <input type="hidden" name="mysubmit" value="TRUE"/>

        <div align="left"><input type="submit" class="upload-css-10" name="submit" value="Submit"/></div>
    </form></div><br/><br/><strong>CURRENT CSS FILES:</strong><br/><?php
    if (isset($_POST['dir'])) {
        $dir = $_POST['dir'];
    } else {
        $dir = getcwd();
        //echo $directory;
    }
//$dir    = $directory;
    $files1 = scandir($dir);
    foreach ($files1 as $file) {
        if (strlen($file) >= 3) {
            $foil = strstr($file, '.css'); // As of PHP 5.3.0
//$pos = strpos($file, 'css');
            if ($foil == true) {
                echo $file . "<br/>";
            }
        }
    }
}

mysqli_close($db);
?>
</div><br/>
