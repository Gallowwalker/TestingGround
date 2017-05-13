<?php
final class Database {
    
    private static $databaseInstance = null;
    private $databaseConnection = null;
    private $databaseHost = "localhost";
    private $databaseUser = "root";
    private $databasePassword = "";
    private $database = "testing_ground";
    private $databasePort = 3306; //optional
    private $databaseSocket = "MySQL"; //optional
    
    private function __construct() {
        $this->databaseConnection = new mysqli($this->databaseHost, $this->databaseUser, $this->databasePassword, $this->database, $this->databasePort, $this->databaseSocket);
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
            exit;
        } 
    }
    
    private function __clone() { } // Magic method clone is empty to prevent duplication of connection
    
    public static function getDatabaseInstance() {
        if (!self::$databaseInstance) {
            self::$databaseInstance = new self();
        }
        return self::$databaseInstance;
    }
    
    public function getDatabaseConnection() {
        return $this->databaseConnection;
    }
    
    public function closeDatabaseConnection() {
        $this->databaseConnection->close();
    }
    
    public function runQuery($sqlQuery) {
        $this->databaseConnection->query("SET NAMES utf8");
        return $this->databaseConnection->query($sqlQuery);
    }
    
}

 $db = Database::getInstance();
    $mysqli = $db->getConnection(); 
    $sql_query = "SELECT foo FROM .....";
    $result = $mysqli->query($sql_query);