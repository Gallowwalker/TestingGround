<?php
include './system.php';
include './database.php';
include './event.php';
include './modules/exam.php';
System::sessionStart();
if (filter_input(INPUT_GET, 'examName')) {
    $examName = filter_input(INPUT_GET, 'examName');
    $_SESSION['names'] = $examName;
} else;

if (isset($_SESSION['names'])) {
    $title = $_SESSION['names'];
} else {
    $title = 'Грешка';
}
    
System::setContentTitle($title);
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) === true) {

Database::setDatabaseConnection();

if (filter_input(INPUT_POST, 'formSubmit') == 1) {
    
    $object = $_SESSION['objects'];
    $object->submitAnswers();
    $object->checkAnswers();
    $object->calculatePoints();
    $object->setScore();
    
    Event::getExamAnswersSubmitSuccess();
    
} else {
    
    $object = new Exam();
    $_SESSION['objects'] = $object;

    $userID = $_SESSION['userInfo']['id'];
    $object->setUserID($userID);
/*
    if (filter_input(INPUT_GET, 'examName')) {
        $examName = filter_input(INPUT_GET, 'examName');
        $_SESSION['names'] = $examName;
    } else;*/
    $object->setExamName($_SESSION['names']); //$examName;
    $object->setExamQuery();
    $object->setExamInfo();
    
    //$object->initQuestions();
    
    $examTime = 3600;

    if ($object->verifyPassword()) {
        
        //$object->initQuestions();
        $object->setQuestions();
        $object->getQuestions();
    }
}
    //
?>

<script type="text/javascript">
var hours;
var minutes;
var sec = 60;
function Countdown(options) {
  var timer,
  instance = this,
  seconds = options.seconds || 10,
  updateStatus = options.onUpdateStatus || function () {},
  counterEnd = options.onCounterEnd || function () {};

  function decrementCounter() {
    updateStatus(seconds);
    if (seconds === 0) {
      counterEnd();
      instance.stop();
    }
    if (sec == 0) {
        sec = 60;
    }
    sec--;
    //localStorage.setItem("someVarName", seconds);
    seconds = localStorage.getItem("someVarName");
    seconds--;
    localStorage.setItem("someVarName", seconds);
  }

  this.start = function () {
    clearInterval(timer);
    timer = 0;
    seconds = options.seconds;
    timer = setInterval(decrementCounter, 1000);
  };

  this.stop = function () {
    clearInterval(timer);
  };
}
    
var myCounter = new Countdown({  
    seconds:localStorage.getItem("someVarName"),  // number of seconds to count down
    onUpdateStatus: function(seconds){
        hours = parseInt(seconds / 3600);
        minutes = parseInt(seconds / 60);
        //sec = parseInt(sec);
        
        $('.message').html("<strong>" + hours + " : " + minutes + " : " + sec + "</strong>" + localStorage.getItem("someVarName"));
    }, // callback for each second - console.log(sec);
    onCounterEnd: function(){ /*$('#questions').submit();*/} // final action - alert('counter ended!');
    
});

myCounter.start();
</script>
<?php

} else {
    Event::getExamsLoginError();
}
System::getPageFooter();
