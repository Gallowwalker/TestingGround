<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true){
$user_id= $_SESSION['user_info']['id'];
db_init();
date_default_timezone_set('Europe/Sofia');
if (isset($_POST['submit'])){
if(!empty($_POST['title']) && !empty($_POST['receiver']) && !empty($_POST['message'])){
        $receiver=htmlspecialchars(mysql_real_escape_string($_POST['receiver']));
        $title=htmlspecialchars(mysql_real_escape_string($_POST['title']));
        $message=htmlspecialchars(mysql_real_escape_string($_POST['message']));
		$url=htmlspecialchars(mysql_real_escape_string($_POST['url']));
            $q="SELECT id
                FROM users
                WHERE username='$receiver'";
            $r=run_mysql_query($q)or die (mysql_error());
            if (mysql_num_rows($r)==1){
            $userData=mysql_fetch_array($r);
            $receiver_id=$userData['id'];
            $time=time();
            $sql = "INSERT INTO `messages` 
            (
            `id`, 
            `sender_id`, 
            `receiver_id`, 
            `title`, 
            `message`,
			`url`,
            `time`, 
            `readed`)
            VALUES 
            ('', '$user_id', '$receiver_id', '$title', '$message', '$url', '$time', '0');";
            $sql_result=run_mysql_query($sql)or die (mysql_error());
            echo '<article id="main_content_body">
	<header>
		<hgroup>
			
		</hgroup>
	</header><br />
	<p><img src="img/success.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Съобщението е изпратено.&nbsp;&nbsp;</span></P>
	<footer>
		<br /><a class="underline" href="send_message.php">Назад</a>
	</footer>
</article>';
            } else {
                echo '<article id="main_content_body">
	<header>
		<hgroup>
			
		</hgroup>
	</header><br />
	<p><img src="img/fail.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Не съществува такъв потребител.</span></P>
	<footer>
		<br /><a class="underline" href="send_message.php">Назад</a>
	</footer>
</article>';
            }
        } else {
            echo '<article id="main_content_body">
	<header>
		<hgroup>
			
		</hgroup>
	</header><br />
	<p><img src="img/fail.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Трябва да попълниш всички полета.</span></P>
	<footer>
		<br /><a class="underline" href="send_message.php">Назад</a>
	</footer>
</article>';
    }
}else{
?>
<article id="main_content_body">
	<header>
		<hgroup>
			<div id="main_content_title">Sent Message</div>
		</hgroup>
	</header><br />
	<p>
<form action="send_message.php" method="post">
*To:<br /><input type="text" name="receiver" size="30" maxlength="100"
<?php
if (isset($_GET['user'])){
$user=htmlspecialchars(mysql_real_escape_string($_GET['user']));
echo "value='";
echo $user . "'";
}
?>>
<br />
*Subject:<br /><input type="text" name="title" size="30" maxlength="100" /><br />
*Content:<br /><textarea rows="5" cols="70" name="message">
</textarea><br /><br />
Link:<br /><input type="text" name="url" size="50" maxlength="255" /><br /><br />
<input type="submit" name="submit" value="Sent">
</form>
</P>
	<footer>
		<br /><a class="underline" href="inbox.php">Back</a>
	</footer>
</article>
<?php
}
}else{
	header('Location: index.php');
	exit;
	}
page_footer();
?>