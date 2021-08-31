<?php


$rows = [];

try {
    $pdoparam = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    
    $db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);

    $stmnt = $db->query("SELECT * FROM list");

    $rows = $stmnt->fetchAll();




} catch (Exception $e) {

    die($e->getMessage());
}


