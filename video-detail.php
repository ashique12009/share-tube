<?php
// ----------------------------------------------------------------
$site_url = 'http://php-video-sharing-app.local';
define("SITE_URL", $site_url);
$page_title = 'Video page';

// ----------------------------------------------------------------
?>
<?php require_once 'header.php';?>
<body>
    <div class="container mt-5rem">
        <?php 
            $video_link = $_GET['vid'];
            require_once "admin/db/class-db-config.php";
            require_once "admin/db/class-admin-query.php";

            $db_connection_object = new ClassDBConfig();
            $db_connection_object = $db_connection_object->getConnection();

            $admin = new ClassAdminQuery($db_connection_object);
            $vdata = $admin->getVideoByVlink($video_link);
        ?>
        
        <video width="100%" height="100%" controls>
            <source src="user/uploads/videos/<?php echo $video_link;?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <h3><?php echo $vdata['title'];?></h3>
        <p><?php echo $vdata['description'];?></p>
        <p><?php echo "Category: " . $vdata['name'];?></p>
    </div>

</body>
<?php require_once 'footer.php';?>