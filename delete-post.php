<?php
#session_start();
include 'system.php';
System::getPageHeader();
if((isset($_SESSION['is_logged']) === true) && ($_SESSION['user_info']['type'] === "Администратор" || $_SESSION['user_info']['type'] === "Модератор")){
System::db_init();
if(!isset($_GET['id']))
{
	$query = System::run_mysql_query('SELECT * FROM web_info ORDER BY id DESC');
	echo'<article id="main_content_body">
	<header>
        <div id="main_content_title">Изтрий публикация</div>
    </header>
		
	</header><br />
	<p>';
	while($output = mysql_fetch_assoc($query)){ 
		echo $output['subject'].' &raquo; <a href="#" onclick="check('.$output['id'].'); return false;">Изтрий</a><br />';
	}
	echo'</P>
	<footer>
		<a class="underline" href="delete-post.php">Назад</a>
	</footer>
</article>';
}
else
{
	$id = $_GET['id']; 
	System::run_mysql_query("DELETE FROM web_info WHERE id = $id LIMIT 1");
	echo '
	<article id="main_content_body">
		<header>
        <div id="main_content_title"></div>
    </header><br />
		<p><img src="img/success.png" width="24" height="24" />&nbsp;<span style="bottom:7px; position:relative;">Публикацията беше изтрита.</span></p>
		<footer>
			<a class="underline" href="delete.php">Назад</a>
		</footer>
	</article>';
}
?>
<script type="text/javascript">
function check(id){
	if (confirm("Сигурен ли си, че искаш да изтриеш публикацията?")) {
		this.location.href = "?id="+id;
	}
}</script>
<?php
}else{
	header('Location: index.php');
	exit;
	}
System::getPageFooter();
?>


