<?php


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$rows = [];

try {
    $pdoparam = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    
    $db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);
    
    $stmnt = $db->query('SELECT * FROM list ORDER BY category, name ASC');
    $rows = $stmnt->fetch();
    
    $category_rows = $db->query('SELECT DISTINCT category FROM list')->fetchAll();
    
    $categories = [];
    foreach($category_rows as $category_row) {
        $categories[] = $category_row['category'];
    }
    
    print_r($rows);
    

} catch (Exception $e) {

    die($e->getMessage());
}


?>



<html>


    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/admin.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;900&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/admin.js"></script>
    </head>

    <body>
        <div class="wrapper">

            <h1 class="header">Administrera</h1>
            <h2 class="header">Lägg till ny dryck</h2>
            <div id="form-wrapper">
                <form action="/update.php" id="add">
                    <input type="text" id="name" name="name" placeholder="Namn">
                    <input  type="number" id="internal_prize" name="internal_prize">
                    <input type="number" id="member_prize" name="member_prize">
                    <input type="number" id="external_prize" name="external_prize">
                    <input type="number" id="alcohol_content" name="alcohol_content" step="0.1">
                    <label id="internal" for="internal_prize">Aktivt pris</label>
                    <label id="member" for="member_prize">Medlems pris</label>
                    <label id="external" for="external_prize">Externt pris</label>
                    <label id="percent" for="alcohol_content" >Procent</label>

                    <fieldset form="add" id="stock"> 
                        <label class="label-header" for="in_stock"> Är i lager: </label>
                        <label for="in_stock_false">Ja</label>
                        <input type="radio" id="in_stock_false" name="in_stock" value="1" checked>
                        <label for="in_stock_true">Nej</label>
                        <input type="radio" id="in_stock_true" name="in_stock" value="0">
                    </fieldset>

                    <fieldset form="add" id="new"> 
                        <label class="label-header" for="new"> Är ny: </label>
                        <label for="new">Ja</label>
                        <input type="radio" cid="new_false" name="new" value="1" checked>
                        <label for="new">Nej</label>
                        <input type="radio" id="new_true" name="new" value="0">
                    </fieldset> 
                    <select id="category" name="category">
                        <?php
                            foreach($categories as $category) {
                        ?>
                            <option value="<?= $category ?>"><?= $category ?></option>
                        <?php
                            }

                        ?>
                    </select>
                    <input id="submit" type="submit" value="Lägg till">

                </form>            
            </div>
            <div class="list">
                    


            </div>



            </div>
        </div>

    </body>

</html>


