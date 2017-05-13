<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true){
$user_id= $_SESSION['user_info']['id'];
db_init();

?> 
<script type="text/javascript">
checked=false;
function checkedAll (form1) {
    var aa= document.getElementById('form1');
     if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
    for (var i =0; i < aa.elements.length; i++) 
    {
     aa.elements[i].checked = checked;
    }
      }
</script>

<article id="main_content_body">
	<header>
		<hgroup>
			<div id="main_content_title">Inbox</div>
			<a id="link" class="underline" href="send_message.php">Sent Message</a><br />
		</hgroup>
	</header><br />
        <center>
	<p>
<?php
$q_messages="SELECT *
            FROM messages
            WHERE receiver_id='$user_id'
            ORDER BY `time` DESC";
$r_messages=run_mysql_query($q_messages) or die (mysql_error());
$count=mysql_num_rows($r_messages);
if ($count==0){
echo '<div><img src="img/text-chat.png" width="24" height="24" />Няма съобщения!</div>';
} else {
    ?>
<p><img src="img/text-chat.png" width="24" height="24" />&nbsp;<span style="bottom:3px; position:relative;">You can see the content of a message by clicking on his subject.</span></p>
<form name="form1" id="form1" method="post" action="inbox.php">
<table class="list">
    <tr>
        <th id="link" ><input type='checkbox' name='checkall' onclick='checkedAll(form1);'></th>
        <th id="link"><span class="display_color">№</span></th>
        <th id="link"><span class="display_color">From</span></th>
        <th id="link"><span class="display_color">Subject</span></th>
        <th id="link"><span class="display_color">Sent on</span></th>
    </tr>
<?php
function get_sender(){
    global $sender_id;
    /*global $sender_name;*/
    global $name;
	global $surname;
    $q_function="SELECT name, surname
                FROM users
                WHERE id='$sender_id'";
    $r_function=run_mysql_query($q_function) or die (mysql_error());
    $row=mysql_fetch_array($r_function);
    /*$sender_name=$row['username'];   $q_function="SELECT username
                FROM users
                WHERE id='$sender_id'";*/
	$name=$row['name'];
	$surname=$row['surname'];
}
$nomer=1;
date_default_timezone_set('Europe/Sofia');
while ($userData=mysql_fetch_array($r_messages)){
    $sender_id=$userData['sender_id'];
    $title=$userData['title'];
    $message=$userData['message'];
    $time=$userData['time'];
    $message_id=$userData['id'];
    $readed=$userData['readed'];
    $date=date('d-m-Y | G:i ', $time);
    echo "<tr><td><input name='checkbox[]' type='checkbox' id='checkbox[]' value='";
    echo $message_id;
    echo "'></td><td>";
    echo $nomer;
    echo "</td><td>"; /*<a id='link' class='underline' href='view_user.php?user_id=
    echo $sender_id;
    echo "'>";*/
    get_sender();
    echo ''.$name.' '.$surname.''; /*$sender_name;*/
    echo "</a></td><td><a id='link' class='underline' href='view_message.php?id=";
    echo $message_id;
    echo "'>";
    if ($readed==1){
    echo "<font color='#555555'>";
    }
    echo $title;
    if ($readed==1){
    echo "</font>";
    }
    echo "</a></td><td>";
    echo $date;
    echo "</td></tr>";
    $nomer++;
}
?>
<td colspan="5" align="center"><input name="delete" type="submit" id="delete" value="Delete"></td>
<?php
if(isset($_POST['delete'])){
for($i=0;$i<$count;$i++){
$del_id = $_POST['checkbox'][$i];
$sql = "DELETE FROM messages WHERE id='$del_id'";
$result = run_mysql_query($sql) or die (mysql_error());
}
if($result){
echo "<meta http-equiv='refresh' content='0;URL=inbox.php'>";
}
}
?>
</table>
</form>
</P>
</center>
	<footer>
		
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

