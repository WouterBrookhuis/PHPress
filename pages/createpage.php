<?php
if(isset($_SESSION['user']['userId']))
{
    echo '<h2>Create a page</h2>';
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
}
?>
