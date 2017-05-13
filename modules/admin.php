<?php
final class Admin {
    
    private function __construct() {}
    
    public function addExam() {
    ?>
    <form action="" method="POST" class="form-inline">
        <div class="form-group">
            <div class="input-group"> <!-- check and filter name -->
                <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
                <input type="text" name="examName" placeholder="Име на тест" class="form-control" value="" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <label class="radio-inline">Тип</label> 
            <label class="radio-inline"><input type="radio" name="examType" value="Контролна" />Контролна</label>
            <label class="radio-inline"><input type="radio" name="examType" value="Изпит" />Изпит</label>
       
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <label class="radio-inline">Видим</label> 
            <label class="radio-inline"><input type="radio" name="visible" value="Да" />Да</label>
            <label class="radio-inline"><input type="radio" name="visible" value="Не" />Не</label>
       
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <label class="radio-inline">Достъпен</label> 
            <label class="radio-inline"><input type="radio" name="available" value="Да" />Да</label>
            <label class="radio-inline"><input type="radio" name="available" value="Не" />Не</label>
       
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-time"></div></div>
                <input type="text" name="examDuration" placeholder="Време (мин.)" class="form-control" value="" required />
            </div>
        </div>
         <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></div>
                <input type="password" name="examPassword" placeholder="Парола" class="form-control" value="" required />
            </div>
        </div>
        
    
        <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-question-sign"></div></div>
        <select name="secretQuestion" class="form-control">
            <option selected>Взимай въпроси от</option>
            <option>operating_systems_questions</option>
            <option>database_questions</option>
            <option>other_questions</option>
        </select>
    </div>
        <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-exclamation-sign"></div></div>
        <select name="secretQuestion" class="form-control">
            <option selected>Сравнявай отговори от</option>
            <option>operating_systems_answers</option>
            <option>database_answers</option>
            <option>other_answers</option>
        </select></div>
    </div>
    <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
                <input type="text" name="openedCount" placeholder="Брой отворени" class="form-control" value="" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
                <input type="text" name="closedCount" placeholder="Брой затворени" class="form-control" value="" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
                <input type="text" name="taskCommandsCount" placeholder="Брой задачи(команди)" class="form-control" value="" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
                <input type="text" name="taskScriptCount" placeholder="Брой задачи(скрипт)" class="form-control" value="" required />
            </div>
        </div>
    </form>
    <?php
        $sqlQuery = 'INSERT INTO exams (exam_name, exam_type, visible, available, duration, password, question_type, question_count, belong_to) VALUES ();';
    }
    
    public function editExam() {
        
    }
    
    public function deleteExam() {
        
    }
    
    public function selectExams($link) {
        $count = 0;
        $page = 0;
        $index = 1;
        $clause = "";
        $result = "";
        
        $status = "";
        $user = "";
        
        $allExams = "selected";
        $checkedExams = "";
        $uncheckedExams = "";
        
        $maxPageResults = 10;
        $pageFlag = false;
        
        //setcookie("TestCookie", $count, time()+43200);
        
        if (filter_input(INPUT_GET, 'page')) {
            $index = filter_input(INPUT_GET, 'page');
            $pageFlag = true;
        }
        
        if (filter_input(INPUT_POST, 'formSubmit') == 1) {
            $user = trim(filter_input(INPUT_POST, 'search'));
            switch (filter_input(INPUT_POST, 'option')) {
                case 'Всички тестове':
                    $allExams = "selected";
                    $clause = ' WHERE usr.username LIKE "'.$user.'%" OR usr.name LIKE "'.$user.'%"';
                    break;
                case 'Само проверените':
                    $checkedExams = "selected";
                    $clause = ' WHERE (usr.username LIKE "'.$user.'%" OR usr.name LIKE "'.$user.'%") AND fres.final_score != 0';
                    break;
                case 'Само непроверените':
                    $uncheckedExams = "selected";
                    $clause = ' WHERE (usr.username LIKE "'.$user.'%" OR usr.name LIKE "'.$user.'%") AND fres.final_score = 0';
                    break;
                default:
                    echo 'There was some error during the exam selection.';
                    break;
            }
        }
        
//        if (strlen($user) > 0) {
//            $clause = ' WHERE usr.username = "'.$user.'" OR usr.name = "'.$user.'"';
//        }
        
        #ambigous query id and id - user_id -works

        $sqlQuery = 'SELECT usr.id, usr.username, usr.name, exm.id, exm.exam_name, exm.exam_type,
        fres.user_id, fres.exam_id, fres.started, fres.ended, fres.system, fres.browser, fres.ip, fres.final_score, fres.checked_by
        FROM users usr JOIN final_results fres ON usr.id = fres.user_id JOIN exams exm ON exm.id = fres.exam_id'.$clause.'';

        $infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        
        $msgFlag = false;
        $outputData = Database::fetchAssoc($infoDataQuery);
        if (Database::getRowNumber($infoDataQuery) == 0) {
            $msgFlag = true;
        } else {
            $msgFlag = false;
        }

        Database::setDataSeek($infoDataQuery);
        
        $roundPageNumber = round(Database::getRowNumber($infoDataQuery) / $maxPageResults, 0);
        $rawPageNumber = Database::getRowNumber($infoDataQuery) / $maxPageResults;
        if ($rawPageNumber >= $roundPageNumber) {
            $page++;
            $roundPageNumber++;
        } else {
            $page++;
        }
        if ($roundPageNumber == 0) {
            $roundPageNumber++;
        }
        
        $index--;
        $recordIndex = $index * $maxPageResults;
        $sqlQuery.=' LIMIT '.$maxPageResults.' OFFSET '.$index * $maxPageResults.';';
        
        $infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        
        #lock when 2 users edit the same exam
        ?>
        <form action="admin-panel.php" method="POST" class="form-inline" autocomplete="off">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><div class="glyphicon glyphicon-search"></div></div>
                    <input type="text"  name="search" placeholder="Потребителско име" class="form-control" value="<?php echo $user; ?>" required />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <select class="form-control" name="option">
                        <option <?php echo $allExams; ?>>Всички тестове</option>
                        <option <?php echo $checkedExams; ?>>Само проверените</option>
                        <option <?php echo $uncheckedExams; ?>>Само непроверените</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="formSubmit" value="1" />
                <button type="submit" name="submit" class="btn btn-primary">Търси</button>
            </div>
        </form>
        <div class="page-header">
            <h3>Тестове за преглед и оценяване <?php echo $link; ?></h3>
        </div>
        
        <?php
        if (Database::getRowNumber($infoDataQuery) > 0) {
        echo '<table class="table table-hover table-condensed table-responsive my-table table-condensed">
            <thead>
                <tr>
                    <th>№</th>';
                        if (strlen($user) > 0 && $allExams == "selected") {
                            echo '<th><div class="glyphicon glyphicon-edit"></div></th>';
                        }
                    echo '
                    <th>Потребител</th>
                    <th>Име</th>
                    <th>Тест</th>
                    <th>Тип</th>
                    <th>Започнал</th>
                    <th>Завършил</th>
                    <th>Система</th>
                    <th>Браузър</th>
                    <th>IP адрес</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody> ';
       //$count = $_COOKIE['TestCookie'];
        $count = $recordIndex;

        while ($outputData = Database::fetchAssoc($infoDataQuery)) {
            $count++;
            echo '
            <tr >
                <td>'.$count.'</td>';
                if (strlen($user) > 0 && $allExams == "selected") {
                    if ($outputData['checked_by'] === "Недефиниран") {
                        $status = "unchecked.png";
                    } elseif ($outputData['checked_by'] !== "Недефиниран") {
                        $status = "checked.png";
                    }
                    echo '<td><img src="img/system/status/'.$status.'" width="20" height="20" /></td>';
                }
                echo '
                <!--<div class="glyphicon glyphicon-remove"></div>-->
                <td>'.$outputData['username'].'</td>
                <td><a href="user-profile.php?userId='.$outputData['user_id'].'">'.$outputData['name'].'</a></td>
                <td>'.$outputData['exam_name'].'</td>
                <td>'.$outputData['exam_type'].'</td>
                <td>'.date('d-m-y', $outputData['started']).' <font color="#0066ff">|</font> '.date('H:i:s', $outputData['started']).'</td>
                <td>'.date('d-m-y', $outputData['ended']).' <font color="#0066ff">|</font> '.date('H:i:s', $outputData['ended']).'</td>
                <td>'.System::getPlatformIcon($outputData['system']).'</td>
                <td>'.System::getBrowserIcon($outputData['browser']).'</td>
                <td>'.$outputData['ip'].'</td>
                <td>
                    <a href="exam-review.php?examName='.$outputData['exam_name'].'" class="btn btn-sm btn-primary" role="button" title="Преглед"><span class="glyphicon glyphicon-eye-open"></span></a>
                    <a href="exam-review.php?examName='.$outputData['exam_name'].'&editMode=true" class="btn btn-sm btn-success" role="button" title="Оценяване"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="" class="btn btn-sm btn-danger" role="button" title="Изтриване"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
            </tr>'; 
        }
        //setcookie("TestCookie", $count, time()+43200);  /* expire in 12 hours */
        echo '</tbody>
        </table>';
            
        } else {
        
        if ($msgFlag) {
            echo '<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Грешка</h3>
                    </div>
                    <div class="panel-body">
                        <p>Не съществува такъв потребител.</p>
                     </div>
                </div>';
        } else {
            if ($pageFlag) {
                header("Location: admin-panel.php?page=".$index."");
                exit;
            }
            echo '<div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Статус</h3>
                </div>
                <div class="panel-body">
                    <p>В момента няма намерени тестове.</p>
                 </div>
            </div>';
        }
        } 
        
            ?>
            <ul class="pagination"> <!--pagination-sm-->
                <li><a href="<?php echo 'admin-panel.php?page='.$index.''; ?>">&laquo;</a></li>
                <?php
                    $pageLimit = 2;
                    //$pageLimitFlag = false;
                    $index++;
                    //if (filter_input(INPUT_GET, 'page') > $pageLimit) {
                       //$page = filter_input(INPUT_GET, 'page');
                    //}
                    while ($page <= $roundPageNumber) {
                        if (filter_input(INPUT_GET, 'page') == $page) {
                            $active = 'active';
                        } else if (!filter_input(INPUT_GET, 'page') && $page == 1) {
                            $active = 'active';
                        } else {
                            $active = '';
                        }
                        echo '<li class="'.$active.'"><a href="admin-panel.php?page='.$page.'">'.$page.'</a></li>'; #must get results from pagenumber + maxpageresults 
                        $page++;
                        
                        //if ($page > $pageLimit) {
                            //echo '<li><a href=""> ... </a></li>';
                            //echo '<li><a href="admin-panel.php?page='.$roundPageNumber.'">'.$roundPageNumber.'</a></li>';
                       // }
                    }
                ?>
                <li><a href="<?php echo 'admin-panel.php?page='.($index+1).''; ?>">&raquo;</a></li>
            </ul>
            <?php
                        

        
    }
    
    public function showUserProfile() {
        echo 'here';
    }
    
}