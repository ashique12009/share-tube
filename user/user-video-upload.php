<?php
$page_title = 'User video upload'; 
require_once 'user-header.php';
?>
    <div class="container">
        <h1 class="text-center">Upload video</h1>

        <form class="mb-3" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
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
                $target_dir = "uploads/videos/";
                $target_file = $target_dir . basename($_FILES["vfile"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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

                // Check if $uploadOk is 1
                if ($uploadOk == 2) 
                {
                    echo '<div class="alert alert-danger" role="alert">Sorry, your file is too large.</div>';
                } 
                elseif ($uploadOk == 3)
                {
                    echo '<div class="alert alert-danger" role="alert">Sorry, only mp4 file is allowed.</div>';
                }
                else 
                {
                    if (move_uploaded_file($_FILES["vfile"]["tmp_name"], $target_file)) 
                    {
                        echo '<div class="alert alert-success" role="alert">The file ' . htmlspecialchars( basename( $_FILES["vfile"]["name"])). " has been uploaded.</div>";
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