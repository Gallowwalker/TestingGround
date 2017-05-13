<?php
#session_start();   str_ireplace(); - case insensitive 
include 'system.php';
System::getPageHeader();
if (isset($_SESSION['is_logged']) === true) {
System::db_init();
$questionsDataQuery = System::run_mysql_query('SELECT id, question, question_type FROM os_exam_questions WHERE question_type = "Отворен" AND exam_type = "Контролна";');
$examsDataQuery = System::run_mysql_query('SELECT id, time, question_type, question_count FROM exams WHERE exam_name = "Операционни Системи - Тест 1"');
?>
<article id="main_content_body">
    <header>
        <div id="main_content_title">Контролна - ОС</div>
    </header>
<?php
if (isset($_POST['form_submit']) == 1) {
    foreach ($questionsArray as $value){
        $answersArray[$index] = array('id' => $value['id'], 'answer' => $_POST['answer'.$value['id']]);
        $index++;
    }
    foreach ($answersArray as $value) { 
	System::run_mysql_query('INSERT INTO students_exam_results (user_id, exam_id, question_id, student_answer) VALUES ("'.$userID.'", "'.$examID.'", "'.$value['id'].'", "'.$value['answer'].'");');
    } 
} else {
    $userID = $_SESSION['user_info']['id']; 
    $examInfo = mysql_fetch_assoc($examsDataQuery);
    $examID = $examInfo['id'];
    $questionCount = $examInfo['question_count'];
    $examTime = $examInfo['time'];

    $index = 0;
    $questionsArray = null;
    $answersArray = null;


    //if ($output2['question_type'] === "Отворени") { 

    while($questionInfo = mysql_fetch_assoc($questionsDataQuery)){
        if ($questionInfo['question_type'] === "Отворен") {
            //if ($index < $questionCount) {
                $questionsArray[$index] = array('id' => $questionInfo['id'], 'question' => $questionInfo['question']);
                $index++;
            //} else;
        }	
    }

    $index = 0;

    shuffle($questionsArray);




?>

<div class="clock" style="margin:20px 120px; border: 1px solid black; width: 460px;"></div>
<div class="message"></div>
<form action="started-exam.php" method="POST">
    <table class="custom-table" border="0">
        <tbody>
<?php

    foreach ($questionsArray as $value) {
        echo '<tr>'
               . '<td class="left">'.$value['question'].' - <input name="answer'.$value['id'].'" type="text" /></td>'
           . '</tr>';
    }
}
?>
            <tr>
                <td><input type="hidden" name="form_submit" value="1" /></td>
            </tr>
            <tr>
                <td><input type="submit" id="sub" name="submit" value="Завърши теста" /></td>
            </tr>
        </tbody>
    </table>
</form>


<footer>
                        
    </footer>
</article>
<script type="text/javascript">
			var time = <?php echo json_encode($examTime); ?>;
			var clock;
			
			$(document).ready(function() {
			
				clock = $('.clock').FlipClock(2, {
					clockFace: 'HourlyCounter', countdown: true, callbacks: {
						stop: function() {
							$('.message').html('Game Over!');
                                                        $('.message').html(clock.getTime().time);
						}
					}

				});
                                
                                

			});
		</script>
<?php
} else {

	echo'
	<center>
        <div id="main_content_body">
		<div id="main_content_title">Грешка</div>
		<p><img src="img/fail.png" width="24" heigh="24" />&nbsp;<span style="bottom:7px; position:relative;">За да използваш библиотеката трябва да си регистриран потребител и да си влязъл в системата.</span></p>
        </div>
	</center>';
}
System::getPageFooter();
