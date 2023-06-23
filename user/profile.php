<?php
// ----------------------------------------------------------------
$site_url   = 'http://php-video-sharing-app.local';
$page_title = 'Profile page';
// ----------------------------------------------------------------
?>
<?php require_once 'user-header.php';?>
<body>
    <?php 
        // Get latest videos
        require_once "../admin/db/class-db-config.php";
        require_once "../admin/db/class-user-query.php";

        $db_connection_object = new ClassDBConfig();
        $db_connection_object = $db_connection_object->getConnection();

        $user      = new ClassUserQuery($db_connection_object);
        $user_data = $user->getUserInfoById($user_info['id']);
    ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Profile</div>
            <div class="card-body">
                <h5 class="card-title">Name: <?php echo $user_data['name'];?></h5>
                <p class="card-text">Email: <?php echo $user_data['email'];?></p>
                <p class="card-text">Role: <?php echo $user_data['role_id'] == 2 ? 'User' : 'Admin';?></p>

                <?php if (is_null($user_data['profile_photo']) || $user_data['profile_photo'] == "") :?>
                    <img src="../assets/images/nopic.png" alt="profile-photo">
                <?php else: ?>
                    <img src="<?php echo "uploads/profile/" . $user_data['profile_photo'];?>" alt="profile-photo">
                <?php endif;?>

                <form class="mb-3" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name: </label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user_data['name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email: </label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $user_data['email'];?>">
                    </div>
                    <div class="form-group">
                        <label for="pfile">Profile photo: </label>
                        <input type="file" class="form-control-file" id="pfile" name="pfile">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>

                <?php
                    if (isset($_POST['submit']))
                    {
                        $name       = htmlspecialchars(strip_tags($_POST['name']));
                        $email      = htmlspecialchars(strip_tags($_POST['email']));
                        $inputError = 1;

                        if (isset($_FILES['pfile']) && $_FILES['pfile']['name'] != "")
                        {
                            $target_dir    = "uploads/profile/";
                            $file_name     = round(microtime(true)) . '-' . basename($_FILES["pfile"]["name"]);
                            $target_file   = $target_dir . $file_name;
                            $uploadOk      = 1;                            
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            // Check file size is not bigger than 2MB
                            if ($_FILES["pfile"]["size"] > 2097152) 
                            {
                                $uploadOk = 2;
                            }

                            // Allow certain file formats
                            if ($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != "jpeg") 
                            {
                                $uploadOk = 3;
                            }
                        }
                        else 
                        {
                            $uploadOk = 0;
                        }

                        if ($name == "")
                        {
                            $inputError = 2;
                        }
                        
                        if ($email == "")
                        {
                            $inputError = 2;
                        }

                        // Check if $uploadOk is 1
                        if ($uploadOk == 2) 
                        {
                            echo '<div class="alert alert-danger" role="alert">Sorry, your file is too large.</div>';
                        } 
                        elseif ($uploadOk == 3)
                        {
                            echo '<div class="alert alert-danger" role="alert">Sorry, only mp4 file is allowed.</div>';
                        }
                        elseif ($uploadOk == 4)
                        {
                            echo '<div class="alert alert-danger" role="alert">Sorry, upload failed.</div>';
                        }
                        elseif ($inputError == 2)
                        {
                            echo '<div class="alert alert-danger" role="alert">Sorry, input missing.</div>';
                        }
                        else 
                        {
                            $user_id = $user_info['id'];

                            if ($uploadOk == 1)
                            {
                                if (move_uploaded_file($_FILES["pfile"]["tmp_name"], $target_file)) 
                                {
                                    // Update user info to database
                                    $data = [
                                        'name'    => $name,
                                        'email'   => $email,
                                        'pfile'   => $file_name,
                                        'user_id' => $user_id,
                                    ];
                                    if ($user->updateUser($data, $user_id))
                                    {
                                        echo '<div class="alert alert-success" role="alert">Update has been done.</div>';
                                    }
                                    else 
                                    {
                                        echo '<div class="alert alert-danger" role="alert">Sorry, update failed.</div>';
                                    }                        
                                } 
                                else 
                                {
                                    echo '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your file.</div>';
                                }
                            }
                            else
                            {
                                // Update user info to database
                                $data = [
                                    'name'    => $name,
                                    'email'   => $email,
                                    'pfile'   => '',
                                    'user_id' => $user_id,
                                ];
                                if ($user->updateUser($data, $user_id))
                                {
                                    // echo '<div class="alert alert-success" role="alert">Update has been done.</div>';
                                    header('Location: ' . $_SERVER['REQUEST_URI']);
                                }
                                else 
                                {
                                    echo '<div class="alert alert-danger" role="alert">Sorry, update failed.</div>';
                                }
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
<?php require_once 'user-footer.php';?>