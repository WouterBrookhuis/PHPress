<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once 'phpress/phpress.php';

$displayMode = 0;   //0 is nothing ('index' for now), 1 is a php included page, 2 is a db page
$pageBody = "<h1>Index</h1>";
$pageTitle = "Index";
$pageData = null;

$page = filter_input(INPUT_GET, "page");
$pageId = filter_input(INPUT_GET, "pageId");
$userGet = filter_input(INPUT_GET, "user"); //name

if($page)
{
    switch($page)
    {
        case "createpage": break;
        default: break;
    }
    $pageTitle = $page;
    $pageBody = get_include_contents("pages/" . $page . ".php");
    $displayMode = 1;
}
else if($pageId && filter_var($pageId, FILTER_VALIDATE_INT))
{
    //Go to that page
    $pageData = pp_get_page($pageId);
    if($pageData)
    {
        $pageTitle = $pageData['pageName'];
        $pageBody = $pageData['content'];
        $displayMode = 2;
    }
}
else if($userGet)
{
    $uid = pp_get_user_details_name($userGet)['activeMenu'];
    if($uid)
    {
        $p =  pp_get_menu($uid);
        if($p)
        {
            if(count($p) > 1)
            {
                $pageId = $p[1]['pageId'];
                //Go to that page
                $pageData = pp_get_page($pageId);
                if($pageData)
                {
                    $pageTitle = $pageData['pageName'];
                    $pageBody = $pageData['content'];
                    $displayMode = 2;
                }
            }
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>PHPress - <?php echo $pageTitle ?></title>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1 id="header_title">Site title</h1>
                <?php 
                if(isset($_SESSION['user']['userId']))
                {
                    //From right to left (I blame float)
                    echo '<p><a href="?page=logout"><img class="header_icon" src="phpress/images/icon-exit.png" alt="icon"></a></p>';
                    echo '<p><a href="?page=admin"><img class="header_icon" src="phpress/images/icon-cp.png" alt="icon"></a></p>';
                    if($displayMode === 2 && pp_can_edit_page($pageData['pageId'], $_SESSION['user']['userId']))
                    {
                        echo '<p><a href="?page=editpage&param=' . $pageId . '"><img class="header_icon" src="phpress/images/icon-edit-page.png" alt="icon"></a></p>';
                    }
                    
                    //Debug info
                    echo '<p>' . $_SESSION['user']['userType'] . ' ' . $_SESSION['user']['userName'] . ' with userId ' . $_SESSION['user']['userId'] . ' is logged in</p>';
                }
                else
                {
                    echo '<p><a href="?page=login"><img class="header_icon" src="phpress/images/icon-login.png" alt="icon"></a></p>';
                }
                ?>
            </div>
            <div id="menu">
                <ul>
                    <?php
                    if($displayMode === 2)
                        $pages = pp_get_menu(pp_get_user_details($pageData['authorId'])['activeMenu']);
                    else
                        $pages = isset($_SESSION['user']['userId']) ? pp_get_menu(pp_get_user_details($_SESSION['user']['userId'])['activeMenu']) : null;
                    if($pages)
                    {
                        foreach($pages as $index => $userPage)
                        {
                            if($index === 0)
                                continue;

                            if($displayMode === 2 && $userPage['pageId'] === $pageId)
                                echo '<li><a class="menubar_a_selected" href="?pageId=' . $userPage['pageId'] . '">' . pp_get_page($userPage['pageId'])['pageName'] . '</a></li>';
                            else
                                echo '<li><a class="menubar_a" href="?pageId=' . $userPage['pageId'] . '">' . pp_get_page($userPage['pageId'])['pageName'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <div id="content">
                <?php
                    echo $pageBody;
                ?>
            </div>
            <div id="footer">
                <pre>
                    <?php
                    $_dvtc = pp_get_connected_thread_count()['Value'];
                    if($_dvtc > 1)
                    {
                        pp_write_log("WARNING: Connections greater than 1: " . $_dvtc);
                    }
                    //Trust me, this makes sense
                    mysqli_close(pp_connect());
                    ?>
                </pre>                
            </div>
        </div>
    </body>
</html>
