<?php


$con=mysqli_connect("localhost","root","","accounts"); //Change it if required

// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM profile_img " );


while($userData = mysqli_fetch_array($result))
{
echo '<img src="' . $userData['src'] . '"  />';
echo'<br /><br />';  
}


mysqli_close($con);



