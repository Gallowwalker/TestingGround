<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true){
$user_id= $_SESSION['user_info']['id'];
db_init();
date_default_timezone_set('Europe/Sofia');
$q="SELECT *
    FROM messages
    WHERE `id`='$_GET[id]' AND `receiver_id`='$user_id'";
$r=run_mysql_query($q) or die (mysql_error());
$userData=mysql_fetch_array($r);
if (mysql_num_rows($r)==0){
    echo "<div style='background-color:#ff8888' align='center'>Няма съобщения.</div>";
} else {
$sender_id=$userData['sender_id'];
$title=$userData['title'];
$message=$userData['message'];
$url=$userData['url'];
$time=$userData['time'];
$date=date('d-m-Y в G:i ч.', $time);
$readed=$userData['readed'];
$q_user="SELECT name, surname
        FROM users
        WHERE id='$sender_id'";
$r_user=run_mysql_query($q_user) or die (mysql_error());
$userData=mysql_fetch_array($r_user);
/*$sender_name=$row['username'];  $q_user="SELECT username
        FROM users
        WHERE id='$sender_id'";*/
$name=$userData['name'];
$surname=$userData['surname'];
if ($readed==0){
$q_readed="UPDATE messages 
        SET `readed` = '1' 
        WHERE `id` =$_GET[id]";
$r_readed=run_mysql_query($q_readed) or die (mysql_error());
}
echo '<article id="main_content_body"><header><hgroup><div id="main_content_title" >Message</div></hgroup></header><span class="display_color">From:</span> ';
echo ''.$name.' '.$surname.'';  /*$sender_name;*/
echo '<br /><br /><span class="display_color">Subject:</span> ';
echo $title;
echo '<br /><br /><span class="display_color">Sent on:</span> ';
echo $date;
echo '<br /><br /><span class="display_color">Content:</span> <br /><br /><p>';
echo $message;
echo '<br /><br /><a class="underline" target="_blank" href="'.$url.'">'.$url.'</a>';
echo '</p><br /><footer><a class="underline" href="inbox.php">Back</a></footer></article>';
}
}else{
	header('Location: index.php');
	exit;
	}
page_footer();
?>
