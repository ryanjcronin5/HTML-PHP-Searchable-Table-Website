<?php
  include 'includes/dbc_inc.php';
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
foreach ($statusVariables as $variable) {
  $sql = "SHOW GLOBAL STATUS LIKE '$variable'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<b>{$variable}:</b> " . $row['Value'] . "<br>";
  }
}
$conn->close();
?>
