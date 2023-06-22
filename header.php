<?php 
    session_start();

    if (isset($_SESSION['user_info'])) 
    {
        $user_info = $_SESSION['user_info'];
        $site_url  = 'http://php-video-sharing-app.local';
        $role_id   = $user_info['role_id'];
    }
    else 
    {
        $role_id = 0;
        $user_info = '';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap4.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <title><?php echo $page_title != '' ? $page_title : '';?></title>
</head>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-expand-lg navbar navbar-dark bg-dark custom-navbar">
        <a class="navbar-brand" href="<?php echo $site_url;?>">ShareTube</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <?php if ($role_id == 1) :?>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $site_url . '/admin/admin-home.php';?>">Home <span class="sr-only">(current)</span></a></li>
                <?php elseif ($role_id == 2) :?>
                    <li class="nav-item active"><a class="nav-link" href="<?php echo $site_url . '/user/user-home.php';?>">Home <span class="sr-only">(current)</span></a></li>
                <?php endif;?>

                <?php 
                    // Get recent 3 categories
                    require_once "admin/db/class-db-config.php";
                    require_once "admin/db/class-admin-query.php";

                    $db_connection_object = new ClassDBConfig();
                    $db_connection_object = $db_connection_object->getConnection();

                    $admin = new ClassAdminQuery($db_connection_object);
                    $categories = $admin->getRecentCategories();
                ?>
                <?php foreach($categories as $cat) : ?>
                    <li class="nav-item"><a class="nav-link" href="#"><?php echo $cat['name'];?></a></li>
                <?php endforeach; ?>
            </ul>
            <form class="form-inline my-2 my-lg-0 mx-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            <ul class="navbar-nav">
                <?php if ($user_info == "") :?>
                    <li class="nav-item"><a href="user/login.php" class="nav-link">Sign in</a></li>
                <?php else :?>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Sign out</a></li>
                <?php endif;?>
            </ul>
        </div>
    </nav>
</div>

