<?php



$query = "
    SELECT  list.name as alcohol_name,
        list.category as alcohol_category,
        list.alcohol_content,
        list.internal_prize as alcohol_internal_prize,
        list.member_prize as alcohol_member_prize,
        list.external_prize as alcohol_external_prize,
        ingredient.amount,
        recipes.id,
        recipes.name,
        recipes.description,
        recipes.internal_prize,
        recipes.member_prize,
        recipes.external_prize
    FROM ingredient 
    INNER JOIN list ON ingredient.itemId = list.id 
    INNER JOIN recipes ON ingredient.recipeId = recipes.id;
";

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

    
    $stmnt = $db->query($query);

    $drink_rows = $stmnt->fetchAll();

    foreach($drink_rows as $row) {
        $drinks[$row['name']]['item'] = $row;
        $drinks[$row['name']][$row['alcohol_name']] = $row;
    }

/*
    print_r($drink_rows);
    echo '<br>';
    echo '<br>';
    print_r($drinks);
*/


} catch (Exception $e) {

    die($e->getMessage());
}


?>



<html>


    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;900&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link rel="stylesheet" href="css/index.css">



        <script src="js/index.js"></script>
    </head>

    <body>
        <div class="wrapper">

            <h1 class="header">Meny</h1>

            <input id="search" placeholder="Jag vet jag vill ha." type="search"></input>

<!--
            <h2 class="header" data-id="drinks">
                Drinks
                <span class="material-icons"> 
                    expand_less
                </span>
            </h2>
-->
            <?php 
            
            foreach($drinks as $key => $row) {
               
            ?>
            <div class="list special">
                <div id="<?= $row[$key]['id'] ?>" class="item">
                    <span class="percent"></span>
                    <span class="name"><?= $key ?></span>
                    <span class="price-wrapper">
                        <span class="price internal"><?= $row["item"]['internal_prize'] ?> kr</span>
                        <span class="price member"><?= $row["item"]['member_prize'] ?> kr</span>
                        <span class="price external"><?= $row["item"]['external_prize'] ?>kr</span>
                    </span>

            <?php
                foreach($row as $key => $item) {
                    if($key == 'item')
                        continue;
            ?>
                    <div class="extendable">        
                        <div class="alcohol_content">   
                            <span class="name thin"><?= $key ?></span>
                            <span class="amount thin"><?=$item['amount'] ?> cl</span>                        
                            <span class="thin"><?=$item['alcohol_content'] ?>%</span>
                        </div>
                    </div>
            <?php
                }
            ?>
                </div>
            </div>

            <?php
            }
            ?>

            <?php 
            foreach($categories as $category) {
            ?>
                    <h2 class="header" data-id="<?= $category ?>">
                            <?= $category ?>  
                            <span class="material-icons"> 
                                expand_less
                            </span>
                  
          

                    </h2>
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


