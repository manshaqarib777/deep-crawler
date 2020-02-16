<?php
if(basename($_SERVER['SCRIPT_NAME']) != 'logout.php'){
session_start();
}
header('Cache-Control: max-age=900');
/*
 * LOGIN.PHP ONLY BEGIN
 */
if (isset($_POST['login'])) {
    if (count($_POST['login']) > 0 && count($_POST['password']) > 0) {
        header("Location: login.php");
    }
}
/*
 * LOGIN.PHP ONLY END
 */
if (!isset($title)) {
    $title = "Sitemakin and Cloner Admin";
}?>
<!DOCTYPE HTML>
<!--
Telephasic 1.1 by HTML5 UP
    html5up.net | @n33co
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title><?php echo str_replace("-", " ", $title); ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="stylesheet" href="admin-blogify/css/style-everywhere.css"/>

    <!--[if lte IE 8]>
    <script src="admin-blogify/css/ie/html5shiv.js"></script><![endif]-->
    <script src="admin-blogify/js/jquery.min.js"></script>
    <script src="admin-blogify/js/jquery.dropotron.min.js"></script>
    <script src="admin-blogify/js/skel.min.js"></script>
    <script src="admin-blogify/js/skel-layers.min.js"></script>
    <script src="admin-blogify/js/init.js"></script>
    <noscript>
        <link rel="stylesheet" href="admin-blogify/css/skel.css"/>
        <link rel="stylesheet" href="admin-blogify/css/style.css"/>
    </noscript>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="admin-blogify/css/ie/v8.css"/><![endif]-->


    <?php if (isset($blogger_id, $mylogin, $blogger_login) && !(is_numeric(
            $blogger_id
        )) || isset($blogger_id) != 1 || $blogger_login != $_SESSION['blogger_login'] || $blogger_password != substr(
            $_SESSION['blogger_password'],
            4
        )
    ) {
    } else {
        ?>
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <?php } ?>
    <script>
        $(document).ready(function () {
            $(".button-clear").click(function () {
                $(this).closest('form').find("input[type=text], textarea").val("");
                $(this).closest('form').find("input[type=text]").val("");
            });
        });
    </script>
</head>

<body class="right-sidebar" style="width:100%">

<!-- Header Wrapper -->
<div id="header-wrapper" class="header-wrapper">

    <!-- Header -->
    <div id="header" class="container">

        <!-- Logo -->
        <h1 id="logo"><a href="#">Sitemakin</a></h1>
        <?php include("menu.inc.php"); ?>
    </div>

</div>


<!-- Main Wrapper -->
<div class="wrapper">

    <div class="container">

        <div class="row" id="main">

            <div class="nineu">
