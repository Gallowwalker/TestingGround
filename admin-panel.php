<?php
include './system.php';
include './database.php';
include './modules/admin.php';
System::sessionStart();
System::setContentTitle("Админ панел");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");
if ((isset($_SESSION['isLogged']) === true)) {
#&& ($_SESSION['userInfo']['type'] === "Администратор" || $_SESSION['userInfo']['type'] === "Главен Модератор" || $_SESSION['userInfo']['type'] === "Модератор")
    $link = '<a href="admin-panel.php" class="btn btn-sm btn-success" role="button">Към главния панел</a>';
    $_SESSION['page'] = 0;
    
    $name = $_SESSION['userInfo']['name'];
    Database::setDatabaseConnection();
    
    $sqlQuery = 'SELECT profile_picture FROM user_info WHERE user_id = '.$_SESSION['userInfo']['id'].';';
    $infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery); #check if empty and catch error
    $outputData = Database::fetchAssoc($infoDataQuery);

# full control , rank , name , exit
           
?>  

        <!--<div class="panel panel-primary">
    <!--<div class="panel-heading"><strong></strong></div>
    <div class="panel-body">
        <div style="width: 130px; text-align: center; position: relative; float: left;">
            <div></div>
            <div style="margin-top: 10px; font-weight: bold; color: #0066ff;"></div> 
        </div>
        <div style="width: 975px;  position: relative; float: left;">
            <div style="padding-left: 30px; position: relative; float: left;">
               <!-- <button type="submit" name="addPost" class="btn btn-primary">Публикувай</button><br /><br />
                <button type="submit" name="editPost" class="btn btn-primary">Редактирай</button><br /><br />
                <button type="submit" name="deletePost" class="btn btn-primary">Изтрий</button>
            </div>-->
            <div style=" position: relative; float: left; border: 0px solid black;">
                <?php Admin::selectExams($link); ?>
             
                
            </div>
            <!--<div style=" position: relative; float: left;">
            
            </div>
            <!--<div style="margin-top: 40px; text-align: right;"><p></p></div>
        </div>
    </div>
</div>-->
<?php
} else {
	/*header('Location: index.php');
	exit;*/
    
    echo'
	Error
		<p><img src="img/error.png" width="24" heigh="24" />&nbsp;<span style="bottom:7px; position:relative; color: violet;">Your access to this page is denied.</span></p>
               ';
}
System::getPageFooter();
