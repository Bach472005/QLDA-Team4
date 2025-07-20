<?php

// Hỗ trợ show bất kỳ data nào
function debug($data)
{
    echo "<pre>";

    print_r($data);

    die;
}

// Kết nối CSDL qua PDO
function connect_db(){
    $hostname=DB_HOST;
    $dbname=DB_NAME;
    try{
        $conn= new PDO("mysql:host=$hostname;dbname=$dbname",DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        // $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $conn;
    }
    catch(PDOException $e){
        debug("connect-faile".$e->getMessage());
    }
}
