<?php
if(isset($_SESSION['user']['userId']))
{
    echo '<p><a href="?page=admin">Back to admin panel</a></p>';
    echo '<h2>Your pages</h2>';
    if(isset($_POST['submit']))
    {
        $name = filter_input(INPUT_POST, "name");
        $content = filter_input(INPUT_POST, "content");
        if(!empty($name) && !empty($content))
        {
            $pageId = pp_create_page($_SESSION['user']['userId'], $name, $content);
            if($pageId)
            {
                echo "<p>Your page was created! (id:" . $pageId . ")</p>";
            }
        }
    }
    $pageData = pp_get_user_pages($_SESSION['user']['userId']);
    if($pageData)
    {
        echo '<table>';
        echo '<tr><th rel="col" class="align_left">Page name</th><th rel="col">Page id</th><th rel="col">Delete page</th></tr>';
        foreach($pageData as $data)
        {            
            echo "<tr>\n";
            echo '<td class="align_left"><a href="?page=editpage&param=' . $data['pageId'] . '">' . $data['pageName'] . '</a></td>';
            echo '<td>' . $data['pageId'] . '</td>';
            echo '<td><a href="?page=shredpage&param=' . $data['pageId'] . '">delete</a></td>';
            echo "</tr>\n";
        }
        echo '</table>';
    }
    else 
    {
        echo "<p>You don't seem to have any pages</p>";
    }
    ?>
<hr>
<h2>Create new page</h2>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <p>Page name<br><input type="text" name="name" value="New Page"></p>
    <p><textarea name="content" rows="20" cols="40"></textarea></p>
    <p><input type="submit" name="submit" value="Create page"></p>
</form>
<?php
}
?>
