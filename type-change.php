<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true && $_SESSION['user_info']['type']==3){
if(isset($_POST['submit'])){
db_init();
$id = (int)$_POST['id'];
$type = $_POST['type'];
$sql = "UPDATE users SET type = $type WHERE id = $id" ;
$retval = run_mysql_query( $sql );
if(! $retval )
{
  die('Could not delete data: ' . mysql_error());
}

echo "<article><br /><img src='img/button_ok.png' width='16' heigh='16' />&nbsp;<span style='bottom:4px; position:relative;'>Data deleted successfully!</span><br /><br /><a href='type_change.php'>Back</a></article>";

mysql_close();
}else{
?>
<article>
	<header>
		<hgroup>
			<h2 class="display_color">Change Type</h2>
		</hgroup>
	</header><br />
	<p>
<form method="post" action="<?php $_PHP_SELF ?>">
<input name="id" type="text" id="id" placeholder="User ID"><br /><br />
	<select name="type" id="type">
		<option selected value="3">Admin</option>
		<option selected value="2">Moderator</option>
		<option selected value="1">User</option>
	</select><br /><br />
<input name="submit" type="submit" id="submit" value="Change Type">
</form></P>
	<footer>
		<a href='admin.php'>Back</a>
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