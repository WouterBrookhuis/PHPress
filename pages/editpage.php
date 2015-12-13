<?php
if(isset($_SESSION['user']['userId']) && isset($_GET['param']))
{
    $pageId = filter_input(INPUT_GET, 'param', FILTER_VALIDATE_INT);
    echo '<p><a href="?page=pagelist">Back to page list</a></p>';
    echo '<p><a href="?pageId=' . $pageId. '">View page</a></p>';
    echo '<h2>Edit a page</h2>';
    if($pageId && pp_can_edit_page($pageId, $_SESSION['user']['userId']))
    {
        if(isset($_POST['submit']))
        {
            $name = filter_input(INPUT_POST, "name");
            $content = filter_input(INPUT_POST, "content");
            if(!empty($name) && !empty($content))
            {
                if(pp_update_page($pageId, $name, $content))
                {
                    echo "<p>Your page was updated! (id:" . $pageId . ")</p>";
                }
                else
                {
                    echo "<p>The page could not be updated!</p>";
                }
            }
        }
        $pageData = pp_get_page($pageId);
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <p>Page name<br><input type="text" name="name" value="<?php echo $pageData['pageName']; ?>"></p>
    <p><textarea name="content" rows="20" cols="40"><?php echo $pageData['content']; ?></textarea></p>
    <p><input type="submit" name="submit" value="Update page"></p>
</form>
<?php
    }
}
?>
