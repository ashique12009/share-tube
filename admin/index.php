<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <title>Admin sign in</title>
</head>
<body>
    <div class="form-container">
        <div class="container">
            <?php $site_url = 'http://php-video-sharing-app.local'; ?>
            <form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <h1 class="text-center"><a href="<?php echo $site_url; ?>">Home</a></h1>
                <h1 class="h3 mb-3 font-weight-normal text-center">Please sign in</h1>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="">
                <div class="checkbox mt-3 mb-3 text-center">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>
            </form>
        </div>        

        <div class="container little-container">
            <?php 
                require_once "db/class-db-config.php";
                require_once "db/class-admin-query.php";

                $db_connection_object = new ClassDBConfig();
                $db_connection_object = $db_connection_object->getConnection();

                $user = new ClassAdminQuery($db_connection_object);
            ?>
            <?php 
                if (isset($_POST['submit'])) {
                    $email    = $_POST['email'];
                    $password = $_POST['password'];
                    if (empty($email) || empty($password)) {
                        echo '<div class="alert alert-danger" role="alert">Please fill all the fields correctly</div>';
                    } 
                    elseif (!$user->verifyAdminCredentials($email, $password)) {
                        echo '<div class="alert alert-danger" role="alert">Invalid email or password</div>';
                    }
                    else {
                        $get_user_info = $user->getAdminInfo($email);
                        $_SESSION['user_info'] = $get_user_info;
                        header('Location: admin-home.php');
                    }
                }
            ?>
        </div>
        
    </div>
</body>
</html>