<?php
include './system.php';
include './database.php';
System::sessionStart();
System::setContentTitle("Информация");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");
Database::setDatabaseConnection();
$query1 = 'SELECT subject, content, posted_by, post_date, url FROM web_info ORDER BY id DESC;';
$infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $query1);
while ($outputData = Database::fetchAssoc($infoDataQuery)) {
    
    $query2 = 'SELECT ui.profile_picture FROM users u JOIN user_info ui ON u.id = ui.user_id JOIN web_info wi ON u.name = "'.$outputData['posted_by'].'";';
    $infoDataQuery2  = Database::runQuery(Database::getDatabaseConnection(), $query2);
    $outputData2 = Database::fetchAssoc($infoDataQuery2);
    
    echo '<div class="panel panel-primary">
    <div class="panel-heading"><strong>'.$outputData['subject'].'</strong></div>
    <div class="panel-body">
        <div style=" height: 155px;">
            <div style="width: 130px; text-align: center; position: relative; float: left;">
                <div><img class="img-thumbnail" alt="" data-src="" style="width: 130px; height: 130px;" src="'.$outputData2['profile_picture'].'" data-holder-rendered="true"></div>
                
            </div>
            <div style="width: 950px; height: 132px; position: relative; float: left;">
                <div style="padding-left: 30px;"><p>'.$outputData['content'].'<br /><a target="_blank" href="'.$outputData['url'].'">'.$outputData['url'].'</a></p></div>
                
            </div>
            <div style="width: 130px; padding-top: 10px; text-align: center; position: relative; float: left; font-weight: bold; color: #0066ff;">'.$outputData['posted_by'].'</div> 
                <div style=" text-align: right; padding-top: 10px; position: relative; float: left; width: 1075px; height: 22px; text-align: right;"><p>Публикувано на: <span style="color: #0066ff;">'.date('d-m-Y - H:i', $outputData['post_date']).'</span></p></div>
        </div>
    </div>
</div> ';
}
Database::freeResultSet($infoDataQuery);
Database::closeDatabaseConnection();
System::getPageFooter();