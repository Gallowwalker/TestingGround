<?php
#session_start();
include 'system.php';
System::getPageHeader();
$id = $_SESSION['user_info']['id'];
if((isset($_SESSION['is_logged']) === true) && ($_SESSION['user_info']['type'] === "Администратор" || $_SESSION['user_info']['type'] === "Модератор")){ 
$username = $_SESSION['user_info']['name'];

function clear($message)
{
	if (!get_magic_quotes_gpc()) {
		$message = addslashes($message);
		$message = strip_tags($message);
		//$message = htmlentities($message);
		$message = htmlspecialchars($message);
		$message = mysql_real_escape_string($message);
		return trim($message);
	}
}

if (isset($_POST['submit'])) { 
	if (empty($_POST['postedby'])) {
		die('<article id="main_content_body">
		<header>
			<hgroup>
				<div id="main_content_title">Error</div>
			</hgroup>
		</header><br />
		<img src="img/fail.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Не си въвел име.</span>
		<footer>
			<a class="underline" href="add.php">Назад</a>
		</footer>
	</article>'); 
	} else if (empty($_POST['subject'])) {
		die('<article id="main_content_body">
		<header>
			<hgroup>
				<div id="main_content_title">Error</div>
			</hgroup>
		</header><br />
		<img src="img/fail.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Не си въвел тема.</span>
		<footer>
			<a class="underline" href="add.php">Назад</a>
		</footer>
	</article>'); 
	} else if (empty($_POST['content'])) {
		die('<article id="main_content_body">
		<header>
			<hgroup>
				<div id="main_content_title">Error</div>
			</hgroup>
		</header><br />
		<p><img src="img/fail.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Не си въвел съдържание.</span></p>
		<footer>
			<a class="underline" href="add.php">Назад</a>
		</footer>
	</article>');
    } else;
	
	//mysql_real_escape_string()
	
	$postedby = clear($_POST['postedby']);     
	$subject = clear($_POST['subject']); 
	$content = clear($_POST['content']);
	$url = clear($_POST['url']);
	
	date_default_timezone_set('Europe/Sofia');	
	$date = mktime();
	
	System::db_init();
	
	if(System::run_mysql_query("INSERT INTO web_info (subject, content, posted_by, url, date) VALUES ('$subject', '$content', '$postedby', '$url', '$date')"))
	{
            //System::run_mysql_query("UPDATE web_info SET profile_picture = (SELECT profile_picture FROM users WHERE name = '$username') WHERE posted_by = '$username'");
		echo'
		<article id="main_content_body">
			<header>
				<hgroup>
					<div id="main_content_title">Success</div>
				</hgroup>
			</header><br />
			<p><img src="img/success.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Публикацията беше успешна.</span></p>
			<footer>
				<a class="underline" href="add_news.php">Назад</a>
			</footer>
		</article>';
	}

} else {
?>
<article id="main_content_body">
	<header>
		<hgroup>
                    <div id="main_content_title">Публикувай на началната страница</div>
		</hgroup>
	</header><br />
	<p>
<form method="post" action="add-post.php"> 
	Публикувано от:<br /><input name="postedby" id="postedby" type="Text" size="30" maxlength="100" value="<?php echo $username ?>" /><br /><br />
	Тема:<br /><input name="subject" id="subject" type="Text" size="30" maxlength="100" /><br /><br />
	Съдържание:<br /><textarea name="content" id="news" cols="50" rows="5"></textarea><br /><br />
	Линк:<br /><input name="url" id="url" type="Text" size="50" maxlength="255" /><br /><br />
	<input type="Submit" name="submit" id="submit" value="Публикувай" />
</form>
	</P><br />
	<footer>
		<a class="underline" href="admin.php">Назад</a>
	</footer>
</article>
<?php
	}
} else {
	header('Location: index.php');
	exit;
	}
System::getPageFooter();
?> 