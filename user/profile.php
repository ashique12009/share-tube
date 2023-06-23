<?php
// ----------------------------------------------------------------
$site_url   = 'http://php-video-sharing-app.local';
$page_title = 'Profile page';
// ----------------------------------------------------------------
?>
<?php require_once 'user-header.php';?>
<body>
    <?php 
        // Get latest videos
        require_once "../admin/db/class-db-config.php";
        require_once "../admin/db/class-user-query.php";

        $db_connection_object = new ClassDBConfig();
        $db_connection_object = $db_connection_object->getConnection();

        $user = new ClassUserQuery($db_connection_object);
    ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Profile</div>
            <div class="card-body">
                <h5 class="card-title">Name: <?php echo $user_info['name'];?></h5>
                <p class="card-text">Email: <?php echo $user_info['email'];?></p>
                <p class="card-text">Role: <?php echo $user_info['role_id'] == 2 ? 'User' : 'Admin';?></p>
                
            </div>
        </div>
    </div>
</body>
<?php require_once 'user-footer.php';?>