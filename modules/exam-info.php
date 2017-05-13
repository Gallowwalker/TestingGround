<?php
final class ExamInfo {
    
    public function __construct() {}
    
    public function getQuestions($userID, $examID, $editMode) {
        $sqlQuery = 'SELECT osq.question, osq.question_type, er.student_answer, er.points FROM operating_systems_questions osq JOIN exam_results er ON
        osq.id = er.question_id WHERE er.user_id = '.$userID.' AND er.exam_id = '.$examID.';'; //belong to
        $examInfo = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        $index = 0;
        $check = '';
        $hasTasks = 0;
        $pointsArray = array();
        if (Database::getRowNumber($examInfo) == 0) {
            echo '<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Грешка</h3>
                    </div>
                    <div class="panel-body">
                        <p>Ти все още не си решил този тест и нямаш въпроси за преглед.</p>
                     </div>
                </div>';
        } else {
            
            if (filter_input(INPUT_POST, 'formSubmit') == 1) {
                
                
                
                
                $editMode = false;
                $sqlQuery = 'UPDATE final_results SET is_checking_now = "false" WHERE exam_id = '.$examID.'';
                Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
            }
            
            if ($editMode) {
                $sqlQuery = 'UPDATE final_results SET is_checking_now = "true" WHERE exam_id = '.$examID.'';
                Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
                echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
            }
        ?>

        <table class="table table-striped table-responsive table-condensed">
            <tbody>
                <tr>
                    <th>№</th>
                    <th>Въпрос / Задача</th>
                    <th>Отговор</th>
                    <th>Точки</th>
                    <th><div class="glyphicon glyphicon-edit"></div></th>
                </tr>
        <?php
        
            if ($editMode) {
                
                
                
                while ($outputData = Database::fetchAssoc($examInfo)) {
                $index++;
                if ($outputData['points'] > 0) {
                    $check = '<img src="img/system/status/checked.png" width="20" height="20" title="Верен" />';
                } elseif ($outputData['points'] == 0) {
                    $check = '<img src="img/system/status/unchecked.png" width="20" height="20" title="Грешен" />';
                    if ($outputData['points'] === "Неоценен") {
                        $check = '<img src="img/system/status/unknown.png" width="20" height="20" title="Неопределен" />';
                    }
                }

                echo '<tr><td>'.$index.'</td><td>'.$outputData['question'].'</td><td>'.$outputData['student_answer'].'</td><td>
                   
      
           
            <input type="text"  name="points'.$index.'" value="'.$outputData['points'].'" />
       </td><td>'.$check.'</td></tr>';

                if ($outputData['question_type'] === "Задача(Команди)" || $outputData['question_type'] === "Задача(Скрипт)") {
                    $hasTasks++;
                }
                
            }
                
            } else {
       
            
                while ($outputData = Database::fetchAssoc($examInfo)) {
                    $index++;
                    if ($outputData['points'] > 0) {
                        $check = '<img src="img/system/status/checked.png" width="20" height="20" title="Верен" />';
                    } elseif ($outputData['points'] == 0) {
                        $check = '<img src="img/system/status/unchecked.png" width="20" height="20" title="Грешен" />';
                        if ($outputData['points'] === "Неоценен") {
                            $check = '<img src="img/system/status/unknown.png" width="20" height="20" title="Неопределен" />';
                        }
                    }

                    echo "<tr>\n\t<td>".$index."</td><td>".$outputData['question']."</td>\n<td>".$outputData['student_answer']."</td>\n<td>".$outputData['points']."</td>\n<td>".$check."</td>\n</tr>";

                    if ($outputData['question_type'] === "Задача(Команди)" || $outputData['question_type'] === "Задача(Скрипт)") {
                        $hasTasks++;
                    }
                }
                
                
            }
            
        
        ?>
            </tbody>
        </table>

        <?php
        
        if ($editMode) {
            
        
            echo '<input type="hidden" name="formSubmit" value="1">
                    <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;Оцени</button>
            </form>';
        }
        
        
        $index = 0;
        Database::freeResultSet($examInfo);
        if ($hasTasks > 0) {
            return true;
        } else {
            return false;
        }
    }
    }
    
    
    
    public function getFinalResults($userID, $examID, $hasTasks) {
        $sqlQuery = 'SELECT started, ended, ip, temporary_points, temporary_score, final_points, final_score, checked_by, comment FROM final_results WHERE user_id = '.$userID.' AND exam_id = '.$examID.';';
        $examInfo = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        $outputData = Database::fetchAssoc($examInfo); //$outputData = mysqli_fetch_assoc($examInfo);
        
        if (Database::getRowNumber($examInfo) == 0) {
            echo '<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Грешка</h3>
                    </div>
                    <div class="panel-body">
                        <p>Ти все още не си решил този тест и нямаш поставена оценка.</p>
                     </div>
                </div>';
        } else {
        $score = "";
        if ($outputData['final_score'] == 0) {
            $score = ' Текуща оценка: '.$outputData['temporary_score'].'';
            $score .= ' Внимание: Тестът все още не е оценен напълно.';
        } else {
            $status = "";
            switch ($outputData['final_score']) {
                case 2:
                    $status = "sad.png";
                    break;
                case 3 || 4:
                    $status = "neutral.png";
                    break;
                case 5 || 6:
                    $status = "happy.png";
                    break;
                default:
                    $status = "unknown.png";
                    break;
            }
            $score = ' Крайна оценка: '.$outputData['final_score'].' <img src="img/system/status/'.$status.'" width="40" height="40" />';
        }
        $checkedBy = "";
        if ($hasTasks) {
            if ($outputData['checked_by'] !== "Недефиниран") {
                $checkedBy = " Задачите са проверени от <strong><em>".$outputData['checked_by']."</em></strong><br />Коментар: ".$outputData['comment'];
            }
        }
        echo '<hr /><div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Краен резултат:</h3>
  </div>
  <div class="panel-body">
    Точки: '.$outputData['temporary_points'].$score.$checkedBy.'
  </div>
</div>';
        }
//        
//        echo '<hr><div class="list-group">
//                <div class="list-group-item active">
//                  <h4 class="list-group-item-heading">Краен резултат:</h4>
//                  <p class="list-group-item-text">Точки: '.$outputData['temporary_points'].$score.'</p>
//                </div>
//              </div>';
        
    }
    
}

