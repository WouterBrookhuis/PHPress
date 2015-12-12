<h2>Logout</h2>
<?php
if(isset($_SESSION['user']['userId']))
{
    if(session_destroy())
    {
        echo '<p>You have been logged out</p>';
        //Bleh
        header("Refresh: 0");
    }
}
?>
