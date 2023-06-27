<?php 

$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// ----------------------------------------------------------------
$site_url = 'http://php-video-sharing-app.local';
define("SITE_URL", $site_url);
$page_title = 'Search page';

// ----------------------------------------------------------------
require_once 'header.php';?>
<body>
    <?php 
        // Get latest videos
        require_once "admin/db/class-db-config.php";
        require_once "admin/db/class-admin-query.php";

        $db_connection_object = new ClassDBConfig();
        $db_connection_object = $db_connection_object->getConnection();

        $admin = new ClassAdminQuery($db_connection_object);
        $videos = $admin->getSearchedVideos($search_keyword);
    ?>
    <div class="container mt-5rem">
        <div class="cards-grid-container">
            <?php foreach ($videos as $value) : ?>
                <a href="video-detail.php?vid=<?php echo $value['video_link'];?>" class="video-anchor">
                    <div class="card">
                        <?php if ($value['thumbnail']) : ?>
                            <img src="user/uploads/thumbnails/<?php echo $value['thumbnail'];?>" class="card-img-top" alt="">
                        <?php else : ?>
                            <img src="assets/images/nopic.png" class="card-img-top" alt="">
                        <?php endif; ?>
                        <div class="card-body custom-card-body">
                            <div class="photo-and-title-wrapper">
                                <?php if ($value['profile_photo'] != "") : ?>
                                    <img src="<?php echo 'user/uploads/profile/' . $value['profile_photo'];?>" alt="profile-photo" class="loop-profile-image">
                                <?php else : ?>
                                    <img src="assets/images/nopic.png" alt="profile-photo" class="loop-profile-image">
                                <?php endif; ?>
                                <h5 class="card-title"><?php echo $value['title'];?></h5>
                            </div>
                            <p class="card-text card-description"><?php echo $value['description'];?></p>
                        </div>
                    </div>
                </a>
                
            <?php endforeach; ?>
        </div>
    </div>

</body>