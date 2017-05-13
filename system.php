<?php
final class System {
    
    private static $contentTitle = null;
	
    private function __construct() {}
    
    public static function setContentTitle($title) {
        self::$contentTitle = $title;
    }
    
    public static function getContentTitle() {
        return self::$contentTitle;
    }
    
    public static function sessionStart() {
        session_start();
        ob_start();
        ob_clean();
    }
    
    public static function sessionDestroy() {
        session_destroy();
    }

    public static function getPageHeader(){
?>
<!DOCTYPE html>
<html lang="bg">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="img/other/favicon.png">
        <title>Testing Ground</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/flipclock.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/flipclock.js"></script>
        <!--<script src="js/jquery-3.0.0.js"></script>-->
    </head>
    <body>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" id="home" href="index.php">Testing Ground</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li id="home"><a href="index.php">Начало</a></li>
                        <!--<li id="register"><a href="register.php">Регистрация</a></li>
                        <li id="login"><a href="login.php">Вход</a></li>-->
                        <li id="library"><a href="library.php">Библиотека</a></li>
                        <li id="exams"><a href="exams.php">Тестове</a></li>
                        <li id="team"><a href="team.php">Екип</a></li>
                        <li id="contact-us"><a href="contact-us.php">Връзка с нас</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Други <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php System::getAccountPanel(); ?>
<!--                        <li><a href="register.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Регистрация</a></li>
                        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Вход</a></li>-->
                    </ul>
                    <!--ul class="nav navbar-nav navbar-right"></ul>-->
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div class="container" role="main">
            <div class="page-header">
                <h2><?php echo self::getContentTitle(); ?></h2>
            </div>
        <?php
        }
	
	public static function getPageFooter() {
	?>   
            </div><!-- /container -->
            <br />
            <footer class="footer">
                <div class="container">
                    <p>Copyright &copy; 2016 Всички права запазени. Дизайн и поддръжка - Иван Пунтев. Версия 2.0</p>
                </div>
            </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
        <?php
	}
	
	public static function getAccountPanel() {
            if(isset($_SESSION['isLogged']) === true){
                if($_SESSION['userInfo']['type'] === "Администратор"){

 echo '<li><a href="admin-panel.php">Админ</a></li>';
                }
                if($_SESSION['userInfo']['type'] === "Модератор"){


                }
                if($_SESSION['userInfo']['type'] === "Потребител"){

                }

            Database::setDatabaseConnection();
            
            $imageSource = 'img/profile-pictures/default/p_0.png';
            $sqlQuery = 'SELECT profile_picture FROM user_info WHERE user_id = '.$_SESSION['userInfo']['id'].';';
            $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
            $userInfo = Database::fetchAssoc($outputData);
            
            if ($userInfo['profile_picture']) {
                $imageSource = $userInfo['profile_picture'];
            }
            
            Database::freeResultSet($outputData);
            Database::closeDatabaseConnection();
            #strtok($_SESSION['userInfo']['name'], ' ')
                     echo '


<!--<li><a href="#"><img class="valign" src="'.$imageSource.'" width="28" height="28" /></a></li>-->
    <li><a href="#">'.$_SESSION['userInfo']['name'].'</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Изход</a></li>
                            

                       
';
            } else {
                echo '
                <li><a href="register.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Регистрация</a></li>
                <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Вход</a></li> ';
            }
	}
	
	
	
	
    public static function getFileList($directory) { #must be bootstrap table
        $count = 0;
        if ($handle = opendir($directory)) {
            echo '<table>';
            echo '<tbody>';
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $count++;
                    echo '<tr><td class="right">'.$count.' - '.$entry.'</td><td class="right"><a href="'.$directory."".$entry.'">'
                            . '<img src="img/web/Downloads.png" width="64" height="64" title="Свали" />'
                            . '<img src="img/web/icon_download.png" width="64" height="64" title="Свали" />'
                            . '</a></td></tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
            closedir($handle);
        }
    }
    
    function getUserAgent() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if ($userAgent !== null && $userAgent !== "") {
            return $userAgent;
        } else {
            return null;
        }
    }
    
    public static function getUserPlatform($userAgent) {
        $platform = "";

        $operatingSystems = array(
            '/linux/i' => 'Linux',
            '/macintosh|mac os x/i' => 'Mac OS',
            '/windows|win32/i' => 'Windows',
            '/android/i' => 'Android',
            '/iphone/i' => 'iOS',
        );
        
        foreach ($operatingSystems as $key => $value) { 
            if (preg_match($key, $userAgent)) {
                $platform = $value;
            } else;
        }
        
        if ($platform == null || $platform === "") {
            $platform = "Unknown";
        } else;
        
        return $platform;
    }
    
    public static function getUserBrowser($userAgent) {
        $browser = "";

        $browsers = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Mozilla Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Google Chrome',
            '/edge/i' => 'Microsoft Edge',
            '/opera/i' => 'Opera'
        );#check for ice wheasle ans chromium

        foreach ($browsers as $key => $value) {
            if (preg_match($key, $userAgent)) {
                $browser = $value;
            } else;
        }
        
        if ($browser == null || $browser === "") {
            $browser = "Unknown";
        } else;

        return $browser;
    }
    
    public static function getPlatformIcon($platform) {
        $icon = "";
        $platformIcon = "";
        switch ($platform) {
            case 'Windows':
                $icon = 'windows.png';
                break;
            case 'Linux':
                $icon = 'linux.png';
                break;
            case 'Android':
                $icon = 'android.png';
                break;
            case 'Mac OS':
                $icon = 'apple.png';
                break;
            case 'iOS':
                $icon = 'apple.png';
                break;
            default:
                $icon = "unknown.png";
                break;
        }
        $platformIcon = '<img src="img/system/platforms/'.$icon.'" width="20" height="20" />';
        return $platformIcon;
    }
    
    public static function getBrowserIcon($browser) {
        $icon = "";
        $browserIcon = "";
        switch ($browser) {
            case 'Mozilla Firefox':
                $icon = "firefox.png";
                break;
            case 'Google Chrome':
                $icon = "chrome.png";
                break;
            case 'Safari':
                $icon = "safari.png";
                break;
            case 'Opera':
                $icon = "opera.png";
                break;
            case 'Microsoft Edge':
                $icon = "edge.png";
                break;
            case 'Internet Explorer':
                $icon = "explorer.png";
                break;
            default:
                $icon = "unknown.png";
                break;
        }
        $browserIcon = '<img src="img/system/browsers/'.$icon.'" width="20" height="20" />';
        return $browserIcon;
    }
    /*
    public static function getUserPlatform($userAgent) {
        $platform = null;
        $iconSource = '<img class="valign" src="img/system/platforms/';
        $iconDimensions = '" width="28" height="28" />';
        
        $operatingSystems = array(
            '/linux/i' => ''.$iconSource.'linux.png'.$iconDimensions.' Linux',
            '/macintosh|mac os x/i' => ''.$iconSource.'apple.png'.$iconDimensions.' Mac OS',
            '/windows|win32/i' => ''.$iconSource.'windows.png'.$iconDimensions.' Windows',
            '/android/i' => ''.$iconSource.'android.png'.$iconDimensions.' Android',
            '/iphone/i' => ''.$iconSource.'apple.png'.$iconDimensions.' iOS',
        );
        
        foreach ($operatingSystems as $key => $value) { 
            if (preg_match($key, $userAgent)) {
                $platform = $value;
            } else;
        }
        
        if ($platform == null) {
            $platform = "Unknown OS Platform";
        } else;
        
        return $platform;
    }*/
    
    
    /*
    public static function getUserBrowser($userAgent) {
        $browser = "";
        $iconSource = '<img class="valign" src="img/system/browsers/';
        $iconDimensions = '" width="24" height="24" />';
        
        $browsers = array(
            '/msie/i' => ''.$iconSource.'explorer.png'.$iconDimensions.' Internet Explorer',
            '/firefox/i' => ''.$iconSource.'firefox.png'.$iconDimensions.' Mozilla Firefox',
            '/safari/i' => ''.$iconSource.'safari.png'.$iconDimensions.' Safari',
            '/chrome/i' => ''.$iconSource.'chrome.png'.$iconDimensions.' Google Chrome',
            '/edge/i' => ''.$iconSource.'edge.png'.$iconDimensions.' Microsoft Edge',
            '/opera/i' => ''.$iconSource.'opera.png'.$iconDimensions.' Opera',
            
            '/netscape/i' => ' '.$iconSource.'netscape.png'.$iconDimensions.'Netscape',
            '/maxthon/i' => ''.$iconSource.'maxthon.png'.$iconDimensions.' Maxthon',
            '/konqueror/i' => ''.$iconSource.'konqueror.png'.$iconDimensions.' Konqueror',
            '/rockmelt/i' => ''.$iconSource.'rockmelt.png'.$iconDimensions.' Rockmelt'
        );

        foreach ($browsers as $key => $value) { 
            if (preg_match($key, $userAgent)) {
                $browser = $value;
            } else;
        }
        
        if ($browser == null) {
            $browser = "Unknown Browser";
        } else;

        return $browser;
    }*/
        
    public static function getUserIP() {
        $ipAddress = null;

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipAddress = "Unknown";
        }

        return $ipAddress;
    }
        
    public static function setTimeZone($timeZone = "Europe/Sofia") {
        date_default_timezone_set($timeZone); 
    }

    public static function getTimeZone() {
        $timeZone = date_default_timezone_get();
        return $timeZone;
    }

    public static function getDateTime() {
        $dateTime = date('d-m-Y H:i:s');
        return $dateTime;
    }

    public static function getDate() {
        $date = date('d-m-Y');
        return $date;
    }

    public static function getTime() {
        $time = date('H:i:s');
        return $time;
    }
        
}