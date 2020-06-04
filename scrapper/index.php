<?php

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    include 'service/components/pdo.php';
    include 'service/components/functions.php';
    include 'config.php';

    $code   = 'demo-account';
    $domain = 'demo';

    if($code != 'demo-account'){
        $db    = connectDB($config);
        $query = $db->select('purchases', 'purchase_code = "'.$code.'"');
    }

    if($code == 'demo-account'){
        $token = 'live';
    }else if(@$query[0]){
        $token = $query[0]['hash'];
    }else{
        $token = '';
    }
?>
<!DOCTYPE html>
    <html>
    <head>
        <title>Scraper - Fetch URL</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/all.css"> -->

        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/hint.min.css">
        <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo $config['version']; ?>">
        <link rel="icon" href="https://scraper.piktd.com/assets/images/favicon.png" type="image/x-icon"/>
    <!-- Place favicon.ico in the root directory -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic|Lato:400,700,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/animate.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/font-awesome.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/bootstrap.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/normalize.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/main.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/css/pricing.css">
    <link rel="stylesheet" href="https://scraper.piktd.com/aes_website/style.css">
    <script src="https://scraper.piktd.com/aes_website/js/vendor/modernizr-2.8.3.min.js"></script>


        <script type="text/javascript">
            var token = '<?php echo $token; ?>';
            var domain = '<?php echo $domain; ?>';
            var purchase_code = '<?php echo $code; ?>';
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="https://scraper.piktd.com/aes_website/js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
<script src="https://scraper.piktd.com/aes_website/js/plugins.js"></script>
<script src="https://scraper.piktd.com/aes_website/js/bootstrap.js"></script>
<script src="https://scraper.piktd.com/aes_website/js/main.js"></script>
<script src="https://scraper.piktd.com/aes_website/js/jquery.scrollUp.min.js"></script>
<script src="https://scraper.piktd.com/aes_website/js/wow.min.js"></script>
<script src="https://scraper.piktd.com/aes_website/js/smooth-scroll.js"></script>
    </head>
    <body>
        <?php
            if($token){
                include 'views/scraper.php';
            }else{
                include 'views/token.php';
            }
        ?>
    </body>
</html>