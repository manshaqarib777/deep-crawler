<?php
if (ISSET($_POST['mysubmit'])) {

    /*
    * Only Allow One Dot In The filename
    */
    if (!preg_match(
        '/^[a-zA-Z0-9_]+([a-zA-Z0-9_]*(\-)*[a-zA-Z0-9_]*)+(\.|){1}(jpg|jpeg|png|gif|bmp)$/i',
        $_FILES["img_upload"]["name"]
    )
    ) {
        die("All You Need Is Love Love ... love is all you need!");
    }

    if (($_FILES["img_upload"]["type"] == "image/jpeg" || $_FILES["img_upload"]["type"] == "image/pjpeg" || $_FILES["img_upload"]["type"] == "image/jpg" || $_FILES["img_upload"]["type"] == "image/pjpg" || $_FILES["img_upload"]["type"] == "image/gif" || $_FILES["img_upload"]["type"] == "image/x-png" || $_FILES["img_upload"]["type"] == "image/png") && ($_FILES["img_upload"]["size"] < 1000000)) {
        $max_upload_width = 2592;
        $max_upload_height = 1944;

        if (isset($_REQUEST['max_img_width']) and $_REQUEST['max_img_width'] != '' and $_REQUEST['max_img_width'] <= $max_upload_width) {
            $max_upload_width = $_REQUEST['max_img_width'];
        }
        if (isset($_REQUEST['max_img_height']) and $_REQUEST['max_img_height'] != '' and $_REQUEST['max_img_height'] <= $max_upload_height) {
            $max_upload_height = $_REQUEST['max_img_height'];
        }

        if ($_FILES["img_upload"]["type"] == "image/jpeg" || $_FILES["img_upload"]["type"] == "image/pjpeg" || $_FILES["img_upload"]["type"] == "image/jpg" || $_FILES["img_upload"]["type"] == "image/pjpg") {
            $image_source = imagecreatefromjpeg($_FILES["img_upload"]["tmp_name"]);
        }

        if ($_FILES["img_upload"]["type"] == "image/gif") {
            $image_source = imagecreatefromgif($_FILES["img_upload"]["tmp_name"]);
        }

        if ($_FILES["img_upload"]["type"] == "image/x-png" || $_FILES["img_upload"]["type"] == "image/png") {
            $image_source = imagecreatefrompng($_FILES["img_upload"]["tmp_name"]);
        }

        $remote_file = "images/" . $_FILES["img_upload"]["name"];
        //echo $remote_file;
        imagejpeg($image_source, $remote_file, 100);
        chmod($remote_file, 0644);

        list($image_width, $image_height) = getimagesize($remote_file);

        if ($image_width > $max_upload_width || $image_height > $max_upload_height) {
            $proportions = $image_width / $image_height;

            if ($image_width > $image_height) {
                $new_width = $max_upload_width;
                $new_height = round($max_upload_width / $proportions);
            } else {
                $new_height = $max_upload_height;
                $new_width = round($max_upload_height * $proportions);
            }

            $new_image = imagecreatetruecolor($new_width, $new_height);
            $image_source = imagecreatefromjpeg($remote_file);

            imagecopyresampled(
                $new_image,
                $image_source,
                0,
                0,
                0,
                0,
                $new_width,
                $new_height,
                $image_width,
                $image_height
            );
            imagejpeg($new_image, $remote_file, 100);

            imagedestroy($new_image);
        }

        imagedestroy($image_source);
    } else {

    }
}
?>
<form name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>"
      enctype="multipart/form-data">

    <label>Maximum 1MB. Accepted Formats: jpg, gif and png:</label><br/>
    <input name="img_upload" type="file" id="img_upload" size="40"/>
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>


    Width:
    <input name="max_img_width" type="visible" value="130" size="4">

    Height:
    <input name="max_img_height" type="visible" value="54" size="4">
    <input name="tmp_name" type="hidden" value="myfile.jpg">

    <input type="submit" name="mysubmit" value="Submit">

</form>
