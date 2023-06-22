<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <title>User registration</title>
</head>
<body>
    <div class="form-container">
        <div class="container">
            <?php $site_url = 'http://php-video-sharing-app.local'; ?>
            <form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <h2 class="text-center"><a href="<?php echo $site_url; ?>">Home</a>|<a href="<?php echo $site_url . '/user/login.php'; ?>">Sign in</a></h2>
                <h1 class="h3 mb-3 font-weight-normal text-center">Registration</h1>
                <label for="inputName" class="sr-only">Name</label>
                <input type="text" name="name" id="inputName" class="form-control" placeholder="Name" required="" autofocus="">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="">
                <button class="btn btn-lg btn-primary btn-block mt-3" type="submit" name="submit">Register</button>
            </form>
        </div>        

        <div class="container little-container">
            <?php 
                require_once "../admin/db/class-db-config.php";
                require_once "../admin/db/class-user-query.php";

                $db_connection_object = new ClassDBConfig();
                $db_connection_object = $db_connection_object->getConnection();

                $user = new ClassUserQuery($db_connection_object);
            ?>
            <?php 
                if (isset($_POST['submit'])) {
                    $name     = $_POST['name'];
                    $email    = $_POST['email'];
                    $password = $_POST['password'];
                    if (empty($email) || empty($password) || empty($name)) 
                    {
                        echo '<div class="alert alert-danger" role="alert">Please fill all the fields correctly</div>';
                    } 
                    else 
                    {
                        if ($user->insertUser($name, $email, $password))
                        {
                            header('Location: login.php?registartion=1');
                        }
                        else 
                        {
                            echo '<div class="alert alert-danger" role="alert">Please fill all the fields correctly</div>';
                        }
                    }
                }
            ?>
        </div>
        
    </div>
</body>
</html>