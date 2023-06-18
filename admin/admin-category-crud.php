<?php
$page_title = 'Admin category crud'; 
require_once 'admin-header.php';
?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Category</div>
            <div class="card-body">
                <form class="mb-3" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="cname">Category name</label>
                        <input type="text" class="form-control" id="cname" name="cname">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Category list</div>
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

                    $operation  = isset($_GET['operation']) ? $_GET['operation'] : '';
                    $cat_id     = isset($_GET['id']) ? $_GET['id'] : '';

                    if ($operation == 'delete' && is_numeric($cat_id))
                    {
                        $admin->deleteCategory($cat_id);
                    }

                    $get_category_list = $admin->getCategories();
                ?>

                <ul class="list-group">
                    <?php foreach ($get_category_list as $value) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $value['name'];?>
                            <span class="badge badge-light badge-pill">
                                <a href=<?php echo "?operation=edit&id=" . $value['id'];?>>EDIT</a> | 
                                <a href=<?php echo "?operation=delete&id=" . $value['id'];?>>DELETE</a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>