<?php
// Include the database connection script ('dbc_inc.php')
include 'includes/dbc_inc.php';

// Define an array of specific MySQL server status variables to retrieve
$statusVariables = [
    'Connections',
    'Uptime',
    'Threads_connected',
    'Queries',
    'Slow_queries',
    'Key_reads',
    'Key_read_requests',
    'Key_write_requests',
    'Innodb_buffer_pool_size',
    'Innodb_buffer_pool_pages_free',
    'Table_locks_waited',
    'Created_tmp_tables',
    'Aborted_connects',
    'Max_used_connections'
];

// Loop through each status variable and retrieve its value from the MySQL server
foreach ($statusVariables as $variable) {
    // Define an SQL query to retrieve the value of the current status variable
    $sql = "SHOW GLOBAL STATUS LIKE '$variable'";

    // Execute the SQL query and store the result
    $result = $conn->query($sql);

    // Check if there are rows in the result
    if ($result->num_rows > 0) {
        // Fetch the row (there should be only one) and display the variable name and its value
        $row = $result->fetch_assoc();
        echo "<b>{$variable}:</b> " . $row['Value'] . "<br>";
    }
}

// Close the database connection
$conn->close();
?>
