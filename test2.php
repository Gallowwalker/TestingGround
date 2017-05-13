<?php
final class Sys {
    
    private static $mainContentTitle = null;
	
    private function __construct() {}
    
    public static function setMainContentTitle($title) {
        self::$mainContentTitle = $title;
    }
    
    public static function getMainContentTitle() {
        return self::$mainContentTitle;
    }
}