<?php
include './system.php';
include './database.php';
include './modules/exam-info.php';
System::sessionStart();

$examName = "";
$editMode = "";

if (filter_input(INPUT_GET, 'examName')) {
    $examName = filter_input(INPUT_GET, 'examName');
}

if (filter_input(INPUT_GET, 'editMode')) {
    $editMode = filter_input(INPUT_GET, 'editMode');
}

System::setContentTitle($examName);
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) === true) {
    
    $userID = $_SESSION['userInfo']['id'];
    $hasTasks = false;
    Database::setDatabaseConnection();
    
    $sqlQuery = 'SELECT id, available_results FROM exams WHERE exam_name = "'.$examName.'"';
    $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
    $examInfo = Database::fetchAssoc($outputData);
    $examID = $examInfo['id'];
    $results = $examInfo['available_results'];
    if ($examInfo['available_results'] === "Да") {
        $object = new ExamInfo();
        if ($_SESSION['userInfo']['type'] === "Администратор" || $_SESSION['userInfo']['type'] === "Модератор") {
            $hasTasks = $object->getQuestions($userID, $examID, $editMode);
        } else {
            $hasTasks = $object->getQuestions($userID, $examID, false);
        }
        $object->getFinalResults($userID, $examID, $hasTasks);
    } elseif ($examInfo['available_results'] === "Не") {
        if ($_SESSION['userInfo']['type'] === "Администратор" || $_SESSION['userInfo']['type'] === "Модератор") {
            $object = new ExamInfo();
            $hasTasks = $object->getQuestions($userID, $examID, $editMode);
            $object->getFinalResults($userID, $examID, $hasTasks);
        } else {
            echo '<img src="img/system/security/restricted.png" /><br />';
            echo 'Добър опит, но неуспешен. Това действие ще бъде докладвано до администратора, а достъпът ти до сайта ще бъде спрян.';
            echo 'Ще бъдеш изритан от профила си автоматично след 15 секунди.';

            $possibleAction = 'breaching result page when its forbidden';
            $eventPage = 'exam-review.php';
            $eventTime = mktime();
            $userAgent = System::getUserAgent();
            $system = System::getUserPlatform($userAgent);
            $browser = System::getUserBrowser($userAgent);
            $ip = System::getUserIP();

            $securityQuery = 'INSERT INTO security (user_id, possible_action, event_page, event_time, system, browser, ip) VALUES ('.$userID.', "'.$possibleAction.'", "'.$eventPage.'", '.$eventTime.', "'.$system.'", "'.$browser.'", "'.$ip.'");';
            Database::runQuery(Database::getDatabaseConnection(), $securityQuery);
            $disableQuery = 'UPDATE users SET active = "Не" WHERE id = '.$userID.';';
            Database::runQuery(Database::getDatabaseConnection(), $disableQuery);
            #ban query missing
            header("refresh:15;url=logout.php");
            exit;
        }
    } else {
        
    }

} else {
    echo 'you must be logged in';
}

System::getPageFooter();