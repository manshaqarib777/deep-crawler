<?php

$server_base_url = '';
if (file_exists(dirname(__DIR__) . '\.dev')) { //Amit's Windows box
    $server_base_url = 'http://localhost/work/et/scraper-html/visual-editor/';
    //windows
    $config = array(
        'version' => rand(0, 10000),
        'site' => $server_base_url,
        'database_ip' => 'localhost',
        'database_name' => 'scraper',
        'database_username' => 'root',
        'database_password' => ''
    );
} elseif (file_exists(dirname(__DIR__) . '/.dev-vikas')) { //MAC
    $server_base_url = 'http://scraper.site.local/visual-editor/';
    $config = array(
        'version' => rand(0, 10000),
        'site' => $server_base_url,
        'database_ip' => 'localhost',
        'database_name' => 'scraper',
        'database_username' => 'scraper',
        'database_password' => 'scraper'
    );
} else {
    $server_base_url = 'https://scraper.site/visual-editor/';
    $config = array(
        'version' => rand(0, 10000),
        'site' => $server_base_url,
        'database_ip' => 'localhost',
        'database_name' => 'scraper',
        'database_username' => 'scraper',
        'database_password' => 'asdaQakjb339#'
    );
}


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}