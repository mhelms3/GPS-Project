<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function hashSalt($any)
{
    $salt1 = "^e%g@g!";
    $salt2 = "12@07!69";

    $token = md5("$salt1$any$salt2");
    return($token);
}

?>
