<?php
include_once 'constants.php';

session_start();

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

/*
 * Writes $message to the log with a timestamp
 */
function pp_write_log($message)
{
    $logFile = fopen("log.txt", "ab");
    if($logFile)
    {
        fwrite($logFile, date("d-m-Y, H:i:s", time()) . "\t" . $message . "\n");
        fclose($logFile);
    }
}

function pp_get_connected_thread_count()
{
    $return = -1;
    $link = pp_connect();
    if($link)
    {
        $sql = "SHOW STATUS WHERE `variable_name` = 'Threads_connected'";
        $result = mysqli_query($link, $sql);
        if(($r = mysqli_fetch_array($result)) !== null)
        {
            return $r;
        }
    }
    return $return;
}

/*
 * We use this... sort of?
 */
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

function pp_get_user_details($userId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_USER . " WHERE userId=" . mysqli_real_escape_string($link, $userId);
        $result = mysqli_query($link, $sql);
        if(($user = mysqli_fetch_assoc($result)) !== null) 
        {
            return $user;
        }
    }
    return false;
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
                $result = $user;
                $result['password'] = null;
                return $result;
            }
        }
    }
    return false;
}

/*
 * menu(menuId, userId, menuName);
 * menu_item(menuId, pageId);
 */
function pp_create_menu($userId, $menuName='New Menu')
{
    $link = pp_connect();
    if($link)
    {
        $sql = "INSERT INTO " . PP_TABLE_MENU . " VALUES(" . "NULL" . ", "
                . "'" . mysqli_real_escape_string($link, $userId) . "', "
                . "'" . mysqli_real_escape_string($link, $menuName) . "')";
        $result = mysqli_query($link, $sql);
        if($result) 
        {
            return mysqli_insert_id($link);
        }
    }
    return false;
}

function pp_create_menu_item($menuId, $pageId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "INSERT INTO " . PP_TABLE_MENU_ITEM . " VALUES("
                . "'" . mysqli_real_escape_string($link, $menuId) . "', "
                . "'" . mysqli_real_escape_string($link, $pageId) . "')";
        $result = mysqli_query($link, $sql);
        if($result) 
        {
            return mysqli_insert_id($link);
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

function pp_update_page($pageId, $pageName, $content)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "UPDATE " . PP_TABLE_PAGE . " SET "
                . "pageName='" . mysqli_real_escape_string($link, $pageName) . "', "
                . "content='" . mysqli_real_escape_string($link, $content) . "' WHERE pageId=" . mysqli_real_escape_string($link, $pageId);
        return mysqli_query($link, $sql);
    }
    return false;
}

function pp_update_menu($menuId, $menuName, $pageIds)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "UPDATE " . PP_TABLE_MENU . " SET menuName='" . mysqli_real_escape_string($link, $menuName) . "' WHERE menuId=" . mysqli_real_escape_string($link, $menuId);
        mysqli_query($link, $sql);
        $sql = "DELETE FROM " . PP_TABLE_MENU_ITEM . " WHERE menuId=" . mysqli_real_escape_string($link, $menuId);
        mysqli_query($link, $sql);
        if(count($pageIds) > 0)
        {
            $sql = "INSERT INTO " . PP_TABLE_MENU_ITEM . " VALUES";
            foreach($pageIds as $i => $p)
            {
                $sql .= "(" . mysqli_real_escape_string($link, $menuId) . ", " . mysqli_real_escape_string($link, $p) . ")";
                if($i !== count($pageIds) - 1)
                    $sql .= ", ";
            }
            return mysqli_query($link, $sql);
        }
    }
    return false;
}

function pp_delete_page($pageId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "DELETE FROM " . PP_TABLE_PAGE . " WHERE pageId=" . mysqli_real_escape_string($link, $pageId);
        return mysqli_query($link, $sql);
    }
    return false;
}

function pp_get_page_author($pageId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT authorId FROM " . PP_TABLE_PAGE . " WHERE pageId=" . mysqli_real_escape_string($link, $pageId);
        $result = mysqli_query($link, $sql);
        if(($page = mysqli_fetch_assoc($result)) !== null) 
        {
            return $page['authorId'];
        }
    }
    return false;
}

function pp_get_menu_author($menuId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT userId FROM " . PP_TABLE_MENU . " WHERE menuId=" . mysqli_real_escape_string($link, $menuId);
        $result = mysqli_query($link, $sql);
        if(($page = mysqli_fetch_assoc($result)) !== null) 
        {
            return $page['userId'];
        }
    }
    return false;
}

function pp_can_edit_page($pageId, $userId)
{
    if(pp_get_user_details($userId)['userType'] === 'admin' || pp_get_page_author($pageId) === $userId)
        return true;
    return false;
}

function pp_get_page($pageId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_PAGE . " WHERE pageId=" . mysqli_real_escape_string($link, $pageId);
        $result = mysqli_query($link, $sql);
        if(($page = mysqli_fetch_assoc($result)) !== null) 
        {
            return $page;
        }
    }
    return false;
}

function pp_get_menu($menuId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_MENU . " WHERE menuId=" . mysqli_real_escape_string($link, $menuId);
        $result = mysqli_query($link, $sql);
        if(($menu = mysqli_fetch_assoc($result)) !== null) 
        {
            $menuData = array($menu);
            $sql = "SELECT * FROM " . PP_TABLE_MENU_ITEM . " WHERE menuId=" . mysqli_real_escape_string($link, $menuId);
            $result = mysqli_query($link, $sql);
            while(($menuItem = mysqli_fetch_assoc($result)) !== null) 
            {
                array_push($menuData, $menuItem);
            }
            return $menuData;
        }
    }
    return false;
}

function pp_get_user_pages($userId)
{
    $link = pp_connect();
    $pages = array();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_PAGE . " WHERE authorId=" . mysqli_real_escape_string($link, $userId);
        $result = mysqli_query($link, $sql);
        while(($page = mysqli_fetch_assoc($result)) !== null)
        {
            array_push($pages, $page);
        }
        return $pages;
    }
    return false;
}

function pp_get_user_menus($userId)
{
    $link = pp_connect();
    $pages = array();
    if($link)
    {
        $sql = "SELECT * FROM " . PP_TABLE_MENU . " WHERE userId=" . mysqli_real_escape_string($link, $userId);
        $result = mysqli_query($link, $sql);
        while(($page = mysqli_fetch_assoc($result)) !== null)
        {
            array_push($pages, $page);
        }
        return $pages;
    }
    return false;
}

function pp_set_active_menu($userId, $menuId)
{
    $link = pp_connect();
    if($link)
    {
        $sql = "UPDATE " . PP_TABLE_USER . " SET "
                . "activeMenu='" . mysqli_real_escape_string($link, $menuId) . "' WHERE userId=" . mysqli_real_escape_string($link, $userId);
        return mysqli_query($link, $sql);
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
