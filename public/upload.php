

<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;900&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/upload.css">
    </head>
    <body>
        <table width="600" class="list">
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                <tr>
                    <td width="20%">Select file</td>
                    <td width="80%"><input type="file" name="file" id="file" /></td>
                </tr>
                <tr>
                    <td>Submit</td>
                    <td><input type="submit" name="submit" /></td>
                </tr>
            </form>
        </table>

<?php

/*
    FUNCTIONS
*/



function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}


function missingData($item) 
{    
   return $item == "";
} 


function format($data, $headers)
{
   echo "<table id=\"itemTable\">";
   echo '<thead>';
   echo '<tr>';
   echo '<th> Number </th>';
   foreach($headers as $header) 
   {    
       echo "<th>" . $header . "</th>";
   }
   echo '</tr>';
   echo '</thead>';

   foreach($data as $key => $row) 
   {

       if(array_filter($row, "missingData")) 
       {
           echo '<tr class="error"><td>' . ($key + 1) . '</td><td> Saknar information </td></tr>';
           continue;
       }


       echo "<tr>";
       echo '<td>' . ($key + 1) . '</td>';
       foreach($row as $item) 
       {    
           $class = "";
           
           if(strpos($item, "kr")) 
           {
               $class = "error";
           }
           
           echo "<td class=\"" . $class  . "\">" . $item . "</td>";
       }
       echo "</tr>";
   }
   echo "</table>";
}


/**
 * Make sure the fileupload is a valid .csv file.
 * 
 */


if ( isset($_POST["submit"]) ) 
{

    if ( isset($_FILES["file"])) 
    {
 
        //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) 
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
        else 
        {
            //Print file details
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Size: " . (floor($_FILES["file"]["size"] / 1024)) . " Kb<br />";

            //Store file in directory "upload" with a time stamp, titled 'YYYY_MM_DD_H_Mi_S_meny.csv'
            $storagename = date("Y_m_d_h_i_s") ."_meny.csv";
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
            echo "Stored in: " . "upload/" . $storagename . "<br />";
        }
    } 
    else 
    {
        echo "No file selected <br />";
    }
 }


 /**
  * Find the uploaded file, and parse it.
  */


  // Make sure the file exists

 if ( isset($storagename) && $file = fopen( "upload/" . $storagename , "r" ) ) 
 {


    $array = array_map('str_getcsv', file('upload/'. $storagename));
    $header = array_shift($array);
    $data = [];

    $errors = false;
    foreach($array as $key => $row) 
    {
        $data[$key] = [];


        foreach($row as $itemKey => $item) 
        {
            // Check if anything is missing.
            if($item == "" || strpos($item, "kr")) 
            {
                $errors = true;
            }

            $data[$key][$header[$itemKey]] = $item;
        }    
    }

    if($errors) 
    {
        echo '<h1> Du har fel i listan, gör om och gör rätt. </h1>';
    }
    else 
    {
        try 
        {        
        
            $pdoparam = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            
            $db = new PDO('mysql:host=localhost;dbname=drinklista','list','diskkm', $pdoparam);
            

            $query = '
                INSERT INTO list
                VALUES 
                (
                 DEFAULT,
                 :name,           
                 :category,       
                 :alcohol_content,
                 :external_prize, 
                 :internal_prize, 
                 :in_stock,       
                 :new,            
                 :member_prize)
            ';

            $db->beginTransaction();

            $delete = $db->prepare("DELETE FROM list");
            $delete->execute();

            $stmnt = $db->prepare($query);

            foreach($data as $row) {
                $stmnt->execute($row);
            }

            $db->commit();

            echo '<h1> Hurra! Du har nu lagt till: </h1>';

        }
        catch(Exception $e) 
        {
            $db->rollback();
            echo '<h1> Du har fel någonstans, kontakta teknikansvarig</h1>';
            echo '<div class="error">' . $e->getMessage() . '</div>';
        }

        format($data, $header);    
    }
}

?>


</body>

</html>
