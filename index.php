<?php
// ----------------------------------------------------------------
$page_title = 'Welcome to video sharing app';

require_once "admin/db/class-db-config.php";
require_once "admin/db/class-create-table.php";

$db_connection_object = new ClassDBConfig();
$db_connection_object = $db_connection_object->getConnection();
$create_tables        = new ClassCreateTable($db_connection_object);

$create_tables->createDatabaseTables();

// ----------------------------------------------------------------
?>
<?php require_once 'header.php';?>
<?php require_once 'welcome.php';?>
<?php require_once 'footer.php';?>
</html>