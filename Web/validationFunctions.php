<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <?php
        
        function validateName($checkString, &$helpText, &$name)
        {
            //function returns true if string is Alpha and non-blank.
            //make sure firstname and lastname are not blank
           if (!empty($checkString))
           {     //make sure firstname and lastname are alpha only
                if(!preg_match("/[^a-zA-Z_-]/",$checkString))
                    {$name = $checkString;
                    $helpText = ("OK");
                    return true;}
                else
                    {$helpText = "Name must include only letters.";
                    $name="";
                    return false;}
           }
           else
               {$helpText = "Name must not be blank.";
               $name="";
               return false;}

        }


        function validateUserName($checkUserName, &$helpText, &$userName)
        {

           if (!empty($checkUserName))
           {     //make sure username is alpha numeric and correct size
                if(!preg_match("/[^\w]/",$checkUserName) && (strlen($checkUserName)>4) && (strlen($checkUserName)<26))
                    {
                        //connect to database and check if username is in db
                      
                        if(isUserIDUnique($checkUserName))
                              {$userName = $checkUserName;
                              $helpText = ("OK");
                              return true;}
                        else
                              //failed Unique
                              {$helpText = "User name must be unique.";
                              $userName = "";
                              return false;}
                    }
                else
                // failed alpha
                {$helpText = "User name must be between 5 and 25 characters";
                $userName ="";
                return false;}
           }
           else
           //failed isEmpty
           {$helpText = "User name must not be blank, between 5 and 25 characters long.";
           $userName = "";
           return false;}
            
        }

        function validateExistingUserName($checkUserName, &$helpText, &$userName, $userID)
        {

           if (!empty($checkUserName))
           {     //make sure username is alpha numeric and correct size
                if(!preg_match("/[^\w]/",$checkUserName) && (strlen($checkUserName)>4) && (strlen($checkUserName)<26))
                    {
                        //connect to database and check if username is in db

                        if(isUserIDUniqueOrSame($checkUserName, $userID))
                              {$userName = $checkUserName;
                              $helpText = ("OK");
                              return true;}
                        else
                              //failed Unique
                              {$helpText = "User name must be unique.";
                              $userName = "";
                              return false;}
                    }
                else
                // failed alpha
                {$helpText = "User name must be between 5 and 25 characters";
                $userName ="";
                return false;}
           }
           else
           //failed isEmpty
           {$helpText = "User name must not be blank, between 5 and 25 characters long.";
           $userName = "";
           return false;}

        }


        function validatePassword($checkPassword, $confirmPassword, &$helpText, &$password)
        {
           if (!empty($checkPassword))
           {   //make sure both passwords are the same
                if ($checkPassword == $confirmPassword)
                    {
                    //make sure username is alpha numeric and correct size
                    if(!preg_match("/[^\w]/",$checkPassword) && (strlen($checkPassword)>3) && (strlen($checkPassword)<25))
                    {$password = $checkPassword;
                    $helpText = ("OK");
                    return true;}
                    else
                    // failed alpha
                    {$helpText = "Password must be between 4 and 24 characters long.";
                    $password="";
                    return false;                    }
                    }
                else
                // failed confirm
                {$helpText = "Passwords failed to match.";
                $password = "";
                return false;}
           }
           else
           //failed isEmpty
           {$helpText = "Password must not be blank, between 4 and 24 characters long.";
           $password ="";
           return false;}
        }
        
        function validateEmail($checkEmail, $confirmEmail, &$helpText, &$email)
        {
            if ($checkEmail == $confirmEmail)
             {
                    //make sure email is of correct form
                    if(preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $checkEmail))
                    {$email = $checkEmail;
                        $helpText = ("OK");
                        return true;
                    }
                    else
                    // failed email regex test
                    {$helpText = "Not a proper email address";
                    $email = "";
                    return false;}
             }
             else
             // failed confirm
             {$helpText = "Email address failed to match";
             $email = "";
              return false;}
        }

        function validateExistingPassword($password, &$helpText, $userID)
        {
            dbConnect();
            $checkPasswordInput = hashSalt($password);

            $query1 = sprintf('SELECT * FROM User WHERE idUser = "%s" AND password = "%s"',
                    mysql_real_escape_string($userID),
                    mysql_real_escape_string($checkPasswordInput));

            $result1 = mysql_query($query1);

            //if result returns no rows = no matching userID & password found
            if (!mysql_num_rows($result1))
            {
                $helpText = ("password does not match");
                return(false);
            }
            else
                return(true);
        }
                
        function validateUserAccount($firstname,$lastname,$alias, $email, &$helptext, &$userID)
        {
            dbConnect();
            

            $query1 = sprintf('SELECT * FROM user u, email e
                    WHERE u.firstname = "%s"
                    AND u.lastname = "%s"
                    AND u.alias = "%s"
                    AND u.idUser = e.fk_Email_User
                    AND e.emailAddress = "%s"',
                    mysql_real_escape_string($firstname),
                    mysql_real_escape_string($lastname),
                    mysql_real_escape_string($alias),
                    mysql_real_escape_string($email));

            $result1 = mysql_query($query1);
            

            //if result returns no rows = no matching userID & password found
            if (!mysql_num_rows($result1))
            {
                $helptext = "user information could not be verified";
                return(false);
            }
            else
            {
                
                $resultRow = mysql_fetch_assoc($result1);
                $userID = $resultRow['idUser'];
                $helpText = "account verified";
                return(true);
            }
        }
        ?>
