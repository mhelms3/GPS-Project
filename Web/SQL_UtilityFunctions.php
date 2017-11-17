<?php

function dbConnect()
{
            $dbhost = 'localhost';
            $dbuser = 'base';
            $dbpass = 'bytezilla';
            //consider in the $conn call, the local mysql_connect is using a CLIENT_LONG_PASSWORD flage
            //it began throwing an error "Cannot connect to MySQL 4.1+ using old authentication" seemingly at random
            //i do not recall any update which may have affected this
            //see http://www.php.net/manual/en/mysql.constants.php#mysql.client-flags for details on the flag
            //$conn = mysqli_connect($dbhost, $dbuser, $dbpass, false, 1) or die ('Sorry.  There was an error connecting to database.  Please try back later.');
            $link = mysqli_connect('localhost', 'root', '', 'gamegps') or die ('Sorry.  There was an error connecting to database1.  Please try back later.');
            //echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
            //$dbname = 'gamegps';
            //mysql_select_db($dbname) or die('Sorry.  There was an error connecting to database.  Please try back later.');
            return $link;
}

function dbDrop($conn)
{
        mysqli_close($conn);
}
        
function getLocations($link)
{
        $query1 = sprintf('SELECT *
                           FROM locations');
        $results = mysqli_query($link, $query1);
        if(!$results)
                //if no result, return FAIL
                die($query1.'***Database Error: Failed to find locations');
        else
                if(mysqli_num_rows($results) >0)
                {
                    //$row = mysqli_fetch_assoc($results);
                    return($results);
                }
        else
                die($query1."***Database Error: no locations");
}


?>