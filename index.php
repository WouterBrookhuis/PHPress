<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once 'phpress/phpress.php';

$pageBody = "<h1>Index</h1>";
$pageTitle = "Index";

$page = filter_input(INPUT_GET, "page");
$pageId = filter_input(INPUT_GET, "pageId");
if($page)
{
    switch($page)
    {
        case "createpage": break;
        default: break;
    }
    $pageTitle = $page;
    $pageBody = get_include_contents($page . ".php");
}
else if($pageId && filter_var($pageId, FILTER_VALIDATE_INT))
{
    //Go to that page
    $pageData = pp_get_page($pageId);
    if($pageData)
    {
        $pageTitle = $pageData['pageName'];
        $pageBody = $pageData['content'];
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
                <h1>Site title</h1>
            </div>
            <div id="menu">
                <ul>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                </ul>
            </div>
            <div id="content">
                <?php
                    echo $pageBody;
                ?>
            </div>
            <div id="footer"></div>
        </div>
    </body>
</html>
