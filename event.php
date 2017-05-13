<?php
final class Event {
    
    public static function getLoginError() {
        
        ?>
<div class="panel panel-danger">
    <div class="panel-heading">
    <h3 class="panel-title">Грешка</h3>
  </div>
  <div class="panel-body">
      <p><img class="valign" src="img/fail.png" width="24" heigh="24" />&nbsp;Ти вече си влязъл в системата.</p>
  </div>
</div>
<?php
    }
    
    public static function getRegisterError() {
        ?>
<div class="panel panel-danger">
    <div class="panel-heading">
    <h3 class="panel-title">Грешка</h3>
  </div>
  <div class="panel-body">
      <p><img class="valign" src="img/fail.png" width="24" heigh="24" />&nbsp;Първо трябва да излезеш от системата.</p>
  </div>
</div>
<?php
    }
    
    public static function getAccountCreationSuccess() {
        
    }
    
    public static function getExamAnswersSubmitSuccess() {
        ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Статус</h3>
  </div>
  <div class="panel-body">
      <p><img class="valign" src="img/success.png" width="64" heigh="64" />&nbsp;<span class="valign text">Отговорите ти бяха предадени успешно.
        <a href="exams.php">Назад</a></span></p>
  </div>
</div>

<?php
    }
    
    public static function getLibraryLoginError() {
         ?>
        <div class="panel panel-danger">
    <div class="panel-heading">
    <h3 class="panel-title">Грешка</h3>
  </div>
  <div class="panel-body">
      <p><img class="valign" src="img/fail.png" width="24" heigh="24" />&nbsp;За да използваш библиотеката трябва да си регистриран потребител и да си влязъл в системата.</p>
  </div>
</div>
<?php

    }
    
    public static function getExamsLoginError() {
        ?>
        <div class="panel panel-danger">
    <div class="panel-heading">
    <h3 class="panel-title">Грешка</h3>
  </div>
  <div class="panel-body">
      <p><img class="valign" src="img/fail.png" width="24" heigh="24" />&nbsp;За да решиш тест трябва да си регистриран потребител и да си влязъл в системата.</p>
  </div>
</div>
<?php
    }
    
}