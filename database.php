<?php
final class Database {
    
    private static $databaseConnection = null;
    private static $databaseHost = "localhost";
    private static $databaseUser = "root";
    private static $databasePassword = "";
    private static $database = "testing_ground";
    private static $databasePort = 3306; //optional
    private static $databaseSocket = "MySQL"; //optional
    
    private function __construct() {}
    
    private function __clone() {} // Magic method clone is empty to prevent duplication of connection
    
    public static function setDatabaseConnection() {
        self::$databaseConnection = mysqli_connect(self::$databaseHost, self::$databaseUser, self::$databasePassword, self::$database, self::$databasePort, self::$databaseSocket);
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
            exit;
        } else {
            mysqli_query(self::$databaseConnection, "SET NAMES utf8");
        }
    }
    
    public static function getDatabaseConnection() {
        return self::$databaseConnection;
    }
    
    public static function closeDatabaseConnection() {
        mysqli_close(self::$databaseConnection);
    }
    
    public static function runQuery($dbConnection, $sqlQuery) { #add message when fail
        //mysqli_query($dbConnection, "SET NAMES utf8");
        return mysqli_query($dbConnection, $sqlQuery);
    }
    
    public static function freeResultSet($resultSet) {
        mysqli_free_result($resultSet);
    }
    
    public static function fetchAssoc($outputData) {
        $resultData = mysqli_fetch_assoc($outputData);
        return $resultData;
    }
    
    public static function getRowNumber($outputData) {
        $result = mysqli_num_rows($outputData);
        return $result;
    }
    
    public static function setDataSeek($result, $offset = 0) {
        $resultData = mysqli_data_seek($result, $offset);
        return $resultData;
    }
    
}