<?php
/** Singleton Design Pattern */
final class DatabaseConnection {

    private static $instance = null;
    
    private $databaseConnection = null;
    private $databaseHost = "localhost";
    private $databaseUser = "root";
    private $databasePassword = "";
    private $database = "testing_ground";
    private $databasePort = 3306;
    #private $databaseSocket = null;

    private function __construct() {
        $this->databaseConnection = mysqli_connect($this->databaseHost, $this->databaseUser, $this->databasePassword, $this->database, $this->databasePort);
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        } else {
            return self::$instance;
        }
    }

    public function getConnection() {
        return $this->connection;
    }

}

//$db_connect=DatabaseConnection::getInstance();
//$myConnection = $db_connect->getConnection();
//$result = $myConnection->query($sql_query);
