<h2>Control panel</h2>
<p>Here you can change your pages, account settings and more</p>
<div id="admin_panel_buttons">
    <a href="?page=pagelist">
        <div class="admin_panel_button">
            <h3>Pages</h3>
            <img class="admin_panel_button_img" src="phpress/images/icon-create-page.png" alt="icon">
        </div>
    </a>
    <a href="?page=menulist">
        <div class="admin_panel_button">
            <h3>Menus</h3>
            <img class="admin_panel_button_img" src="phpress/images/icon-edit-page.png" alt="icon">
        </div>
    </a>
    <?php
    if($_SESSION['user']['userType'] === 'admin')
    {
        ?>
    <a href="?page=userlist">
        <div class="admin_panel_button">
            <h3>Users</h3>
            <img class="admin_panel_button_img" src="phpress/images/icon-edit-page.png" alt="icon">
        </div>
    </a>
    <?php
    }
    ?>
    <a href="?page=logout">
        <div class="admin_panel_button">
            <h3>Logout</h3>
            <img class="admin_panel_button_img" src="phpress/images/icon-exit.png" alt="icon">
        </div>
    </a>
</div>