<?php
//session_start();
include './system.php';
include './database.php';
System::sessionStart();
$username = $_SESSION['userInfo']['username'];
$sqlQuery = 'UPDATE users SET status = "offline" WHERE username = "'.$username.'";';
Database::setDatabaseConnection();
Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
Database::closeDatabaseConnection();
//session_destroy();
System::sessionDestroy();
header('Location: index.php');
exit;