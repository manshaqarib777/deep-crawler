<?php
include("includes/sessions.inc.php");

if (is_numeric($blogger_id)  && $blogger_login == $mylogin && $blogger_password == $mypassword) {

    function folderToZip($folder, &$zipFile, $subfolder = null, $subfolder2 = null)
    {
        if ($zipFile == null) {
            // no resource given, exit
            return false;
        }
        // check for main folder
        $folder .= @end(str_split($folder)) == "/" ? "" : "/";
        //echo $folder;
        $subfolder .= @end(str_split($subfolder)) == "/" ? "" : "/"; // check for subfolders
        //echo "sub".$subfolder."sub";
        //$subfolder2 .= end(str_split($subfolder)) == "/" ? "" : "/";
        //echo "sub2".$subfolder2."sub2";

        // we start by going through all files in main folder
        $handle = opendir($folder);
        while ($file = readdir($handle)) {
            if ($file != "." && $file != "..") {
                if (is_file($folder . $file)) {
                    // if we find a file, store it
                    //echo $folder."-".$file;

                    // if we have a subfolder, store it there
                    if ($subfolder != null) {
                        //echo $subfolder;
                        $zipFile->addFile($folder . $file, $subfolder . $file); //adds everytthing to main folder
                        //if(strstr($subfolder,$file)){
                        $zipFile->addFile(
                            $folder . $file,
                            "img/" . $subfolder . $file
                        ); // adds everything to subfolder img
                        //}
                    } // if we hava a sub sub folder
                    else {
                        if ($subfolder2 != null) {
                            $zipFile->addFile($folder . $file, $subfolder2 . $file);
                        } else {
                            $zipFile->addFile($folder . $file);
                        }
                    }
                } elseif (is_dir($folder . $file)) {

                    // if we find a folder, create a folder in the zip
                    $zipFile->addEmptyDir($file);
                    // and call the function again
                    folderToZip($folder . $file, $zipFile, $file);
                }
            }
        }
    }

    $myzip = new ZipArchive();
    $myzip->open("website.zip", ZIPARCHIVE::CREATE);
    folderToZip("html", $myzip);
    $myzip->close();

    echo "<h2>Download Zip</h2>";
    $file_name = "website.zip";
    echo '
					<a class="dload" href="downloads/download_website.php?filename=' . $file_name . '"><b>' . $file_name . '</b></a>';

} else {

    echo "You are not logged in. Please <a href=\"login.php\">login!</a>";
    exit();
}
