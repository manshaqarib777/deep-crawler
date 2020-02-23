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
        $token = 'demo';
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

        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/all.css">

        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/hint.min.css">
        <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo $config['version']; ?>">

        <script type="text/javascript">
            var token = '<?php echo $token; ?>';
            var domain = '<?php echo $domain; ?>';
            var purchase_code = '<?php echo $code; ?>';
        </script>
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