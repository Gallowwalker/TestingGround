<?php
include './system.php';
include './database.php';
System::sessionStart();
System::setContentTitle("Настройки на профила");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) === true) {
    
Database::setDatabaseConnection();

$userID = $_SESSION['userInfo']['id'];

$query = 'SELECT specialty, faculty, course, profile_picture FROM user_info WHERE user_id = '.$userID.';';
$infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $query);
//$outputData = mysqli_fetch_assoc($infoDataQuery);


if (Database::getRowNumber($infoDataQuery) == 0) {  //mysqli_num_rows($infoDataQuery)
    
    echo '<p>Все още не си задал информация.</p>';
    ?>
    <form action="profile-settings.php" method="POST">
    
    <input type="hidden" name="formSubmit" value="1" />
                    <input type="submit" name="submit" value="Направи промени" />
</form>
    <?php
} else {
    
    if (isset($_POST['formSubmit']) == 1) {
        echo 'must be submited'; //run update query  - echo success

    } else {
        
    $outputData = Database::fetchAssoc($infoDataQuery); //$outputData = mysqli_fetch_assoc($infoDataQuery);
    $userAgent = System::getUserAgent();
    $platform = System::getUserPlatform($userAgent);
    $browser = System::getUserBrowser($userAgent);
    echo '
    <div class="panel panel-default"> <!--primary-->
    <!--<div class="panel-heading"><strong></strong></div>-->
    <div class="panel-body">
        <div style="width: 130px; text-align: center; position: relative; float: left;">
            <div><img class="img-thumbnail" alt="" data-src="" style="width: 130px; height: 130px;" src="'.$outputData['profile_picture'].'" data-holder-rendered="true"></div>
            <!--<div style="margin-top: 10px; font-weight: bold; color: #0066ff;"></div>-->
        </div>
        <div style="width: 975px;  position: relative; float: left;">
            <div style="padding-left: 30px;"><br /><a target="_blank" href=""></a></div>
            <!--<div style="margin-top: 40px; text-align: right;"><p>Публикувано на: <span style="color: #0066ff;"></span></p></div>-->
        </div>
    </div>
    '.$platform.$browser.'
    </div>';
    ?>

<form action="profile-settings.php" method="POST" class="form-inline">

        <input type="hidden" name="formSubmit" value="1" />
        <!--<input type="submit" name="submit" value="Направи промени" />-->
        <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;&nbsp;Направи промени</button>
    </form>
</div>
<?php
    }

}






} else {
    
}   
System::getPageFooter();

