<?php
include './system.php';
include './database.php';
include './event.php';
System::sessionStart();
System::setContentTitle("Преглед и решаване на тестове");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) === true) {
    
    Database::setDatabaseConnection();

?>
    <div class="page-header">
        <h3>Достъпни тестове</h3>
    </div>

        <?php
        
        $sqlQuery = 'SELECT exam_name, exam_type, duration, available FROM exams WHERE visible = "Да";';
        $examsDataQuery = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
            
        if (Database::getRowNumber($examsDataQuery) > 0) {
            
        
            $type = null;
            $state = null;
            $glyphicon = null;
            $duration = 0;

            $index = 0;
            
            echo '<table class="table table-hover table-condensed table-responsive my-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Тест</th>
                            <th>Тип</th>
                            <th>Валидност</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while($outputData = Database::fetchAssoc($examsDataQuery)){ //$outputData = mysqli_fetch_assoc($examsDataQuery)
                $index++;
                if ($outputData['available'] === "Да") {
                    $type = "success";
                    $state = "";
                    $glyphicon = "pencil";
                    $examLink = "started-exam.php?examName=".$outputData['exam_name']."";
                } elseif ($outputData['available'] === "Не") {
                    $type = "danger";
                    $state = "disabled";
                    $glyphicon = "lock";
                    $examLink = "";
                } else {
                    
                }
                $duration = $outputData['duration'] / 60;
                echo '
                    <tr>
                        <td>'.$index.'</td>
                        <td>'.$outputData['exam_name'].'</td>
                        <td>'.$outputData['exam_type'].'</td>
                        <td><img src="img/system/events/alarm-clock-ringing.png" width="24" height="24" /> '.$duration.' мин.</td>
                        <td><a href="'.$examLink.'" class="btn btn-'.$type.' '.$state.'" role="button"><span class="glyphicon glyphicon-'.$glyphicon.'"></span>&nbsp;&nbsp;Решаване</a></td>
                    </tr>';

                
            }
            echo '</tbody>
                </table>';
        } else {
            
                echo 'В мемента няма активни тестове.';
        }
            
?> 
        

    <div class="page-header">
        <h3>Решени тестове</h3>
    </div>
cut off after one exam is already completed  - you already finished this exam - session get parameter exam name must not be reached - admin can see even if locked 

        <?php
        
        $userID = $_SESSION['userInfo']['id'];

        $sqlQuery2 = 'SELECT exam_id, started, ended, system, browser, ip FROM final_results WHERE user_id = '.$userID.';';
        $examsDataQuery2 = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery2);
        //$examIDS = array();
        
        if (Database::getRowNumber($examsDataQuery2) > 0) {
            $index = 0;
            $resultsLink = null;
            $type2 = null;
            $state2 = null;
            $glyphicon2 = null;
            echo '<table class="table table-hover table-condensed table-responsive my-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Тест</th>
                            <th>Тип</th>
                            <th>Валидност</th>
                            <th>Започнал</th>
                            <th>Завършил</th>
                            <th>Система</th>
                            <th>Браузър</th>
                            <th>IP адрес</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        while ($outputData = Database::fetchAssoc($examsDataQuery2)) {
            //$examIDS[$index] = $outputData['exam_id'];
            $index++;

                $sqlQuery3 = 'SELECT exam_name, exam_type, available_results, duration FROM exams WHERE id = '.$outputData['exam_id'].';';
                $examsDataQuery3 = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery3);
                $outputData2 = Database::fetchAssoc($examsDataQuery3);

                if ($outputData2['available_results'] === "Да") {
                    $type2 = "primary";
                    $state2 = "";
                    $glyphicon2 = "open";
                    $resultsLink = "exam-review.php?examName=".$outputData2['exam_name']."";
                } elseif ($outputData2['available_results'] === "Не") {
                    $type2 = "primary";
                    $state2 = "disabled";
                    $glyphicon2 = "close";
                    $resultsLink = "";
                } else {

                }
                $duration = $outputData2['duration'] / 60;
                echo '  <tr>
                            <td>'.$index.'</td>
                            <td>'.$outputData2['exam_name'].'</td>
                            <td>'.$outputData2['exam_type'].'</td>
                            <td><img src="img/system/events/alarm-clock-ringing.png" width="24" height="24" /> '.$duration.' мин.</td>
                            <td>Дата: '.date('d-m-Y', $outputData['started']).' <br /> Час: '.date('H:i:s', $outputData['started']).'</td>
                            <td>Дата: '.date('d-m-Y', $outputData['ended']).' <br /> Час: '.date('H:i:s', $outputData['ended']).'</td>
                            <td>'.System::getPlatformIcon($outputData['system']).'</td>
                            <td>'.System::getBrowserIcon($outputData['browser']).'</td>
                            <td>'.$outputData['ip'].'</td>
                            <td><a href="'.$resultsLink.'" class="btn btn-'.$type2.' '.$state2.'" role="button"><span class="glyphicon glyphicon-eye-'.$glyphicon2.'"></span>&nbsp;&nbsp;Преглед</a></td>
                        </tr>';
            }
            echo '</tbody>
                </table>';
        } else {
        
           echo '<div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Статус</h3>
                    </div>
                    <div class="panel-body">
                        <p>Все още нямаш решени тестове.</p>
                     </div>
                </div>';
        }
        

} else {
    Event::getExamsLoginError();
}
System::getPageFooter();