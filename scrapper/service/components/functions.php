<?php

    function xss($data){
        return filter_var($data, FILTER_SANITIZE_STRING);
    }

    function replaceVariables($input, $array){
        $output = $input;

        if(@$array){
            foreach ($array as $key => $value) {
                $output = preg_replace('/@'.$key.'/', $value.' ', $output);
            }
        }

        return $output;
    }

    function error($message){
        echo $message;
    }

    function connectDB($config){
        return $db = new db("mysql:host=".$config['database_ip'].";dbname=".$config['database_name'], $config['database_username'], $config['database_password']);
        $db->setErrorCallbackFunction("error");
    }