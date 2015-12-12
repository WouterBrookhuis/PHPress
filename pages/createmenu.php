<?php
if(isset($_SESSION['user']['userId']))
{
    echo '<h2>Create a menu</h2>';
    if(isset($_POST['submit']))
    {
        $name = filter_input(INPUT_POST, "name");
        if(!empty($name))
        {
            $menuId = pp_create_menu($_SESSION['user']['userId'], $name);
            if($menuId)
            {
                echo "<p>Your menu was created! (id:" . $menuId . ")</p>";
                echo '<p><a href="?page=menulist">Return to menu</a></p>';
            }
        }
    }
}
?>
