<?php
if(isset($_SESSION['user']['userId']))
{
    echo '<h2>Select active menu</h2>';
    if(isset($_POST['submit']))
    {
        $menuId = filter_input(INPUT_POST, 'menu', FILTER_VALIDATE_INT);
        if($menuId)
        {
            //UPDATE MENU
            pp_set_active_menu($_SESSION['user']['userId'], $menuId);
        }
    }
    $menuData = pp_get_user_menus($_SESSION['user']['userId']);
    $activeMenu = pp_get_user_details($_SESSION['user']['userId'])['activeMenu'];
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <table>
        <tr><th rel="col" class="align_left">Menu name</th><th rel="col">Menu id</th><th rel="col">Is active</th></tr>
        <?php
        foreach($menuData as $data)
        {            
            echo "<tr>\n";
            echo '<td class="align_left"><a href="?page=editmenu&param=' . $data['menuId'] . '">' . $data['menuName'] . '</a></td>';
            echo '<td>' . $data['menuId'] . '</td>';
            if($data['menuId'] === $activeMenu)
                echo '<td><input type="radio" name="menu" checked value="' . $data['menuId'] . '"></td>';
            else
                echo '<td><input type="radio" name="menu" value="' . $data['menuId'] . '"></td>';
            echo "</tr>\n";
        }
        ?>
    </table>
    <p><input type="submit" name="submit" value="Update menu"></p>
</form>
<hr>
<h2>Create new menu</h2>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?page=createmenu"; ?>" method="post">
    <p>Menu name<br><input type="text" name="name" value="New Menu"></p>
    <p><input type="submit" name="submit" value="Create page"></p>
</form>
<?php
}
?>
