<?php
$page_title = 'Admin approve video'; 
require_once 'admin-header.php';
?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Video list</div>
            <div class="card-body">
                <?php 
                    require_once "db/class-db-config.php";
                    require_once "db/class-admin-query.php";

                    $db_connection_object = new ClassDBConfig();
                    $db_connection_object = $db_connection_object->getConnection();

                    $admin = new ClassAdminQuery($db_connection_object);

                    if (isset($_POST['submit']))
                    {
                        $cat_name = $_POST['cname'];
                        if ($admin->insertCategory($cat_name))
                        {
                            echo '<div class="alert alert-success" role="alert">Category <b>'.$cat_name.'</b> has been added successfully.</div>';
                        }
                        else 
                        {
                            echo '<div class="alert alert-danger" role="alert">Category <b>'.$cat_name.'</b> has not been added.</div>';
                        }
                    }

                    $operation = isset($_GET['operation']) ? $_GET['operation'] : '';
                    $video_id  = isset($_GET['id']) ? $_GET['id'] : '';

                    if (is_numeric($operation) && is_numeric($video_id))
                    {
                        $admin->updateVideoStatus($operation, $video_id);
                    }

                    $videos = $admin->getVideos();
                ?>

                <ul class="list-group">
                    <?php foreach ($videos as $value) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $value['title'];?>
                            <span class="badge badge-light badge-pill">
                                <?php if ($value['status'] == 1) : ?> 
                                    <a class="red-text" href=<?php echo "?operation=0&id=" . $value['id'];?>>REJECT</a>
                                <?php else : ?>
                                    <a class="green-text" href=<?php echo "?operation=1&id=" . $value['id'];?>>APPROVE</a>
                                <?php endif; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>