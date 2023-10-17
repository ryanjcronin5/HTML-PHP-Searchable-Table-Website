<?php
// Set the response header to indicate that the content will be in JSON format
header('Content-Type: application/json');

// Create a new MySQLi database connection to the 'information_schema' database
$conn = new mysqli('localhost', 'root', '', 'information_schema');

// Check if the connection to the database was successful
if($conn->connect_error){
    // If there's an error connecting to the database, terminate the script and display an error message
	die("Error Connecting to Database: " . $conn->connect_error);
}

// Define the SQL query to retrieve specific global status variables related to data transfer
$query = "SELECT * FROM GLOBAL_STATUS WHERE VARIABLE_NAME IN ('Bytes_received', 'Bytes_sent')";

// Execute the SQL query and store the result
$result = $conn->query($query);

// Initialise an empty array to store the data
$data = [];

// Check if there are rows in the result
if ($result->num_rows > 0) {
    // Iterate through each row and store the VARIABLE_NAME and VARIABLE_VALUE in the 'data' array
    while ($row = $result->fetch_assoc()) {
        $data[$row['VARIABLE_NAME']] = $row['VARIABLE_VALUE'];
    }
}

// Close the database connection
$conn->close();

// Encode the 'data' array as JSON and send it as the response
echo json_encode($data);
?>
