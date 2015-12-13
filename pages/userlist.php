<?php
if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userType'] === 'admin')
{
    echo '<p><a href="?page=admin">Back to admin panel</a></p>';
    echo '<h2>Site users</h2>';
    $userData = pp_get_users();
?>
<table>
    <tr><th rel="col" class="align_left">Username</th><th rel="col">User id</th><th rel="col">User type</th></tr>
    <?php
    foreach($userData as $data)
    {
        echo "<tr>\n";
        echo '<td class="align_left"><a href="?page=viewuser&param=' . $data['userId'] . '">' . $data['userName'] . '</a></td>';
        echo '<td>' . $data['userId'] . '</td>';
        echo '<td>' . $data['userType'] . '</td>';
        echo "</tr>\n";
    }
    ?>
</table>
<?php
}
?>
