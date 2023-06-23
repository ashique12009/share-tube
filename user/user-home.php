<?php
$page_title = 'User home'; 
require_once 'user-header.php';

$user_info = $_SESSION['user_info'];

// Get latest videos
require_once "../admin/db/class-db-config.php";
require_once "../admin/db/class-user-query.php";

$db_connection_object = new ClassDBConfig();
$db_connection_object = $db_connection_object->getConnection();

$user = new ClassUserQuery($db_connection_object);
$videos = $user->getVideos($user_info['id']);
?>
    <div class="container">
        <h1 class="text-center">Welcome to your home</h1>
    </div>

    <div class="container">
        <div class="cards-grid-container">
        <?php foreach ($videos as $value) : ?>
            <a href="video-detail.php?vid=<?php echo $value['video_link'];?>" class="video-anchor">
                <div class="card">
                    <?php if ($value['thumbnail']) : ?>
                        <img src="uploads/thumbnails/<?php echo $value['thumbnail'];?>" class="card-img-top" alt="">
                    <?php else : ?>
                        <img src="../assets/images/nopic.png" class="card-img-top" alt="">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $value['title'];?></h5>
                        <p class="card-text card-description"><?php echo $value['description'];?></p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>        
    </div>

</body>
</html>