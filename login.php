<?php 
include './system.php';
include './database.php';
include './event.php';
System::sessionStart();
System::setContentTitle("Вход в системата");
System::getPageHeader();
System::setTimeZone("Europe/Sofia");

if (isset($_SESSION['isLogged']) !== true) {
    
    $username = "";
    $password = "";
    $errorArray = array();
    
    if (isset($_SERVER['HTTP_REFERER'])) {
        $redirect = $_SERVER['HTTP_REFERER'];
    } else {
        $redirect = 'index.php';
    }
    
    if (filter_input(INPUT_POST, 'formSubmit') == 1) {
        
        $username = trim(filter_input(INPUT_POST, 'username'));
	$password = trim(filter_input(INPUT_POST, 'password'));
        $redirect = filter_input(INPUT_POST, 'redirect');
        
        $success = '<br />&nbsp;<img class="valign" src="img/system/status/success.png" width="28" height="28" />&nbsp;<span class="text text-success">';
        $fail = '<br />&nbsp;<img class="valign" src="img/system/status/fail.png" width="28" height="28" />&nbsp;<span class="text text-danger">';
        $end = '</span><br />';
        
	if (strlen($username) < 10) {
            $errorArray['username'] = ''.$fail.'Твърде кратко потребителско име. Трябва да бъде поне 10 символа.'.$end.'';
	} elseif (strlen($username) > 18) {
            $errorArray['username'] = ''.$fail.'Твърде дълго потребителско име. Максималната дължина е 18 символа.'.$end.'';
        } else {
            #$errorArray['username'] = '<br />&nbsp;'.$fail.'&nbsp;Нещо не е наред с потребителското ти име.<br />';
        }
        
	if (strlen($password) < 6) {
            $errorArray['password'] = ''.$fail.'Твърде кратка парола. Трябва да бъде поне 6 символа.'.$end.'';
	} elseif (strlen($password) > 18) {
            $errorArray['password'] = ''.$fail.'Твърде дълга парола. Максималната дължина е 18 символа.'.$end.'';
        } else {
            #$errorArray['password'] = '<br />&nbsp;'.$fail.'&nbsp;Нещо не е наред с паролата ти.<br />';
        }
        
	if (strlen($username) > 9 && strlen($password) > 5) {
            if (strlen($username) <= 18 && strlen($password) <= 18) {
                
                Database::setDatabaseConnection(); //must remove * - sql injection
                //$userDataQuery = 'SELECT * FROM users WHERE username = "'.addslashes($username).'" AND password_hash = "'.sha1($password).'";';
                $userDataQuery = 'SELECT * FROM users WHERE username = "'.addslashes($username).'";';
                $outputData = Database::runQuery(Database::getDatabaseConnection(), $userDataQuery);

                if (Database::getRowNumber($outputData) == 1) {

                    //$userData = mysqli_fetch_assoc($outputData); // free the resultset
                    $userData = Database::fetchAssoc($outputData);
                    
                    if (password_verify($password, $userData['password'])) {
                       
                        if ($userData['active'] === "Да") {

                            $_SESSION['isLogged'] = true;
                            $_SESSION['userInfo'] = $userData;
                            $date = mktime();
                            $ipAddress = System::getUserIP();

                            $resultSet = 'UPDATE users SET last_login = "'.$date.'", last_ip = "'.$ipAddress.'", status = "online" WHERE username = "'.$username.'";';
                            Database::runQuery(Database::getDatabaseConnection(), $resultSet);
                            Database::freeResultSet($outputData);
                            Database::closeDatabaseConnection();
                            header('Location: '.$redirect);
                            //echo $_SERVER['HTTP_REFERER'];
                            exit;

                        } else {
                            $errorArray['resultSet'] = ''.$fail.'Поради някаква причина достъпът ти до системата е забранен. Моля свържи се с администратора.'.$end.'';
                        }
                        
                    } else {
                        $errorArray['other'] = ''.$fail.'Нещо не е наред с паролата ти.'.$end.'';
                    }
                    
                } elseif (Database::getRowNumber($outputData) == 0) {
                    //$attemptsLeft--;
                    $errorArray['resultSet'] = ''.$fail.'Грешно потребителско име и/или парола.'.$end.''; 
                } else;
                
            }
	} else;
        
    }
?>
<br />
<form action="login.php" method="POST" class="form-inline" autocomplete="off">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-user"></div></div>
        <input type="text"  name="username" placeholder="Потребителско име" class="form-control" value="<?php echo $username; ?>" required />
        </div></div>
    <br />
    <?php
    if (isset($errorArray['username'])) {
        echo $errorArray['username'];
    }
    ?>
    <br />	
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></div>
        <input type="password" name="password" placeholder="Парола" class="form-control" value="<?php echo $password; ?>" required />
        </div></div>
    <br />
    <?php
    if (isset($errorArray['password'])) {
        echo $errorArray['password'];
    }
    if (isset($errorArray['other'])) {
        echo $errorArray['other'];
    }
    ?>
    <br />
    <div class="form-group">
        <input type="hidden" name="formSubmit" value="1" />
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Вход</button><br /><br />
        <p>Все още нямаш регистрация? Направи я <a href="register.php">тук</a>.</p>
    </div>
</form>
<?php
/*
if (isset($errorArray['username'])) {
    echo $errorArray['username'];
}

if (isset($errorArray['password'])) {
    echo $errorArray['password'];
}
*/
if (isset($errorArray['resultSet'])) {
    echo $errorArray['resultSet'];
}
?>
<br />            
<?php
} else {
    Event::getLoginError();
}
System::getPageFooter();