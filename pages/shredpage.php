<?php
if(isset($_SESSION['user']) && isset($_GET['param']))
{
    $pageId = filter_input(INPUT_GET, 'param', FILTER_VALIDATE_INT);
    if($pageId && pp_can_edit_page($pageId, $_SESSION['user']['userId']))
    {
        if(pp_delete_page($pageId))
            echo '<h2>Page was removed</h2>';
        else
            echo '<h2>Page could not be removed</h2>';
    }
    else
    {
        echo '<h2>You are not authorized to remove this page!</h2>';
        echo '<p>Your behaviour has been logged!</p>';
        pp_write_log('NOTICE: User ' . $_SESSION['user']['userName'] . ' tried to remove page with ID ' . $pageId . ' but was not authorized!');
    }
}
?>
