<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php require_once('standardHead.php')?>
        <title>Login Validation</title>
    
        <?php
        //connect to database


        //$POST is not empty
        if (!empty($_POST)) {
           //UserID and Password are not blank
            if (!$_POST['sparkUserID'] || !$_POST['sparkPassword']){
                //blanks in UserID or Password so goto Login with #EmptyLogin -- see getHash.js:getLoginHash()
                header("Location: http://localhost/BlueSpark/loginOnly.php#EmptyLogin");
                die();
            }
            else {
                dbConnect();
                $checkUserInput = ($_POST['sparkUserID']);
                $checkPasswordInput =hashSalt($_POST['sparkPassword']);

                //create query
                $query1 = sprintf('SELECT idUser, lastname, firstname, alias, password FROM user WHERE alias = "%s" AND password = "%s"',
                    mysql_real_escape_string($checkUserInput),
                    mysql_real_escape_string($checkPasswordInput));

                //send query
                $result1 = mysql_query($query1);

                //if result returns no rows = no matching userID & password found
                if (!mysql_num_rows($result1)) {
                    //no results = goto Login with #IncorrectLogin -- see getHash.js:getLoginHash()
                    header("Location: http://localhost/BlueSpark/loginOnly.php#IncorrectLogin");
                    die();
                    }

                else {
                    
                    //VALID USER = goto user startup
                    $row = mysql_fetch_assoc($result1);
                   /*
                    echo "<br />0. User ";
                    echo $row['idUser']." ";
                    echo $row['firstname']." ";
                    echo $row['lastname']." is ";
                    echo $row['alias']."."."<br \>\n";
                    echo $row['password']."."."<br \>\n";
                    */
                    session_start();
                    $_SESSION['idUser'] = $row['idUser'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];
                    $_SESSION['alias'] = $row['alias'];
                    header("Location: http://localhost/BlueSpark/welcomeUser.php");
                    die(); //goto user startup screen
                }
            }
       }
     ?>
</head>

    <body>
    </body>
</html>
