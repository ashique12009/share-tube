<body>
    <?php 
        // Get latest videos
        require_once "admin/db/class-db-config.php";
        require_once "admin/db/class-admin-query.php";

        $db_connection_object = new ClassDBConfig();
        $db_connection_object = $db_connection_object->getConnection();

        $admin = new ClassAdminQuery($db_connection_object);
        $videos = $admin->getVideos();
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