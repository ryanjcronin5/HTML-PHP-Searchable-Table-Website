<?php
// Create a new MySQLi database connection
$conn = new mysqli('localhost', 'root', '', 'rcronin_main');

// Check if the connection was successful
if($conn->connect_error){
	// If there's an error connecting to the database, terminate the script and display an error message
	die("Error Connecting to Database: " . $conn->connect_error);
}
?>
