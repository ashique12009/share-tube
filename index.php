<?php
// ----------------------------------------------------------------
$site_url = 'http://php-video-sharing-app.local';
define("SITE_URL", $site_url);
$page_title = 'Welcome to video sharing app';

require_once "admin/db/class-db-config.php";
require_once "admin/db/class-create-table.php";

$db_connection_object = new ClassDBConfig();
$db_connection_object = $db_connection_object->getConnection();
$create_tables        = new ClassCreateTable($db_connection_object);

$create_tables->createDatabaseTables();
$create_tables->seedAdmin();
$create_tables->seedUser();

// ----------------------------------------------------------------
?>
<?php require_once 'header.php';?>
<?php require_once 'welcome.php';?>
<?php require_once 'footer.php';?>
</html>