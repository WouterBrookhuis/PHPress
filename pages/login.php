<h2>Login</h2>
<?php
    if(isset($_POST['submit']))
    {
        $userName = filter_input(INPUT_POST, 'userName');
        $userPass = filter_input(INPUT_POST, 'userPass');
        if(!empty($userName) && !empty($userPass))
        {
            $userData = pp_login($userName, $userPass);
            if($userData)
            {
                $_SESSION['user'] = $userData;
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
<p><b>Don't have an account?</b></p>
<p><a href="?page=register">Register</a></p>
