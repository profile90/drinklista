<?php


$rows = [];

try {
    $pdoparam = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    
    $db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);
    
    $stmnt = $db->query('SELECT * FROM list ORDER BY category, name ASC');
    $rows = $stmnt->fetchAll();
    
    $items = [];

    $category_rows = $db->query('SELECT DISTINCT category FROM list')->fetchAll();
    $categories = [];
    foreach($category_rows as $category_row) {
        $categories[] = $category_row['category'];
    }

    foreach($rows as $row) {
        $items[$row['category']][] = $row;
    }


} catch (Exception $e) {

    die($e->getMessage());
}


?>



<html>


    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;900&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="wrapper">

            <h1 class="header">Drinklista</h1>

            <input id="search" placeholder="Jag vet jag vill ha." type="search"></input>

            <?php 
            foreach($categories as $category) {
            ?>
                    <h2 class="header" data-id="<?= $category ?>"><?= $category ?></h2>
                    <div class="list" id="<?= $category ?>">
            <?php
                    foreach($items[$category] as $item) {
            ?>
                        <div id="<?= $item["id"] ?>" class="item <?= (($item['in_stock']) ? "" : "not_in_stock") ?>">
                                <span class="percent"><?= (($item['alcohol_content'] == 0) ? "" : ($item['alcohol_content'] . "%")) ?></span>
                                <span class="name"><?= $item['name'] ?></span>
                                <span class="price-wrapper">
                                    <span class="price internal"><?= $item['internal_prize'] ?> kr</span>
                                    <span class="price member"><?= $item['member_prize'] ?> kr</span>
                                    <span class="price external"><?= $item['external_prize'] ?> kr</span>
                                <span>
                                <span></span>
                        </div>
                    
            <?php 
                }
            ?>
                    </div>
            <?php 
            }
            ?>

            <h3 class="header">Kan du inte bestämma dig?</h3>
            <div id="random-deposit" class="list"></div>
            <button id="random"> Välj åt mig! </button>
            

            </div>
        </div>

    </body>

</html>


