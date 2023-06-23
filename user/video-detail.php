<?php
// ----------------------------------------------------------------
$site_url   = 'http://php-video-sharing-app.local';
$page_title = 'Video page';

// ----------------------------------------------------------------
?>
<?php require_once 'user-header.php';?>
<body>
    <div class="container mt-5">
        <?php $video_link = $_GET['vid'];?>
        <video width="100%" height="100%" controls>
            <source src="uploads/videos/<?php echo $video_link;?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

</body>
<?php require_once 'user-footer.php';?>