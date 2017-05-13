<?php
include './system.php';
include './database.php';
System::sessionStart();
System::setContentTitle("Потребителски профил");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) === true) {
    
Database::setDatabaseConnection();

$userId = $_SESSION['userInfo']['id'];

if ($_SESSION['userInfo']['type'] === "Администратор" || $_SESSION['userInfo']['type'] === "Модератор") {
    if (filter_input(INPUT_GET, 'userId')) {
        $userId = filter_input(INPUT_GET, 'userId');
    }
}

$sqlQuery = 'SELECT u.username, u.name, u.email, u.gender, u.type, u.active, u.join_date, u.last_login, u.last_ip, u.status,
ui.specialty, ui.faculty, ui.course, ui.profile_picture FROM users u JOIN user_info ui ON u.id = ui.user_id WHERE u.id = '.$userId.';';

$userDataQuery = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
$outputData = Database::fetchAssoc($userDataQuery);

?>

<div class="panel panel-default">
    <div class="panel-heading">Information</div>
    <div class="panel-body">
        <div style="float: left; position: relative; border: 1px solid black;">
            <img class="img-thumbnail" src="<?php echo $outputData['profile_picture']; ?>" width="128" height="128" /><br />
            <p><h4><?php echo $outputData['name']; ?></h4></p>
        </div>
        <div style="float: left; position: relative; border: 1px solid black; font-size: 18px;">
            Факултетен № / Потребителско име - <?php echo $outputData['username']; ?> <br />
            Email - <?php echo $outputData['email']; ?> <br />
            Пол - <?php echo $outputData['gender']; ?> <br />
            Тип - <?php echo $outputData['type']; ?> <br />
            Активен - <?php echo $outputData['active']; ?> <br />
            Регистриран на - <?php echo $outputData['join_date']; ?> <br />
            Последно влязъл на - <?php echo $outputData['last_login']; ?> <br />
            От адрес - <?php echo $outputData['last_ip']; ?> <br />
            Статус - <?php
            if ($outputData['status'] === "online") {
                echo '<img src="img/system/status/online.png" />';
            } else {
                echo '<img src="img/system/status/offline.png" />';
            }
            
            ?> <br />
            Специалност - <?php echo $outputData['specialty']; ?> <br />
            Факултет - <?php echo $outputData['faculty']; ?> <br />
            Курс - <?php echo $outputData['course']; ?> <br />
        </div>
    </div>
</div>

<?php


}
