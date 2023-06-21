<?php
$page_title = 'User video upload'; 
require_once 'user-header.php';

require_once "../admin/db/class-db-config.php";
require_once "../admin/db/class-user-query.php";

$db_connection_object = new ClassDBConfig();
$db_connection_object = $db_connection_object->getConnection();

$user = new ClassUserQuery($db_connection_object);
$cats = $user->getCategories();

$user_info = $_SESSION['user_info'];
?>
    <div class="container">
        <h1 class="text-center">Upload video</h1>

        <form class="mb-3" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc" class="form-control" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label for="cat">Category</label>
                <select name="cat_id" id="cat_id" class="form-control">
                    <?php foreach ($cats as $value) :?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="vtfile">Thumbnail</label>
                <input type="file" class="form-control-file" id="vtfile" name="vtfile">
            </div>
            <div class="form-group">
                <label for="vfile">Video file</label>
                <input type="file" class="form-control-file" id="vfile" name="vfile">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php
            if (isset($_POST['submit']))
            {
                // ------------------------------THUMBNAIL FILE----------------------------------
                $thumb_file_name = round(microtime(true)) . '-' . basename($_FILES["vtfile"]["name"]);
                $uploadOk        = $user->uploadThumbnail($_FILES["vtfile"]);
                // ------------------------------------------------------------------------------

                $target_dir    = "uploads/videos/";
                $file_name     = round(microtime(true)) . '-' . basename($_FILES["vfile"]["name"]);
                $target_file   = $target_dir . $file_name;
                $uploadOk      = 1;
                $inputError    = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $title  = $_POST['title'];
                $desc   = $_POST['desc'];
                $cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : '';

                // Check file size is not bigger than 2MB
                if ($_FILES["vfile"]["size"] > 2097152) 
                {
                    $uploadOk = 2;
                }

                // Allow certain file formats
                if ($imageFileType != "mp4") 
                {
                    $uploadOk = 3;
                }

                if ($title == "")
                {
                    $inputError = 2;
                }
                
                if ($cat_id == "")
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

                    if (move_uploaded_file($_FILES["vfile"]["tmp_name"], $target_file)) 
                    {
                        // Insert video info to database
                        $data = [
                            'title'   => $title,
                            'desc'    => $desc,
                            'cat_id'  => $cat_id,
                            'thumb'   => $thumb_file_name,
                            'vfile'   => $file_name,
                            'user_id' => $user_id,
                        ];
                        if ($user->insertVideo($data))
                        {
                            echo '<div class="alert alert-success" role="alert">The file ' . htmlspecialchars(basename($_FILES["vfile"]["name"])) . " has been uploaded.</div>";
                        }
                        else 
                        {
                            echo '<div class="alert alert-danger" role="alert">Sorry, insertion failed.</div>';
                        }                        
                    } 
                    else 
                    {
                        echo '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your file.</div>';
                    }
                }
            }
        ?>
    </div>
</body>
</html>