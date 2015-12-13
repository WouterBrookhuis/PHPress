<h2>Register</h2>
<?php
    if(isset($_POST['submit']))
    {
        $userName = filter_input(INPUT_POST, 'userName');
        $userPass = filter_input(INPUT_POST, 'userPass');
        $userMail = filter_input(INPUT_POST, 'userMail');
        if(!empty($userName) && !empty($userMail) && !empty($userPass))
        {
            if(pp_register($userName, $userPass, $userMail))
            {
                echo "<p>You were registered!</p>";
                //Login right now
                $userData = pp_login($userName, $userPass);
                if($userData)
                {
                    $_SESSION['user'] = $userData;
                    echo "<p>You were logged in</p>";
                }
            }
        }
    }
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="post">
    <p>Username:<input type="text" name="userName"></p>
    <p>Password:<input type="password" name="userPass"></p>
    <p>Email:<input type="email" name="userMail"></p>
    <p><input type="submit" name="submit" value="Register"></p>
</form>