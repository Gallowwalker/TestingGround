<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true && $_SESSION['user_info']['type']==3){
if(isset($_POST['submit'])){
db_init();

$id = (int)$_POST['id'];
$active = $_POST['active'];

$sql = "UPDATE users SET active = $active WHERE id = $id" ;


$retval = run_mysql_query( $sql );
if(! $retval )
{
  die('Could not delete data: ' . mysql_error());
}

echo "<article><br /><img src='img/button_ok.png' width='16' heigh='16' />&nbsp;<span style='bottom:4px; position:relative;'>Data deleted successfully!</span><br /><br /><a href='access_change.php'>Back</a></article>";

mysql_close();
}else{
?>
<article>
	<header>
		<hgroup>
			<h2 class="display_color">Change Access</h2>
		</hgroup>
	</header><br />
	<p>
<form method="post" action="<?php $_PHP_SELF ?>">
<input name="id" type="text" id="id" placeholder="User ID"><br /><br />
	<select name="active" id="active">
		<option selected value="0">Deny</option>
		<option selected value="1">Allow</option>
	</select>
<br /><br />
<input name="submit" type="submit" id="submit" value="Change Access">
</form>
</P>
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