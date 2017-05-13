<?php
final class Exam {
    // directly in query add what type to search
    // must get errors and handle them
    private $examQuestionsType = null;

    private $userID = null;

    private $questionsArray = array(); //
    private $answersArray = array();
    private $multipleAnswerQuestions = array();

    private $questionsDataQuery = null;
    private $examDataQuery = null;

    private $examID = null;
    private $examName = null;
    private $examType = null;
    private $examDuration = 0;
    private $examPassword = "";
    private $examElapsedTime = 0;
    private $examStarted = 0;
    private $examEnded = 0;

    private $questionsTable = null;
    private $answersTable = null;
    private $points = 0;

    private $questionCount = 0;
    private $openedCount = 0;
    private $closedCount = 0;
    private $taskCommandsCount = 0;
    private $taskScriptCount = 0;

    private $tableIdCount = 0;

    private $index = 0;//

    public function getExamPassword() {
        return $this->examPassword;
    }

    public function setVisible($state) {
        $this->visible = $state;
    }

    public function isVisible() {
        return $this->visible;
    }

    public function setExamElapsedTime() {
        $this->examElapsedTime;
    }

    public function getExamElapsedTime() {
        return $this->examElapsedTime;
    }

    public function getExamTime() {
        return $this->examTime;
    }

    public function __construct() {
        $this->examStarted = mktime();
    }

    public function setUserID($id) {
        $this->userID = $id;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function setExamName($examName) {
        $this->examName = $examName;
    }

    public function getExamName() {
        return $this->examName;
    }

    public function setExamQuery() {
        $this->examDataQuery = 'SELECT id, exam_type, duration, password, question_type, opened_count, closed_count, task_commands_count, task_script_count, questions_table, answers_table FROM exams WHERE exam_name = "'.  $this->getExamName().'";'; //Операционни Системи - Тест 1
    }

    public function getQuestionCount($tableName) {
        $sqlQuery = 'SELECT COUNT(id) AS count FROM '.$tableName.';';
        $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        $tableInfo = Database::fetchAssoc($outputData);
        Database::freeResultSet($outputData);
        return $tableInfo['count'];
    }

    public function setExamInfo() {
        $outputData = Database::runQuery(Database::getDatabaseConnection(), $this->examDataQuery);
        $examInfo = Database::fetchAssoc($outputData);
        $this->examID = $examInfo['id'];
        $this->examType = $examInfo['exam_type'];
        $this->examDuration = $examInfo['duration'];
        $this->examPassword = $examInfo['password'];
        $this->examQuestionsType = $examInfo['question_type'];
        $this->openedCount = $examInfo['opened_count'];
        $this->closedCount = $examInfo['closed_count'];
        $this->taskCommandsCount = $examInfo['task_commands_count'];
        $this->taskScriptCount = $examInfo['task_script_count'];
        $this->questionsTable = $examInfo['questions_table'];
        $this->answersTable = $examInfo['answers_table'];
        $this->tableIdCount = $this->getQuestionCount($this->questionsTable);
        Database::freeResultSet($outputData);
        $this->updateQuestionCount();
    }

    public function verifyPassword() {
        $state = 1;
        $password = "";
        $errorArray = array();
        $fail = '<br />&nbsp;<img class="valign" src="img/system/status/fail.png" width="28" height="28" />&nbsp;<span class="text-danger">';
        $end = '</span><br />';
        if (filter_input(INPUT_POST, 'formSubmit2') == 1) {

            if ($this->examPassword === filter_input(INPUT_POST, 'examPassword')) {
                $state = 2;
                //$this->initQuestions();
                //$this->getQuestions();
                return true;

            } else {
                $password = filter_input(INPUT_POST, 'examPassword');
                $errorArray['password'] = ''.$fail.'Грешна парола.'.$end.'';
            }

        }

         if ($state == 1) {
            ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Статус</h3>
                </div>
                <div class="panel-body">
                    <p>За да започнеш теста трябва да въведеш паролата предоставена от преподавател.</p><br />
                    <form action="started-exam.php" method="POST" class="form-inline" autocomplete="off">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></div>
                                <input type="password" name="examPassword" placeholder="Парола" class="form-control" value="<?php echo $password; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="formSubmit2" value="1" />
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-info">Потвърди</button>
                        </div>
                    </form>
                </div>
            </div>
    <?php
    if (isset($errorArray['password'])) {
        echo '<div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Грешка</h3>
                </div>
                <div class="panel-body">'
                    .$errorArray['password'].
                '</div>
            </div>';
    }

         }


    }

    public function getQuestions() {
        if (!empty($_SESSION['refresh'])) { 
            session_destroy();

            
            return;
        } else {
            $_SESSION['refresh'] = true;
        }
    ?>
        <form action="started-exam.php" method="POST" id="questions" name="form1" autocomplete="off" class="form-inline">
            <table class="table table-responsive">
                <tbody>
                    <?php
                    $count = 0;
                    $answer = "";
                    $totalAnswers = 0;
                    $correctAnswers = 0;
                    $correctAnswersArray = array();
                    $hasCorrectAnswer = false;
                    foreach ($this->questionsArray as $value) { #check is task or multiple answers
                        //echo "<tr>\n\t<td>".$value['question']." - <input name=\"answer".$value['id']."\" type=\"text\" /></td>\n</tr>\n";
                        if ($value['question_type'] === "Задача(Команди)" || $value['question_type'] === "Задача(Скрипт)") {
                            $this->index++;
                            echo "<tr>\n\t<td><span class=\"text text-primary\">".$this->index.".</span> ".$value['question']."&nbsp;&nbsp;<br /><div class=\"form-group\">
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\"><div class=\"glyphicon glyphicon-list-alt\"></div></div>
                                    <textarea name=\"answer".$value['id']."\" placeholder=\"Отговор\" class=\"form-control\" rows=\"5\" cols=\"300\" /></textarea>
                                </div>
                            </div></td>\n</tr>\n";
                        } elseif ($value['question_type'] === "Затворен") {
                            $this->index++;
                            $getAnswersQuery = 'SELECT answer, is_correct FROM '.$this->answersTable.' WHERE question_id = '.$value['id'].';';
                            $outputData = Database::runQuery(Database::getDatabaseConnection(), $getAnswersQuery);
                            echo "<tr>\n\t<td><span class=\"text text-primary\">".$this->index.".</span> ".$value['question']."&nbsp;&nbsp;<br /><div class=\"form-group\">
                                <div class=\"input-group\">";
                            while ($answersInfo = Database::fetchAssoc($outputData)) {
                                if ($answersInfo['is_correct'] === "Да") {
                                    $correctAnswersArray[$correctAnswers] = $answersInfo['answer'];
                                    $correctAnswers++;
                                }
                                $totalAnswers++;
                            }
                            if ($correctAnswers > 1) {
                                $this->multipleAnswerQuestions[$count] = $value['id'];
                                $count++;
                            }
                            Database::setDataSeek($outputData);
                            if (!$hasCorrectAnswer) {
                                $correctAnswer = $correctAnswersArray[array_rand($correctAnswersArray)];
                                $hasCorrectAnswer = true;
                            }
                            while ($answersInfo = Database::fetchAssoc($outputData)) {
                                if ($correctAnswers > 1) {
                                    if ($answersInfo['answer'] === $correctAnswer) {
                                        $answer = $correctAnswer;
                                    } elseif ($answersInfo['is_correct'] === "Не") {
                                        $answer = $answersInfo['answer'];
                                    } else {
                                        continue;
                                    }
                                    echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"answer".$value['id']."\" value=\"".$answer."\" />".$answer."</label>";
                                } elseif ($correctAnswers == 1) {
                                    echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"answer".$value['id']."\" value=\"".$answersInfo['answer']."\" />".$answersInfo['answer']."</label>";
                                } else {

                                }
                            }
                            $correctAnswers = 0;
                            echo "</div>
                            </div></td>\n</tr>\n";
                        } else {
                            $this->index++;
                            echo "<tr>\n\t<td><span class=\"text text-primary\">".$this->index.".</span> ".$value['question']."&nbsp;&nbsp;<br /><div class=\"form-group\">
                                <div class=\"input-group\">
                                    <div class=\"input-group-addon\"><div class=\"glyphicon glyphicon-pencil\"></div></div>
                                    <input type=\"text\" name=\"answer".$value['id']."\" placeholder=\"Отговор\" class=\"form-control\" />
                                </div>
                            </div></td>\n</tr>\n";
                        }
                    }
                    $this->index = 0;
                    ?>

                    <tr>
                        <td><input type="hidden" name="formSubmit" value="1" /> <!--if not all field are filled display message -->
                            <button type="submit" name="submitForm" class="btn btn-primary"><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;Завърши теста</button><!--<input type="submit" id="sub" name="submit" value="Завърши теста" />-->
                            <img src="img/system/events/alarm-clock-ringing.png" width="32" height="32" /> <span class="message"></span>
                            on refresh keep time (must not start from the begining)
                        </td>  
                    </tr>
                </tbody>
            </table>
        </form>
    <?php
    }

    public function setQuestionsQuery($id) {
        #$this->questionsDataQuery = 'SELECT id, question, question_type FROM '.$this->questionsTable.' WHERE '.$customQuery.' exam_type = "'.$this->examType.'";';
        $this->questionsDataQuery = 'SELECT question, question_type FROM '.$this->questionsTable.' WHERE id = '.$id.' AND exam_type = "'.$this->examType.'";';
    }

    public function updateQuestionCount() {
        $this->questionCount = $this->openedCount + $this->closedCount + $this->taskCommandsCount + $this->taskScriptCount;
    }

    public function setQuestionsArray($questionID) {
        $outputData = Database::runQuery(Database::getDatabaseConnection(), $this->questionsDataQuery);
        $questionInfo = Database::fetchAssoc($outputData);

        if ($questionInfo['question_type'] === "Отворен") {
            if ($this->openedCount > 0) {
                $this->openedCount--;
                array_unshift($this->questionsArray, array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']));
                /*$this->questionsArray[$this->index] = array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']);
                $this->index++;*/
            }
        }

        if ($questionInfo['question_type'] === "Затворен") {
            if ($this->closedCount > 0) {
                $this->closedCount--;
                array_unshift($this->questionsArray, array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']));
                /*$this->questionsArray[$this->index] = array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']);
                $this->index++;*/
            }
        }

        if ($questionInfo['question_type'] === "Задача(Команди)") {
            if ($this->taskCommandsCount > 0) {
                $this->taskCommandsCount--;
                array_push($this->questionsArray, array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']));
                /*$this->questionsArray[$this->index] = array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']);
                $this->index++;*/
            }
        }

        if ($questionInfo['question_type'] === "Задача(Скрипт)") {
            if ($this->taskScriptCount > 0) {
                $this->taskScriptCount--;
                array_push($this->questionsArray, array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']));
                /*$this->questionsArray[$this->index] = array('id' => $questionID, 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']);
                $this->index++;*/
            }
        }
        #array_unshift - begining / array_push - end
        Database::freeResultSet($outputData);
    }

    public function setQuestions($args = 0, $questionType = "", $questionType2 = "", $questionType3 = "", $questionType4 = "") {   //no need of $questionType4
        /*
        $customQuery = null;
        if ($args == 1) {
            $customQuery = 'question_type = "'.$questionType.'" AND';
        } elseif ($args == 2) {
            $customQuery = 'question_type = "'.$questionType.'" OR question_type = "'.$questionType2.'" AND';
        } elseif ($args == 3) {
            $customQuery = 'question_type = "'.$questionType.'" OR question_type = "'.$questionType2.'" OR question_type = "'.$questionType3.'" AND';
        } elseif ($args == 4) {
            $customQuery = '';
        } else {
            echo 'Internal Error Q2: Something went wrong while setting the questions for the exam.';
        }
        $this->setQuestionsQuery($id, $customQuery);

        $outputData = Database::runQuery(Database::getDatabaseConnection(), $this->questionsDataQuery);
        while ($questionInfo = Database::fetchAssoc($outputData)) { //$questionInfo = mysqli_fetch_assoc($outputData)
            $this->questionsArray[$this->index] = array('id' => $questionInfo['id'], 'question' => $questionInfo['question'], 'question_type' => $questionInfo['question_type']);
            $this->index++;
        }

        Database::freeResultSet($outputData);
       */
        $isRepeating = false;

        while ($this->questionCount > 0) {

            $questionID = mt_rand(1, $this->tableIdCount);
            $this->setQuestionsQuery($questionID);

            if (empty($this->questionsArray)) {
                $this->setQuestionsArray($questionID);
            } else {

                foreach ($this->questionsArray as $value) {
                    if ($questionID == $value['id']) {
                        $isRepeating = true;
                        break;
                    }
                }

                if ($isRepeating) {
                    $isRepeating = false;
                    continue;
                }

                $this->setQuestionsArray($questionID);

                $this->updateQuestionCount();

            }
        }

        $this->index = 0;
        //shuffle($this->questionsArray);

    }

    public function sortQuestionsArray() {

    }

    public function initQuestions() {
        switch ($this->examQuestionsType) {
            case "Отворени":
                $this->setQuestions(1, "Отворен");
                break;
            case "Затворени":
                $this->setQuestions(1, "Затворен");
                break;
            case "Задачи(Команди)":
                $this->setQuestions(1, "Задача(Команди)");
                break;
            case "Задачи(Скрипт)":
                $this->setQuestions(1, "Задача(Скрипт)");
                break;
            case "Отворени и Затворени":
                $this->setQuestions(2, "Отворен", "Затворен");
                break;
            case "Отворени и Задачи(Команди)":
                $this->setQuestions(2, "Отворен", "Задача(Команди)");
                break;
            case "Отворени и Задачи(Скрипт)":
                $this->setQuestions(2, "Отворен", "Задача(Скрипт)");
                break;
            case "Затворени и Задачи(Команди)":
                $this->setQuestions(2, "Затворен", "Задача(Команди)");
                break;
            case "Затворени и Задачи(Скрипт)":
                $this->setQuestions(2, "Затворен", "Задача(Скрипт)");
                break;
            case "Отворени, Затворени и Задачи(Команди)":
                $this->setQuestions(3, "Отворен", "Затворен", "Задача(Команди)");
                break;
            case "Отворени, Затворени и Задачи(Скрипт)":
                $this->setQuestions(3, "Отворен", "Затворен", "Задача(Скрипт)");
                break;
            case "Отворени, Затворени, Задачи(Команди) и Задачи(Скрипт)":
                $this->setQuestions(4, "Отворен", "Затворен", "Задача(Команди)", "Задача(Скрипт)"); //no need of all arguments if args number is 4
                break;
            default: echo 'Internal Error Q1: Something went wrong while setting the questions for the exam.';
                break;
        }
    }

    public function submitAnswers() { // to be refactored
        foreach ($this->questionsArray as $value){
            $this->answersArray[$this->index] = array('id' => $value['id'], 'answer' => trim(filter_input(INPUT_POST, 'answer'.$value['id'].'')));
            $this->index++;
        }
        $this->index = 0;
        foreach ($this->answersArray as $value) { //to be continued
            $sqlQuery = 'INSERT INTO exam_results (user_id, exam_id, question_id, student_answer) VALUES ('.$this->userID.', '.$this->examID.', '.$value['id'].', "'.$value['answer'].'");';
            Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        }
        $this->examEnded = mktime();
        //free result set
    }

    public function check($userAnswer, $databaseAnswer, $isCorrect, $id, $points) {
        if ($userAnswer === $databaseAnswer && $isCorrect === "Да") {
            Database::runQuery(Database::getDatabaseConnection(), 'UPDATE exam_results SET points = '.$points.' WHERE user_id = '.$this->userID.' AND exam_id = '.$this->examID.' AND question_id = '.$id.';');
        } elseif ($isCorrect === "Недефиниран") {
            Database::runQuery(Database::getDatabaseConnection(), 'UPDATE exam_results SET points = "'.$points.'" WHERE user_id = '.$this->userID.' AND exam_id = '.$this->examID.' AND question_id = '.$id.';');
        } else {

        }
    }

    public function checkAnswers() {
        $answerID = 0;
        foreach ($this->answersArray as $value) {
            foreach ($this->multipleAnswerQuestions as $value2) {
                if ($value['id'] == $value2) {
                    $answerID = $value2;
                    break;
                }
            }
            $sqlQuery = 'SELECT answer, is_correct, points FROM '.$this->answersTable.' WHERE question_id = '.$value['id'].';';
            $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
            //$answersInfo = Database::fetchAssoc($outputData);
            if ($answerID == $value['id']) {
                while ($answersInfo = Database::fetchAssoc($outputData)) {
                    $this->check($value['answer'], $answersInfo['answer'], $answersInfo['is_correct'], $value['id'], $answersInfo['points']);
                }
            } else {
                $answersInfo = Database::fetchAssoc($outputData);
                $this->check($value['answer'], $answersInfo['answer'], $answersInfo['is_correct'], $value['id'], $answersInfo['points']);
            }

        }
        Database::freeResultSet($outputData);
    }

    public function calculatePoints() {
        for ($this->index = 0; $this->index < count($this->answersArray); $this->index++) {
            $sqlQuery = 'SELECT points FROM exam_results WHERE user_id = '.$this->userID.' AND exam_id = '.$this->examID.' AND question_id = '.$this->answersArray[$this->index]['id'].';';
            $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
            $answersInfo = Database::fetchAssoc($outputData); //$answersInfo = mysqli_fetch_assoc($outputData);

            $this->points += $answersInfo['points'];

        }
        $this->index = 0;
        $ipAddress = System::getUserIP();
        $userAgent = System::getUserAgent();
        $system = System::getUserPlatform($userAgent);
        $browser = System::getUserBrowser($userAgent);
        Database::freeResultSet($outputData);
        Database::runQuery(Database::getDatabaseConnection(), 'INSERT INTO final_results (user_id, exam_id, started, ended, system, browser, ip, temporary_points) VALUES ('.$this->userID.', '.$this->examID.', '.$this->examStarted.', '.$this->examEnded.', "'.$system.'", "'.$browser.'", "'.$ipAddress.'", '.  $this->points.');');
        //free result set
    }

    public function submitScore($score) {
        $sqlQuery = 'UPDATE final_results SET temporary_score = '.$score.' WHERE user_id = '.$this->userID.' AND exam_id = '.$this->examID.';';
        Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        //free result set
    }

    public function setScore() { #must be different for different exam
        $sqlQuery = 'SELECT temporary_points FROM final_results WHERE user_id = '.$this->userID.' AND exam_id = '.$this->examID.';';
        $outputData = Database::runQuery(Database::getDatabaseConnection(), $sqlQuery);
        $pointsInfo = Database::fetchAssoc($outputData); //$pointsInfo = mysqli_fetch_assoc($outputData);
        if ($pointsInfo['temporary_points'] <= 7) {
            $this->submitScore(2);
        } elseif ($pointsInfo['temporary_points'] > 7 && $pointsInfo['temporary_points'] <= 9) {
            $this->submitScore(3);
        } elseif ($pointsInfo['temporary_points'] > 9 && $pointsInfo['temporary_points'] <= 11) {
            $this->submitScore(4);
        } elseif ($pointsInfo['temporary_points'] > 11 && $pointsInfo['temporary_points'] <= 13) {
            $this->submitScore(5);
        } elseif ($pointsInfo['temporary_points'] > 13 && $pointsInfo['temporary_points'] <= 15) {
            $this->submitScore(6);
        } else;
        Database::freeResultSet($outputData);
    }

    public function getEvaluationCriteria($examName) {

    }

}

