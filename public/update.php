<?php




print_r($_REQUEST);
echo '<br>';

$missing = false;
foreach($_REQUEST as $key => $entry) {
    if(empty($entry) && $entry != 0) {
        echo "You're missing: " . $key . " value.<br>";
        $missing = true;
    }
}

if($missing) {
    exit("Your request is missing information");
}


$param = [];

foreach($_REQUEST as $key => $entry) {

    switch($key) {
        case "name":
            $param[":" . $key] = $entry;
            continue;
        break;
        case "internal_prize": 
        case "member_prize": 
        case "external_prize": 
        case "alcohol_content":             
        case "in_stock":
        case "new":
            if(!is_numeric($entry)) {
                exit($entry . " => Wrong type.");
            } 
            $param[":" . $key] = $entry;
        break;
        default:
            continue;
        break;
    }

}


$pdoparam = [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

$db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);

$query = '
    INSERT INTO list
    VALUES 
    (:name,           
     :category,       
     :alcohol_content,
     :external_prize, 
     :internal_prize, 
     :in_stock,       
     :new,            
     :member_prize)
';


/*

$stmnt = $db->prepare($query);
$stmnt = $stmnt->execute($param);

*/