<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
            
            require_once ('SQL_UtilityFunctions.php');
            
            $conn = dbConnect();
            $locations = getLocations($conn);
            
            echo("<H2> Locations </H2>");
            foreach($locations as $row)
            { 
                echo('<br>');
                foreach($row as $returnValue)
                {
                    echo($returnValue);
                    echo(" ");
                }
            }
            dbDrop($conn);
        ?>
    </body>
</html>
