<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'information_schema');
if($conn->connect_error){
	die("Error Connecting to Database: " . $conn->connect_error);
}
$query = "SELECT * FROM GLOBAL_STATUS WHERE VARIABLE_NAME IN ('Bytes_received', 'Bytes_sent')";
$result = $conn->query($query);
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['VARIABLE_NAME']] = $row['VARIABLE_VALUE'];
    }
}
$conn->close();
echo json_encode($data);
?>
