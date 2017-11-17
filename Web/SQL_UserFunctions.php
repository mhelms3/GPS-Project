<?php

function updatePassword_SQL($userID, $password)
{
    $conn = dbConnect();
    $query1 = sprintf('UPDATE user SET password="%s"
                       WHERE idUser = %d',
            mysql_real_escape_string($password),
            mysql_real_escape_string($userID));
    $result1 = mysql_query($query1);
    if(!$result1)
            return($query1.'Failed to update password');
    else
        return(true);
}

function updateRegistration_SQL($userID,$firstname,$lastname,$alias,$password,$email,$groupAffiliation,$groupID,$SRBackground)
{
    $conn = dbConnect();
    $query1 = sprintf('UPDATE user SET lastname="%s", firstname="%s", alias="%s", background="%s"
                       WHERE idUser = %d',
            mysql_real_escape_string($lastname),
            mysql_real_escape_string($firstname),
            mysql_real_escape_string($alias),
            mysql_real_escape_string($SRBackground),
            mysql_real_escape_string($userID));
    $result1 = mysql_query($query1);
    if(!$result1)
            return($query1.'Failed to update user row');

    $query2 = sprintf('UPDATE email SET emailAddress="%s"
                       WHERE fk_Email_User = %d',
            mysql_real_escape_string($email),
            mysql_real_escape_string($userID));

    $result2 = mysql_query($query2);
    if(!$result2)
            return($query2.'Failed to update email');

    return(true);
    
}

function getUser_SQL($userID)
{
 //given a user ID, returns userRecord
     $conn = dbConnect();
    $UserNames = sprintf('SELECT * FROM user
                           WHERE idUser = %d', $userID);

    $resultUserNames = mysql_query($UserNames);
    if(!mysql_num_rows($resultUserNames))
    {
        return("NULL");
      
    }
    else
    {   
        $rowUser = mysql_fetch_row($resultUserNames);
        
    }
    return ($rowUser);
}

function getEmail_SQL($userID)
{
 //given a user ID, returns userRecord
    $conn = dbConnect();
    $query1 = sprintf('SELECT e.* FROM email e
                       WHERE e.fk_Email_User = %d'
                       , $userID);

    $result = mysql_query($query1);
    if(!mysql_num_rows($result))
    {
        return("NULL");

    }
    else
    {
        $rowEmail = mysql_fetch_assoc($result);

    }
    return ($rowEmail);
}

function getUserName_SQL($userID)
{
    //given a user ID, returns firstName, lastName
     $conn = dbConnect();
    $UserNames = sprintf('SELECT firstName, lastName FROM user
                           WHERE idUser = %d', $userID);

    $resultUserNames = mysql_query($UserNames);
    if(!mysql_num_rows($resultUserNames))
    {
        $userFirstName = "NULL";
        $userLastName = "NULL";
    }
    else
    {   
        $rowUserNames = mysql_fetch_row($resultUserNames);
        $userFirstName=$rowUserNames[0];
        $userLastName=$rowUserNames[1];
    }
    return array($userFirstName, $userLastName);
}


function getUserImage_SQL($userID)
{
    //given a user ID, returns the image of the User
     $conn = dbConnect();
    $UserImageQuery = sprintf('SELECT c.fileaddress FROM  user, attachments b, images c
                WHERE user.idUser = %d
                AND user.fk_imageFile = b.idAttachments
                AND b.fk_type =2
                AND b.idAttachments = c.fk_Attachments', $userID);
    $resultUserImage = mysql_query($UserImageQuery);
    if(!mysql_num_rows($resultUserImage))
    {
        $UserImage = "Unknown";
    }
    else
    {
        $rowUserImage = mysql_fetch_row($resultUserImage);
        $UserImage = $rowUserImage[0];            
    }
    return $UserImage;
}

function getGroupAff ($userID){
     $conn = dbConnect();
            $UserGroupQuery = sprintf('SELECT b.groupName FROM  User_to_Group a, Group_Affiliation b
                WHERE a.fk_User_to_Group = %d
                AND a.fk_Group_to_User = b.idGroup'
                , $userID);

            $resultUserGroup = mysql_query($UserGroupQuery);
            if(!mysql_num_rows($resultUserGroup))
            {
                $UserGroup = "Unknown";
            }
            else
            {
                $rowUserGroup = mysql_fetch_assoc($resultUserGroup);
                $UserGroup = $rowUserGroup['groupName'];           
                echo($UserGroup);
            }           
            return $UserGroup;
}

function getGroupID ($groupName)
{
        $conn = dbConnect();
        $query1 = sprintf('SELECT idGroup FROM group_affiliation WHERE groupName = "%s"', 
            mysql_real_escape_string($groupName));
        
        $resultGroup = mysql_query($query1);
        
        
        if (!$resultGroup)
            {die('Failure at getGroupID for '.$groupName);}
        else
        {
            $groupRow = mysql_fetch_assoc($resultGroup);
            $groupId = $groupRow["idGroup"];
            return($groupId);
        }
}

function getGroupMembers_SQL ($userID)
{
    //will return an array of all members of the associated group
    $conn = dbConnect();
        $query1 = sprintf(
            'SELECT a.* FROM user a, user_to_group b 
             WHERE a.idUser = b.fk_User_to_Group 
             AND b.fk_Group_to_User = 
                (SELECT fk_Group_to_User FROM user_to_group 
                 WHERE fk_User_to_Group = %d) ORDER BY a.lastname, a.firstname ASC', 
            mysql_real_escape_string($userID));
        
        $resultGroup = mysql_query($query1);
        
        
        if (!$resultGroup)
            {die('Failure at getGroupMembers for '.$userID);}
        else
        {
            while($rowsGroupMembers[] = mysql_fetch_assoc($resultGroup));
            array_pop($rowsGroupMembers);
                //var_dump($rowsGroupMembers);
            return($rowsGroupMembers);
        }
}

function getGroupMembersLessProject_SQL ($userID, $projectID)
{
    //will return an array of all members of the associated group, 
    //
    //less members associated with a project
    $conn = dbConnect();
        $query1 = sprintf(
                'SELECT a.* FROM user a, user_to_group b 
                WHERE a.idUser = b.fk_User_to_Group 
                AND b.fk_Group_to_User = 
                    (SELECT fk_Group_to_User FROM user_to_group 
                    WHERE fk_User_to_Group = %d) 
                AND a.idUser NOT IN
                    (SELECT  a.idUser
                    FROM user a, project_to_user c
                    WHERE a.idUser = c.fk_User_Project
                    AND c.projectID = %d)
                ORDER BY a.lastname, a.firstname ASC', 
            mysql_real_escape_string($userID),
            mysql_real_escape_string($projectID));
        
        $resultGroup = mysql_query($query1);
        
        
        if (!$resultGroup)
            {die('Failure at getGroupMembersLessProject for user '.$userID.' project '.$projectID);}
        else
        {
            while($rowsGroupMembers[] = mysql_fetch_assoc($resultGroup));
            array_pop($rowsGroupMembers);
                //var_dump($rowsGroupMembers);
            return($rowsGroupMembers);
        }
}

function createGroupList ($listName, $listMembers)
{
    for ($i = 0; $i<count($listMembers); $i++)
    {
        echo('<option id = "'.$listName.$i.'" value = "'.$listMembers[$i]['alias'].'">'.$listMembers[$i]['lastName'].', '.$listMembers[$i]['firstName'].'</option>');
    }
    return;
}?>