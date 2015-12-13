<?php
if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userType'] === 'admin')
{
    if(($userId = filter_input(INPUT_GET, 'param', FILTER_VALIDATE_INT)) != null)   //Single =, since it may be false on filter fail
    {
        echo '<p><a href="?page=userlist">Back to user list</a></p>';
        echo '<h2>User details</h2>';
        $userData = pp_get_user_details($userId);
        if($userData)
        {
            echo '<table>';
            foreach($userData as $index => $data)
            {
                if(!$data)
                    continue;
                echo "<tr>\n";
                echo '<td class="align_left">' . $index . '</td>';
                echo '<td class="align_left">' . $data . '</td>';
                echo "</tr>\n";
            }
            echo '</table>';
            $pageData = pp_get_user_pages($userId);
            if($pageData)
            {
                echo '<h2>User\'s pages</h2>';
                echo '<table>';
                echo '<tr><th rel="col" class="align_left">Page name</th><th rel="col">Page id</th></tr>';
                foreach($pageData as $data)
                {            
                    echo "<tr>\n";
                    echo '<td class="align_left"><a href="?page=editpage&param=' . $data['pageId'] . '">' . $data['pageName'] . '</a></td>';
                    echo '<td>' . $data['pageId'] . '</td>';
                    //echo '<td><a href="?page=shredpage&param=' . $data['pageId'] . '">delete</a></td>';
                    echo "</tr>\n";
                }
                echo '</table>';
            }
        }
        else
        {
            echo '<p>User ID not valid!</p>';
        }

    }
}
?>
