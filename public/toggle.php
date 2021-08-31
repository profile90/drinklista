<?php 


$pdoparam = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

$db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);


$fetch = $db->prepare("SELECT * FROM list WHERE id=:id");
$stmnt = $db->prepare("UPDATE list SET in_stock = IF(in_stock=1, 0, 1) WHERE id=:id");

$param = array(":id" => $_REQUEST['id']);


$fetch->execute($param);
$row = $fetch->fetch();

if(!empty($row)) {
    $response = $stmnt->execute($param);
    die(json_encode(['id' => $param[":id"], "lastState" => $row["in_stock"], 'response' => $param[":id"] . " has been updated"]));
} else {
    die(json_encode(['response' => "", 'error' => "No such product found."]));
}

