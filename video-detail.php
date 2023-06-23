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
        <?php $video_link = $_GET['vid'];?>
        <video width="100%" height="100%" controls>
            <source src="user/uploads/videos/<?php echo $video_link;?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

</body>
<?php require_once 'footer.php';?>