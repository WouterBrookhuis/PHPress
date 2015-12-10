<?php
include_once 'constants.php';

function pp_connect()
{
    $link = @mysqli_connect(PP_MYSQL_HOST, PP_MYSQL_USER, PP_MYSQL_PASS);
    if($link)
    {
        if(!mysqli_select_db($link, PP_DATABASE))
        {
            echo "<p>Could not connect to the database</p>";
            echo "<p>" . mysqli_errno($link) . "</p>";
            echo "<p>" . mysqli_error($link) . "</p>";
            $link = null;
        }
    }
    else
    {
        echo "<p>Could not connect to the database server</p>";
    }
    return $link;
}

function pp_generate_user_token()
{
    return password_hash(time() . "zeus" . rand(0, PHP_INT_MAX), PASSWORD_DEFAULT);
}

function pp_register($userName, $userPass, $userMail)
{
    if(empty($userName) || empty($userPass) || empty($userMail))
        return false;
    
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT userId FROM " . PP_TABLE_USER . " WHERE userName='" . mysqli_real_escape_string($link, $userName) . "' OR userEmail='" . mysqli_real_escape_string($link, $userMail) . "'";
        $result = mysqli_query($link, $sql);
        if(mysqli_fetch_assoc($result))
        {
            echo "<p>This username or email is already registered!</p>";
            return false;
        }
                
        $sql = "INSERT INTO " . PP_TABLE_USER . " VALUES(" . "NULL" . ", "
                . "'" . mysqli_real_escape_string($link, $userName) . "', "
                . "'" . mysqli_real_escape_string($link, password_hash($userPass, PASSWORD_DEFAULT)) . "', "
                . "'" . "user" . "', "
                . "'" . mysqli_real_escape_string($link, $userMail) . "', "
                . "'" . mysqli_real_escape_string($link, pp_generate_user_token()) . "')";
        $result = mysqli_query($link, $sql);
        if($result)
        {
            return true;
        }
        else
        {
            echo "<p>An error occured registering a new user</p>";
            echo "<p>" . mysqli_error($link) . "</p>";
        }
    }
    return false;
}

function pp_validate_user_id($userId)
{
    
}

function pp_login($userName, $userPass)
{
    if(empty($userName) || empty($userPass))
        return false;
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_USER . " WHERE userName='" . mysqli_real_escape_string($link, $userName) . "'";
        $result = mysqli_query($link, $sql);
        if(($user = mysqli_fetch_assoc($result)) !== null) 
        {
            if(password_verify($userPass, $user['userPass']))
            {
                return $user['userId'];
            }
        }
    }
    return false;
}

function pp_create_page($userId, $pageName="New Page", $content="")
{
    $link = pp_connect();
    if($link)
    {
        $sql = "INSERT INTO " . PP_TABLE_PAGE . " VALUES(" . "NULL" . ", "
                . "'" . mysqli_real_escape_string($link, $userId) . "', "
                . "'" . mysqli_real_escape_string($link, $content) . "', "
                . "'" . mysqli_real_escape_string($link, $pageName) . "')";
        $result = mysqli_query($link, $sql);
        if($result) 
        {
            return mysqli_insert_id($link);
        }
    }
    return false;
}

function pp_get_page($pageId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_PAGE . " WHERE pageId=" . $pageId;
        $result = mysqli_query($link, $sql);
        if(($page = mysqli_fetch_assoc($result)) !== null) 
        {
            return $page;
        }
    }
    return false;
}

function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}