<?php
include './system.php';
include './database.php';
include './event.php'; // must check for error and catch them
System::sessionStart();
System::setContentTitle("Създаване на профил");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) !== true) {
    
    $errorArray = array();
    $successArray = array();
    $username = "";
    $password = "";
    $password2 = "";
    $name = "";
    $surname = "";
    $email = "";
    $gender = "";
    $secretQuestion = "";
    $secretAnswer = "";
    $registerCode = "";
    $termsOfUse = "";
    
    $isMale = ""; #maybe move V
    $isFemale = "";
    $isOther = "";
    
    $isChecked = "";
    
    Database::setDatabaseConnection(); #get only one not every time
    $systemDataQuery = 'SELECT register_code FROM system_data;';
    $outputData = Database::runQuery(Database::getDatabaseConnection(), $systemDataQuery);
    //$systemData = mysqli_fetch_assoc($outputData);
    $systemData = Database::fetchAssoc($outputData);
    Database::freeResultSet($outputData);
    Database::closeDatabaseConnection();
    
    if (filter_input(INPUT_POST, 'formSubmit') == 1) {
		
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        $password2 = trim(filter_input(INPUT_POST, 'password2'));
        $name = trim(filter_input(INPUT_POST, 'name'));
        $surname = trim(filter_input(INPUT_POST, 'surname'));
        $email = trim(filter_input(INPUT_POST, 'email'));
        $gender = filter_input(INPUT_POST, 'gender');
        $secretQuestion = filter_input(INPUT_POST, 'secretQuestion');
        $secretAnswer = trim(filter_input(INPUT_POST, 'secretAnswer'));
        $registerCode = trim(filter_input(INPUT_POST, 'registerCode'));
        $termsOfUse = filter_input(INPUT_POST, 'termsOfUse');
        
        $success = '<br />&nbsp;<img class="valign" src="img/system/status/success.png" width="28" height="28" />&nbsp;<span class="text">';
        $fail = '<br />&nbsp;<img class="valign" src="img/system/status/fail.png" width="28" height="28" />&nbsp;<span class="text">';
        $end = '</span><br />';
	
	if (strlen($username) < 10) {
            $errorArray['username'] = ''.$fail.'Твърде кратко потребителско име. Трябва да бъде поне 10 символа.'.$end.'';
	} elseif (strlen($username) > 18) {
            $errorArray['username'] = ''.$fail.'Твърде дълго потребителско име. Максималната дължина е 18 символа.'.$end.'';
        } else {
            $successArray['username'] = ''.$success.'Ок.'.$end.'';
            #$errorArray['username'] = '<br />&nbsp;'.$fail.'&nbsp;Нещо не е наред с потребителското ти име.<br />';
        }
        
	if (strlen($password) < 6) {
            $errorArray['password'] = ''.$fail.'Твърде кратка парола. Трябва да бъде поне 6 символа.'.$end.'';
	} elseif (strlen($password) > 18) {
            $errorArray['password'] = ''.$fail.'Твърде дълга парола. Максималната дължина е 18 символа.'.$end.'';
        } else {
            $successArray['password'] = ''.$success.'Ок.'.$end.'';
            #$errorArray['password'] = '<br />&nbsp;'.$fail.'&nbsp;Нещо не е наред с паролата ти.<br />';
        }
        
	if ($password !== $password2) {
            $errorArray['password2'] = ''.$fail.'Паролите не съвпадат.'.$end.'';
	} else {
            $successArray['password2'] = ''.$success.'Ок.'.$end.'';
        }
        
	if (strlen($name) < 2) {
            $errorArray['name'] = ''.$fail.'Твърде кратко име. Трябва да бъде поне 2 символа.'.$end.'';
	} else {
            $successArray['name'] = ''.$success.'Ок.'.$end.'';
        }
        
	if (strlen($surname) < 2) {
            $errorArray['surname'] = ''.$fail.'Твърде кратка фамилия. Трябва да бъде поне 2 символа.'.$end.'';
	} else {
            $successArray['surname'] = ''.$success.'Ок.'.$end.'';
        }
        
	if (!preg_match("/(^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$)/i", $email)) {
            $errorArray['email'] = ''.$fail.'Невалидна електронна поща.'.$end.'';
	} else {
            $successArray['email'] = ''.$success.'Ок.'.$end.'';
        }
        
	if (!strlen($gender) > 0) {
            $errorArray['gender'] = ''.$fail.'Трябва да избереш пол.'.$end.'';
	} else {
            if ($gender === "Мъж") {
                $isMale = "checked";
            } elseif ($gender === "Жена") {
                $isFemale = "checked";
            } elseif ($gender === "Друг пол") {
                $isOther = "checked";
            } else;
            $successArray['gender'] = ''.$success.'Ок.'.$end.'';
        }
        /*
	if (!strlen($secretQuestion) > 0) {
            $errorArray['secretQuestion'] = ''.$fail.'Трябва да избереш таен въпрос.'.$end.'';
	}  else;#may not be neccessery*/
        
        if ($secretQuestion === "Избери въпрос от списъка") {
            $errorArray['secretQuestion'] = ''.$fail.'Трябва да избереш таен въпрос.'.$end.'';
	} else {
            $successArray['secretQuestion'] = ''.$success.'Ок.'.$end.'';
        }
        
	if (!strlen($secretAnswer) > 0) {
            $errorArray['secretAnswer'] = ''.$fail.'Трябва да въведеш таен отговор.'.$end.'';
	} else {
            $successArray['secretAnswer'] = ''.$success.'Ок.'.$end.'';
        }
        
        if (!strlen($registerCode) > 0) {
            if (strlen($username) > 10) {
                $errorArray['username'] = ''.$fail.'Тъй като не си въвел специален код, не можеш да използваш друго потребителско име освен факултетен номер.'.$end.'';
            } elseif (strlen($username) == 10) {
                if (ctype_digit($username) === true) {
                    $successArray['registerCode'] = ''.$success.'Ок.'.$end.'';
                } else {
                    $errorArray['username'] = ''.$fail.'Невалидно потребителско име.'.$end.'';
                }
            } else;
        } else {
            if ($registerCode === $systemData['register_code']) {
                $successArray['registerCode'] = ''.$success.'Ок.'.$end.'';
            }
        }
        
         // canot register with terms of use - not sure
        if (!$termsOfUse === "readed") {
            $errorArray['termsOfUse'] = ''.$fail.'Трябва да приемеш условията и правилата на сайта.'.$end.'';
        } else {
            $isChecked = "checked";
            $successArray['termsOfUse'] = ''.$success.'Ок.'.$end.'';
        }
        
        /*
        if($username == "Admin" || $username == "Administrator" || $username == "Moderator"){
            $error_array['username']='<br />&nbsp;<img src="img/error.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative; color: violet;">You cant use that username.</span>';
        }
        */
        
	if (!isset($errorArray) || !count($errorArray) > 0) {
            
            Database::setDatabaseConnection();
            $userDataQuery = 'SELECT COUNT(*) AS count FROM users WHERE username = "'.addslashes($username).'" OR email = "'.addslashes($email).'";';
            $outputData = Database::runQuery(Database::getDatabaseConnection(), $userDataQuery);
            //$userData = mysqli_fetch_assoc($outputData); // free the resultset
            $userData = Database::fetchAssoc($outputData);
            
            if ($userData['count'] == 0) {
                
                $date = mktime();
                $ipAddress = System::getUserIP();
                
                $resultSet = 'INSERT INTO users (username, password, name, email, gender, secret_question, secret_answer, join_date, last_ip) VALUES ("'.addslashes($username).'", "'.password_hash($password, PASSWORD_BCRYPT).'", "'.addslashes($name." ".$surname).'", "'.addslashes($email).'", "'.addslashes($gender).'", "'.addslashes($secretQuestion).'", "'.addslashes($secretAnswer).'", '.$date.', "'.$ipAddress.'");';
                Database::runQuery(Database::getDatabaseConnection(), $resultSet);
                
                $resultSet2 = 'SELECT id FROM users WHERE username = "'.$username.'";';
                $infoDataQuery = Database::runQuery(Database::getDatabaseConnection(), $resultSet2);
                $outputData2 = Database::fetchAssoc($infoDataQuery);
                
                $resultSet3 = 'INSERT INTO user_info (user_id, specialty, faculty, course, profile_picture) VALUES ("'.$outputData2['id'].'", "Недефиниран", "Недефиниран", "Недефиниран", "img/profile-pictures/default/user.png");';
                Database::runQuery(Database::getDatabaseConnection(), $resultSet3);
                
                if (mysqli_connect_error()) {
                    trigger_error("Failed to conencto to MySQL Database: " . mysqli_connect_error(), E_USER_ERROR);
                    #$errorArray['sql'] = '&nbsp;'.$fail.'&nbsp;An error occurred. Please try again.';
                } else {
                    $successArray['sql'] = ''.$success.'Твоят профил беше създаден успешно!'.$end.'';
                    Database::freeResultSet($outputData);
                    Database::closeDatabaseConnection();
                }
                
            } else {
                $errorArray['username'] = ''.$fail.'Потребителското име и / или електронната поща са заети.'.$end.'';
                $errorArray['email'] = ''.$fail.'Потребителското име и / или електронната поща са заети.'.$end.'';
            }

	}
        
    }
?>
<br />
<form action="register.php" method="POST" class="form-inline" autocomplete="off">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-user"></div></div>
            <input type="text" name="username" placeholder="Потребителско име" class="form-control" value="<?php echo $username; ?>" required /></div>&nbsp;&nbsp;

<!--<a tabindex="0" class="btn btn-sm btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="And here's some amazing content. It's very engaging. Right?">popover</a>-->Използвай факултетен номер за потребителско име, ако си студент. 
        </div>
    <br />
    <?php
    if(isset($errorArray['username'])){
        echo $errorArray['username'];
    } elseif (isset($successArray['username'])) {
        echo $successArray['username'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></div>
            <input type="password" name="password" placeholder="Парола" class="form-control" value="<?php echo $password; ?>" required /></div>&nbsp;&nbsp;Трябва да бъде между 6 и 18 символа.
        </div>
    <br />
    <?php
    if(isset($errorArray['password'])){
        echo $errorArray['password'];
    } elseif (isset($successArray['password'])) {
        echo $successArray['password'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></div>
            <input type="password" name="password2" placeholder="Потвърди паролата" class="form-control" value="<?php echo $password2; ?>" required /></div>&nbsp;&nbsp;Въведи паролата отново, за да провериш дали съвпада.
    </div>
    <br />
    <?php
    if(isset($errorArray['password2'])){
        echo $errorArray['password2'];
    } elseif (isset($successArray['password2'])) {
        echo $successArray['password2'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
            <input type="text" name="name" placeholder="Име" class="form-control" spellcheck="true" value="<?php echo $name; ?>" required /></div>&nbsp;&nbsp;Твоето име. <span class="text-danger">(Моля пиши грамотно и на кирилица.)</span>
    </div>
    <br />
    <?php
    if(isset($errorArray['name'])){
        echo $errorArray['name'];
    } elseif (isset($successArray['name'])) {
        echo $successArray['name'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
            <input type="text" name="surname" placeholder="Фамилия" class="form-control" spellcheck="true" value="<?php echo $surname; ?>" required /></div>&nbsp;&nbsp;Твоята фамилия. <span class="text-danger">(Моля пиши грамотно и на кирилица.)</span>
    </div>
    <br />
    <?php
    if(isset($errorArray['surname'])){
        echo $errorArray['surname'];
    } elseif (isset($successArray['surname'])) {
        echo $successArray['surname'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-envelope"></div></div>
            <input type="text" name="email" placeholder="Ел. поща" class="form-control" value="<?php echo $email; ?>" required /></div>&nbsp;&nbsp;Ще бъде използвана само при промяна на информация по профила.
    </div>
    <br />
    <?php
    if(isset($errorArray['email'])){
        echo $errorArray['email'];
    } elseif (isset($successArray['email'])) {
        echo $successArray['email'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <label class="radio-inline"><input type="radio" name="gender" value="Мъж" <?php echo $isMale; ?> />Мъж</label>
            <label class="radio-inline"><input type="radio" name="gender" value="Жена" <?php echo $isFemale; ?> />Жена</label>
            <label class="radio-inline"><input type="radio" name="gender" value="Друг пол" <?php echo $isOther; ?> />Друг пол</label>
        </div>
    </div>
    <br />
    <?php
    if(isset($errorArray['gender'])){
        echo $errorArray['gender'];
    } elseif (isset($successArray['gender'])) {
        echo $successArray['gender'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-list"></div></div>
        <select name="secretQuestion" class="form-control">
            <option selected>Избери въпрос от списъка</option>
            <option>Кой е любимият ти супергерой?</option>
            <option>Коя е любимата ти видео игра?</option>
            <option>Кой е любимият ти филм?</option>
            <option>Коя е любимата ти песен?</option>
            <option>Кой е любимият ти певец?</option>
            <option>Коя е любимата ти певица?</option>
            <option>Кой е любимият ти актьор?</option>
            <option>Коя е любимата ти актриса?</option>
        </select></div>&nbsp;&nbsp;Използва се при промяна на информация по профила.
    </div>
    <br />
    <?php
    if(isset($errorArray['secretQuestion'])){
        echo $errorArray['secretQuestion'];
    } elseif (isset($successArray['secretQuestion'])) {
        echo $successArray['secretQuestion'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-pencil"></div></div>
            <input type="text" name="secretAnswer" placeholder="Отговор" class="form-control" spellcheck="true" value="<?php echo $secretAnswer; ?>" required /></div>&nbsp;&nbsp;Използва се при промяна на информация по профила. <span class="text-danger">(Моля пиши грамотно и на кирилица.)</span>
    </div>
    <br />
    <?php
    if(isset($errorArray['secretAnswer'])){
        echo $errorArray['secretAnswer'];
    } elseif (isset($successArray['secretAnswer'])) {
        echo $successArray['secretAnswer'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-barcode"></div></div>
            <input type="text" name="registerCode" placeholder="Специален код" class="form-control" value="<?php echo $registerCode; ?>" /></div>&nbsp;&nbsp;Предоставя се от администратор при регистрация на потребители, които не са студенти.
    </div>
    <br />
    <?php
    if(isset($errorArray['registerCode'])){
        echo $errorArray['registerCode'];
    } elseif (isset($successArray['registerCode'])) {
        echo $successArray['registerCode'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <label class="checkbox-inline">
            <input type="checkbox" name="termsOfUse" value="readed" <?php echo $isChecked; ?> required />Съгласен съм с <a href="terms-of-use.php" target="_blank">условията и правилата</a> на сайта.
        </label>
    </div>
    <br />
    <?php
    if(isset($errorArray['termsOfUse'])){
        echo $errorArray['termsOfUse'];
    } elseif (isset($successArray['termsOfUse'])) {
        echo $successArray['termsOfUse'];
    } else;
    ?>
    <br />
    <div class="form-group">
        <input type="hidden" name="formSubmit" value="1" />
        <button type="submit" name="submit" class="btn btn-primary">Създай профила</button>
    </div>
</form>
<?php
if(isset($successArray['sql'])){
    echo $successArray['sql'];
}
?>
<br /><br /> 
<?php
} else {
    Event::getRegisterError();
}
System::getPageFooter();