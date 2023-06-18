<?php 
    session_start();

    if (isset($_SESSION['user_info'])) 
    {
        $user_info = $_SESSION['user_info'];
        $site_url = 'http://php-video-sharing-app.local';
        define("SITE_URL", $site_url);

        $haystack = $_SERVER['REQUEST_URI'];

        $active_page = '';

        if (strpos($haystack, 'admin-video-upload') !== false) 
        {
            $active_page = 'upload';
        }
        elseif (strpos($haystack, 'admin-category-crud') !== false)
        {
            $active_page = 'category';
        }
        else 
        {
            $active_page = 'home';
        }
    }
    else 
    {
        header('Location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <title><?php echo $page_title != '' ? $page_title : '';?></title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark custom-navbar">
        <a class="navbar-brand" href="">ShareTube</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo ($active_page == 'home') ? 'active' : '';?>"><a class="nav-link" href="<?php echo SITE_URL . '/admin/admin-home.php';?>">Home</a></li>
                <li class="nav-item <?php echo ($active_page == 'category') ? 'active' : '';?>"><a class="nav-link" href="<?php echo SITE_URL . '/admin/admin-category-crud.php';?>">Category</a></li>
                <li class="nav-item <?php echo ($active_page == 'upload') ? 'active' : '';?>"><a class="nav-link" href="<?php echo SITE_URL . '/admin/admin-video-upload.php';?>">Upload</a></li>
            </ul>
            <form class="form-inline my-2 my-lg-0 mx-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="logout.php" class="nav-link">Sign out</a></li>
            </ul>
        </div>
    </nav>