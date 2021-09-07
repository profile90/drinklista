

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



    </body>

</html>



<?php

if ( isset($_POST["submit"]) ) {

    if ( isset($_FILES["file"])) {
 
             //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) 
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
        else 
        {
            //Print file details
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

            //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) 
            {
                echo $_FILES["file"]["name"] . " already exists. ";
            }
            else 
            {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "meny.csv";
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
                echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
            }
        }
    } 
    else 
    {
        echo "No file selected <br />";
    }
 }
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

 if ( isset($storagename) && $file = fopen( "upload/" . $storagename , "r" ) ) 
 {
  
    
    $array = array_map('str_getcsv', file('upload/'. $storagename));

    $header = array_shift($array);
    
    array_walk($array, '_combine_array', $header);
    
    function _combine_array(&$row, $key, $header) {
      $row = array_combine($header, $row);
    }
    
    $data = [];
    foreach($array as $key => $row) {
        $data[$key] = [];
        foreach($row as $itemKey => $item) {
            $data[$key][":" . $header[$itemKey]] = $item;
        }
        print_r($data[$key]);
        echo '<br>';
        echo '<br>';
        echo '<br>';
    }




/* 
    print_r($header);
    echo '<br>';
    echo '<br>';
    echo '<br>';
    print_r($array);
*/

 }

?>
