## Website Preview
![alt text](https://github.com/ryanjcronin5/HTML-PHP-Searchable-Table-Website/blob/main/localhost_ASCmemberSearch_%20(1).png "Website Preview")

## Template Code
I would love to see what you create with this code so please contact me if you use it.
#### [Database connection:](https://github.com/ryanjcronin5/memberListSearch/blob/129b477adb3c474580a989d215079acea8ea5e8b/includes/dbc_inc.php)
```php
<?php
// Create a new MySQLi database connection
$conn = new mysqli('serverName', 'userName', 'passWord', 'dbName');

// Check if the connection was successful
if($conn->connect_error){
    // If there's an error connecting to the database, terminate the script and display an error message
    die("Error Connecting to Database: " . $conn->connect_error);
}
?>
```
#### [HTML / PHP Code for Table and Search:](https://github.com/ryanjcronin5/memberListSearch/blob/129b477adb3c474580a989d215079acea8ea5e8b/index.php)
```php
<table id="resultTable">
    <?php
    include 'includes/dbc_inc.php'; // Database connection code
    $sql = 
    "SELECT 
        a.aOne, 
        a.aTwo, 
        a.aThree, 
        b.bOne,
        b.bTwo,
        b.bThree,
        c.cOne,
        c.cTwo,
        c.cThree
        FROM aTable a 
        LEFT JOIN bTable b ON a.bTableID = b.bID 
        LEFT JOIN cTable c ON a.cTableID = c.cID
    ";
    // These can be increased/decreased if some columns don't need to be searched.
    $searchColumns = ["aOne", "aTwo", "aThree", "bOne", "bTwo", "bThree", "cOne", "cTwo", "cThree"]; 
    if(isset($_POST['submit'])) {
        $search = $_POST['searchInput'];
        $whereClause = "";
        foreach ($searchColumns as $column) {
            if ($whereClause !== "") {
                $whereClause .= " OR ";
            }
            $whereClause .= "$column LIKE '%$search%'";
        }
        if (!empty($whereClause)) {
            // These can be changed/increased depending on what order the searched items should appear in.
            $sql .= " WHERE $whereClause ORDER BY a.aOne, a.aTwo, c.cOne";
        }   
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "
        <thead>
            <tr>
                <th>aOne Text</th>
                <th>aTwo Text</th>
                <th>aThree Text</th>
                <th>bOne Text</th>
                <th>bTwo Text</th>
                <th>bThree Text</th>
                <th>cOne Text</th>
                <th>cTwo Text</th>
                <th>cThree Text</th>
            </tr>
        </thead>
        ";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["aOne"] . "</td>";
            echo "<td>" . $row["aTwo"] . "</td>";
            echo "<td>" . $row["aThree"] . "</td>";
            echo "<td>" . $row["bOne"] . "</td>";
            echo "<td>" . $row["bTwo"] . "</td>";
            echo "<td>" . $row["bThree"] . "</td>";
            echo "<td>" . $row["cOne"] . "</td>";
            echo "<td>" . $row["cTwo"] . "</td>";
            echo "<td>" . $row["cThree"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "No results found.";
    }
    $conn->close();     
    ?>
</table>
```
#### [CSS styling:](https://github.com/ryanjcronin5/memberListSearch/blob/129b477adb3c474580a989d215079acea8ea5e8b/index.css)
```css
:root {
    --background-color: rgb(232, 232, 232);
    --text-border-color: rgb(51, 51, 51);
    --element-bg-color: rgb(255, 255, 255);
    --box-shadow-color: rgba(0, 0, 0, 0.1);
    --input-border-color: rgb(204, 204, 204);
    --button-color: rgb(255, 255, 255);
    --button-active-color: rgb(0, 0, 255);
    --table-border-color: rgb(225, 225, 225);
    --table-alt-row-color: rgb(214, 238, 238);
}

/* Styling for tables displaying search results */
#resultTable {
    border-collapse: collapse;
    background-color: var(--element-bg-color);
    border: 3px solid var(--table-border-color);
    width: 50vw;
}

/* Styling for the footer row in tables */
#table_footer_row {
    background-color: var(--element-bg-color);
    font-weight: 600;
    color: var(--text-border-color);
}

/* Styling for table headers and table cells */
th, td {
    padding: 15px 10px;
}

/* Styling for alternating rows in tables (odd rows) */
tr:not(thead tr):nth-child(odd) {
    background-color: var(--table-alt-row-color);
}
```
