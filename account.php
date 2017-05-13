<?php
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true){
    $id = $_SESSION['user_info']['id'];
    db_init();
    $query = run_mysql_query("SELECT avatar FROM users WHERE id = '$id'");
    $output = mysql_fetch_assoc($query)
  ?>  
    <article id="main_content_body">
    <header>
        <div id="main_content_title">Account</div>
    </header>
        <img src="<?php echo $output['avatar'] ?>" width="130" height="130" alt="" />
    <footer>
        
    </footer>
    
</article> 

<?php

}else{
    header('Location: index.php');
    exit;
}
page_footer();
