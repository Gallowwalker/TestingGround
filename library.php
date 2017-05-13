<?php
include 'D:\Software\xampp\htdocs\TestingGround2\system.php';
include 'D:\Software\xampp\htdocs\TestingGround2\event.php';
System::sessionStart();
System::setContentTitle("Библиотека");
System::getPageHeader();

if (isset($_SESSION['isLogged']) === true) {
    
    
   
    
?>

<div class="library-folder">
    <a href="os-folder.php" ><img src="img/other/folder_closed.png" width="96" height="96" /><br />
    <div class="folder-name">Операционни <br />Системи</div></a>
</div>
<div class="library-folder">
    <a href="" ><img src="img/other/folder_closed.png" width="96" height="96" /><br />
    <div class="folder-name">Други</div></a>
</div>
<div class="library-folder">
    <img src="img/web/tomster-under-construction.png" width="96" height="96" /><br />
    <div class="folder-name">Under Construction</div>
</div>	 
<?php
} else {
    Event::getLibraryLoginError();
}			
System::getPageFooter();