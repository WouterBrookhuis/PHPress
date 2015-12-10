<?php
include_once 'phpress/phpress.php';
session_start();
?>
<h2>Login</h2>
<?php
    if(isset($_POST['submit']))
    {
        $userName = filter_input(INPUT_POST, 'userName');
        $userPass = filter_input(INPUT_POST, 'userPass');
        if(!empty($userName) && !empty($userPass))
        {
            $userToken = pp_login($userName, $userPass);
            if($userToken)
            {
                $_SESSION['user'] = $userToken;
                echo "<p>You were logged in</p>";
            }
        }
    }
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <p>Username:<input type="text" name="userName"></p>
    <p>Password:<input type="password" name="userPass"></p>
    <p><input type="submit" name="submit" value="Login"></p>
</form>
