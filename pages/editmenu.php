<?php
if(isset($_SESSION['user']['userId']) && isset($_GET['param']))
{
    echo '<p><a href="?page=menulist">Back to menu list</a></p>';
    echo '<h2>Edit a menu</h2>';
    $menuId = filter_input(INPUT_GET, 'param', FILTER_VALIDATE_INT);
    if($menuId && pp_get_menu_author($menuId) === $_SESSION['user']['userId'])
    {
        $pageData = pp_get_user_pages($_SESSION['user']['userId']);
        if(isset($_POST['submit']))
        {
            //UPDATE MENU
            $checkboxes = array();
            foreach($pageData as $p)
            {
                $value = filter_input(INPUT_POST, 'checked' . $p['pageId']);
                if($value === "on")
                    array_push($checkboxes, $p['pageId']);
            }
            $menuName = filter_input(INPUT_POST, 'name');
            pp_update_menu($menuId, $menuName, $checkboxes);
        }
        $menuData = pp_get_menu($menuId);
        $menuPageIds = array();
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <h3>Menu name<br><input type="text" name="name" value="<?php echo $menuData[0]['menuName']; ?>"></h3>
    <h3>Pages currently in menu</h3>
        <?php
        if(count($menuData) > 0)
        {
            echo '<table>';
            echo '<tr><th rel="col">Page title</th><th rel="col">Page id</th><th rel="col">Is in menu</th></tr>';
            foreach($menuData as $index => $data)
            {
                if($index === 0)
                    continue;   //Skip the menu entity part

                echo "<tr>\n";
                echo '<td>' . pp_get_page($data['pageId'])['pageName'] . '</td>';
                echo '<td>' . $data['pageId'] . '</td>';
                echo '<td><input type="checkbox" name="checked' . $data['pageId'] . '" checked="checked"></td>';
                echo "</tr>\n";

                array_push($menuPageIds, $data['pageId']);
            }
            echo '</table>';
        }
        else
        {
            echo "<p>You have no pages in this menu</p>";
        }
        echo '<h3>Pages not in menu</h3>';
        if($pageData)
        {
            echo '<table>';
            echo '<tr><th rel="col">Page title</th><th rel="col">Page id</th><th rel="col">Is in menu</th></tr>';
            foreach($pageData as $data)
            {
                if(in_array($data['pageId'], $menuPageIds))
                    continue;

                echo "<tr>\n";
                echo '<td>' . $data['pageName'] . '</td>';
                echo '<td>' . $data['pageId'] . '</td>';
                echo '<td><input type="checkbox" name="checked' . $data['pageId'] . '"></td>';
                echo "</tr>\n";
            }
            echo '</table>';
        }
        else
        {
            echo "<p>You have no pages that are not in this menu</p>";
        }
    ?>
    <p><input type="submit" name="submit" value="Update menu"></p>
</form>
<?php
    }
}
?>
