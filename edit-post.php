<?php
#session_start();
include 'system.php';
System::getPageHeader();
if((isset($_SESSION['is_logged']) === true) && ($_SESSION['user_info']['type'] === "Администратор" || $_SESSION['user_info']['type'] === "Модератор")){

function clear($message)
{
	if (!get_magic_quotes_gpc()) {
		$message = addslashes($message);
		$message = strip_tags($message);
		$message = htmlentities($message);
		//$message = htmlspecialchars($message);
		//$message = mysql_real_escape_string($message);
		return trim($message);
	}
}

System::db_init();

if(!isset($_GET['id']))
{
	$query = System::run_mysql_query("SELECT * FROM web_info ORDER BY id DESC");
	echo '<article id="main_content_body">
	<header>
        <div id="main_content_title">Редактирай публикация</div>
    </header><br />';
	while($output = mysql_fetch_assoc($query)) {
		echo '<p>'.$output['subject'].' &raquo; <a href="?id='.$output['id'].'">Редактирай</a></p>';
	}
		echo '
	<footer>
		<a class="underline" href="edit-post.php">Назад</a>
	</footer>
</article>';
}
else
{
	if (isset($_POST['submit']))
	{
		$postedby = clear($_POST['postedby']); 
		$subject = clear($_POST['subject']); 
		$content = clear($_POST['content']);
		$url = clear($_POST['url']);
		$date = mktime();
		$id = $_GET['id']; 
		System::run_mysql_query("UPDATE web_info SET subject='$subject', content='$content', posted_by='$postedby', url='$url', date='$date' WHERE id='$id'");

		echo '
		<article id="main_content_body">
			<header>
				<div id="main_content_title"></div>
			</header><br />
			<p><img src="img/success.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Редакцията беше успешна.</span></P>
			<footer>
				 <a class="underline" href="edit-post.php">Назад</a>
			</footer>
		</article>';
	}
	else
	{
		$id = $_GET['id']; 
		$query = System::run_mysql_query("SELECT * FROM web_info WHERE id='$id'");
		$output = mysql_fetch_assoc($query);
?>
<article id="main_content_body">
	<header>
		
			<div id="main_content_title">Редактирай публикация</div>
		
	</header><br />
	<p>
<form method="post" action="?id=<?php echo $output['id']; ?>"> 
Редакция на <?php echo $output['subject']; ?><br />
Публикувано от:<br /><input name="postedby" id="postedby" type="Text" size="50" maxlength="50" value="<?php echo $output['posted_by']; ?>"><br />
Тема:<br /><input name="subject" id="subject" type="Text" size="50" maxlength="50" value="<?php echo $output['subject']; ?>"><br />
Съдържание:<br /><textarea name="content" cols="50" rows="5"><?php echo $output['content']; ?></textarea><br /><br />
Линк:<br /><input name="url" id="url" type="Text" size="50" maxlength="255" value="<?php echo $output['url']; ?>"><br /><br />
<input type="submit" name="submit" value="Редактирай">
</form>
</P>
	<footer>
		<a class="underline" href="edit-post.php">Назад</a>
	</footer>
</article>
<?php
}
}
} else {
	header('Location: index.php');
	exit;
	}
System::getPageFooter();
?> 