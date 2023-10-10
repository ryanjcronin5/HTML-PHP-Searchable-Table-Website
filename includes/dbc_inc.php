<?php
$conn = new mysqli('localhost', 'root', '', 'rcronin_main');
if($conn->connect_error){
	die("Error Connecting to Database: " . $conn->connect_error);
}
?>
