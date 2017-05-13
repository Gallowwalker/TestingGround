<?php
include 'D:\Software\xampp\htdocs\TestingGround2\system.php';
include 'D:\Software\xampp\htdocs\TestingGround2\event.php';
System::sessionStart();
System::setContentTitle("Папка - Опарационни Системи");
System::getPageHeader();

if (isset($_SESSION['isLogged']) === true) {

System::getFileList("library/operating-systems/");
		
} else {
    Event::getLibraryLoginError();
}			
System::getPageFooter();