<?php
session_start();
if(isset($_SESSION['user']))
{
    if(isset($_POST['submit']))
    {
        $name = filter_input(INPUT_POST, "name");
        $content = filter_input(INPUT_POST, "content");
        if(!empty($name) && !empty($content))
        {
            $pageId = pp_create_page($_SESSION['user'], $name, $content);
            if($pageId)
            {
                echo "<p>Your page was created! (id:" . $pageId . ")</p>";
            }
        }
    }
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <p>Page name<br><input type="text" name="name" value="New Page"></p>
    <p><textarea name="content" rows="20" cols="40"></textarea></p>
    <p><input type="submit" name="submit" value="Create page"></p>
</form>
<?php
}
?>
