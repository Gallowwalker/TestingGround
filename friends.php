<?php 
session_start();
include 'system.php';
page_header();
if(isset($_SESSION['is_logged'])===true){
    ?>
<article id="main_content_body">
            <header>
                <div id="main_content_title">Friend list</div>
            </header><br />
            sdffdsfsdf
            <footer>
                
            </footer>
</article>
<?php
}else{
    
}
page_footer();
