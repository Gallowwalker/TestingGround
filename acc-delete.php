<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true && $_SESSION['user_info']['type']==3){
if(isset($_POST['submit'])){
db_init();

$id = (int)$_POST['id'];

$sql = "DELETE FROM users WHERE id = $id" ;

$retval = run_mysql_query( $sql );
if(! $retval )
{
  die('Could not delete data: ' . mysql_error());
}
$q="SELECT id
                FROM users
                WHERE username='$id'";
            $r=run_mysql_query($q)or die (mysql_error());
if (mysql_num_rows($r)==0){
$userData=mysql_fetch_array($r);
echo "<article><br /><img src='img/button_cancel.png' width='16' heigh='16' />&nbsp;<span style='bottom:4px; position:relative;'>There is no such user!</span><br /><br /><a href='acc_delete.php'>Back</a></article>";
}else{
echo "<article><br /><img src='img/button_ok.png' width='16' heigh='16' />&nbsp;<span style='bottom:4px; position:relative;'>Data deleted successfully!</span><br /><br /><a href='acc_delete.php'>Back</a></article>";
}
mysql_close();
}else{
?>
<article>
	<header>
		<hgroup>
			<h2 class="display_color">Delete Account</h2>
		</hgroup>
	</header><br />
	<p><form method="post" action="<?php $_PHP_SELF ?>">
<input name="id" type="text" id="id" placeholder="User ID"><br /><br />
<input name="submit" type="submit" id="submit" value="Delete Account">
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
