<?php
session_start();
include 'system.php';
page_header();
db_init();
date_default_timezone_set('Europe/Sofia');
$sql = 'SELECT id, username, name, surname, email, gender, secret_question, secret_answer, profile_picture, type, active, join_date, last_login, last_ip, status FROM users';
$retval = run_mysql_query( $sql );
?>
<article id="main_content_body">
    <header>
        <div id="main_content_title">Admin Staff</div>
    </header>
<?php
echo ' <table class="list">
<tr>
		<!--<th><strong class="display_color">ID</strong></th>-->
                <th><strong class="display_color">profile_picture</strong></th>
		<th><strong class="display_color">Username</strong></th>
		<th><strong class="display_color">Name</strong></th>
		<th><strong class="display_color">Surname</strong></th>
		<th><strong class="display_color">Email</strong></th>
		<th><strong class="display_color">Gender</strong></th>
		<!--<th><strong class="display_color">Secret question</strong></th>-->
		<!--<th><strong class="display_color">Secret answer</strong></th>-->
                
		<th><strong class="display_color">Type</strong></th>
		<th><strong class="display_color">Active</strong></th>
		<th><strong class="display_color">Date registered</strong></th>
		<th><strong class="display_color">Last login</strong></th>
		<!--<th><strong class="display_color">Last ip</strong></th>-->
                <th><strong class="display_color">Profile Status</strong></th>
	</tr> ';

while($userData = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    if($userData['status']==="offline"){
        $errorArray['status']='<img src="img/system/offline.png" />'; 
    }else{
        $errorArray['status']='<img src="img/system/online.png" />';
    }
    
    if($userData['type']==="Администратор" || $userData['type']==="Модератор"){echo'<tr>
		<!--<td>'.$userData['id']. '</td>-->
                    <td><img src="' .$userData['profile_picture']. '" width="64" height="64" alt="" /></td>
		<td>' .$userData['username']. '</td>
		<td>' .$userData['name']. '</td>
		<td>' .$userData['surname']. '</td>
		<!--<td>' .$userData['email']. '</td>-->
                    <td>*************</td>
		<td>' .$userData['gender']. '</td>
		<!--<td>' .$userData['secret_question']. '</td>-->
		<!--<td>' .$userData['secret_answer']. '</td>-->
                    
		<td>' .$userData['type']. '</td>
		<td>' .$userData['active']. '</td>
		<td>' .date('d-m-Y | G:i', $userData['join_date']). '</td>
		<td>' .date('d-m-Y | G:i', $userData['last_login']). '</td>
		<!--<td>' .$userData['last_ip']. '</td>-->
                <td>' .$errorArray['status']. '</td>
                
	</tr>'; }
    
}
echo '
	</table></div>
	';
?>

    <footer>
        
    </footer>
</article>
<?php
page_footer();