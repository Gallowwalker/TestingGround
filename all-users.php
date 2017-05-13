<?php
session_start();
include 'system.php';
if(isset($_SESSION['is_logged'])===true && $_SESSION['user_info']['type']=="Администратор"){
System::db_init();
date_default_timezone_set('Europe/Sofia');
$sql = 'SELECT id, username, password, name, email, gender, secret_question, secret_answer, profile_picture, type, active, join_date, last_login, last_ip, status FROM users';
$retval = System::run_mysql_query( $sql );
if(! $retval)
{
  die('Could not get data: ' . mysql_error());
}

?>
<html>
    <head>
        <title>Users Information</title>
        
        <link href="css/admin-style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        
        
        <div id="right">
    
<?php
echo '<div id="main_content_body">
<div id="main_content_title">Users information</div>
<table class="admin_list admin_table">
<tr>
		<th>&nbsp;<strong class="display_color">ID</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Потребител</strong>&nbsp;</th>
                <th>&nbsp;<strong class="display_color">Парола</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Име</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Ел. Поща</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Пол</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Таен Въпрос</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Таен отговор</strong>&nbsp;</th>
                <th><strong class="display_color">Снимка</strong></th>
		<th>&nbsp;<strong class="display_color">Тип</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Активен</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Дата на регистрация</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">Последно влизане</strong>&nbsp;</th>
		<th>&nbsp;<strong class="display_color">IP Адрес</strong>&nbsp;</th>
                <th>&nbsp;<strong class="display_color">Статус</strong>&nbsp;</th>
	</tr>

';
while($userData = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    if($userData['status']==="offline"){$errorArray['status']='<img src="img/system/offline.png" />'; }else{$errorArray['status']='<img src="img/system/online.png" />';}
    echo '
	<tr>
		<td>&nbsp;'.$userData['id']. '&nbsp;</td>
		<td>&nbsp;' .$userData['username']. '&nbsp;</td>
                <td>&nbsp;' .$userData['password']. '&nbsp;</td>
		<td>&nbsp;' .$userData['name']. '&nbsp;</td>
		<td>&nbsp;' .$userData['email']. '&nbsp;</td>
		<td>&nbsp;' .$userData['gender']. '&nbsp;</td>
		<td>&nbsp;' .$userData['secret_question']. '&nbsp;</td>
		<td>&nbsp;' .$userData['secret_answer']. '&nbsp;</td>
                <td><img src="' .$userData['profile_picture']. '" width="48" height="48" /></td>
		<td>&nbsp;' .$userData['type']. '&nbsp;</td>
		<td>&nbsp;' .$userData['active']. '&nbsp;</td>
		<td>&nbsp;' .date('d-m-Y  H:i', $userData['join_date']). '&nbsp;</td>
		<td>&nbsp;' .date('d-m-Y  H:i', $userData['last_login']). '&nbsp;</td>
		<td>&nbsp;' .$userData['last_ip']. '&nbsp;</td>
                <td>&nbsp;' .$errorArray['status']. '&nbsp;</td>
	</tr>
	';
}
echo '</table></div>'; 
echo "<br /><img src='img/button_ok.png' width='16' heigh='16' />&nbsp;<span style='bottom:4px; position:relative;'>Data taken successfully!</span><br /><br /><a href='admin.php'>Back</a>";
mysql_close();
}else{
	/*header('Location: index.php');
	exit;*/
    
    echo'
	<center>
        <div id="main_content_body">
		<div id="main_content_title">Error</div>
		<p><img src="img/error.png" width="24" heigh="24" />&nbsp;<span style="bottom:7px; position:relative; color: violet;">You access to this page is denied.</span></p>
                </div>
	</center>';
}
?>
        </div>
</body>
</html>

