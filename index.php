<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Data Discovery Club</title>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<header class="base">
			<h1>Data Discovery Club Members List</h1>
		</header>
		<section class="base">
			<p>Welcome to our Data Discovery Club Database! Our platform provides a simple and effective way to search for members and their information. Whether you're looking for specific individuals or just exploring, our user-friendly search feature allows you to find members based on their last name, first name, or other criteria. With a clear and intuitive interface, you can quickly access details such as date of birth, contact information, and more. Plus, our responsive design ensures that you can access the database on any device. Explore, search, and discover with ease on our Data Discovery Club Database!</p>
		</section>
		<div class="base container_base">
			<section>
				<h3>Club Member Look Up</h3>
				<form action="" method="post">
					<input type="text" id="searchInput" name="searchInput" placeholder="Search...">
					<div class="buttonGroup" style="width: 100%;">
						<button class="button buttonSearch" type="submit" name="submit">Search</button>
						<button class="button buttonClear" type="submit" onclick="clearSearch()">Clear</button>
					</div>
				</form>
				<h3>Server Status:</h3>
				<div id="serverStatus">
					<?php include 'serverStatus.php'; ?>
				</div>
			</section>
			<aside>
				<div class="buttonGroup" style="width: 100%;">
					<button id="btn1" class="button swapBtn active" onclick="showTable(1)">User Details</button>
					<button id="btn2" class="button swapBtn" onclick="showTable(2)">Financial Details</button>
				</div>
				<table id="resultTable">
					<?php
					include 'includes/dbc_inc.php';
					$sql = "SELECT u.LastName, u.FirstName, COALESCE(u.DateofBirth,'Not Listed') AS DateofBirth, COALESCE(a.Suburb, 'Not Listed') AS Suburb, COALESCE(a.City, 'Not Listed') AS City, COALESCE(c.Phone, 'Not Listed') AS Phone, COALESCE(c.Email, 'Not Listed') AS Email FROM users u LEFT JOIN address a ON u.AddressID = a.ID LEFT JOIN contact c ON u.ContactID = c.ID";
					$searchColumns = ["LastName", "FirstName", "DateofBirth", "Suburb", "City", "Phone", "Email"];
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
							$sql .= " WHERE $whereClause ORDER BY u.LastName, u.FirstName, c.Phone";
						}	
					}
					$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							echo "<thead><tr><th colspan='2'>Full Name</th><th>Date of Birth</th><th>Suburb</th><th>City</th><th>Phone</th><th>Email</th></tr></thead>";
							while ($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "<td>" . $row["LastName"] . "</td>";
								echo "<td>" . $row["FirstName"] . "</td>";
								echo "<td>" . $row["DateofBirth"] . "</td>";
								echo "<td>" . $row["Suburb"] . "</td>";
								echo "<td>" . $row["City"] . "</td>";
								echo "<td>" . $row["Phone"] . "</td>";
								echo "<td>" . $row["Email"] . "</td>";
								echo "</tr>";
							}
						} else {
							echo "No results found.";
						}
						$conn->close();		
					?>
				</table>
				<table id="resultTable2" style="display: none;">
					<?php
					include 'includes/dbc_inc.php';
					$sql = "SELECT u.LastName, u.FirstName, o.RefID, o.DateofPurchase, o.ItemNum, o.TotalCost, o.Owed, o.Status, a.Credit FROM users u JOIN orders o ON u.OrdersID = o.ID JOIN account a ON u.AccountID = a.ID";
					$searchColumns = ["LastName", "FirstName", "RefID", "DateofPurchase", "ItemNum", "Owed", "Status"];
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
							$sql .= " WHERE $whereClause ORDER BY o.DateofPurchase, o.Status, u.LastName";
						}	
					}
					echo "<thead><tr><th colspan='2'>Full Name</th><th>Ref</th><th>Date of Purchase</th><th>Num of Items</th><th>Total Cost</th><th>Owing</th><th>Status</th><th>Credit</th></tr></thead>";
					$result = $conn->query($sql);
					$totalCostSum = 0;
					$owingSum = 0;
					$creditSum = 0;
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							echo "<tr>";
							echo "<td>" . $row["LastName"] . "</td>";
							echo "<td>" . $row["FirstName"] . "</td>";
							echo "<td>" . $row["RefID"] . "</td>";
							echo "<td>" . $row["DateofPurchase"] . "</td>";
							echo "<td>" . $row["ItemNum"] . "</td>";
							echo "<td>$ " . $row["TotalCost"] . "</td>";
							echo "<td>$ " . $row["Owed"] . "</td>";
							echo "<td>" . $row["Status"] . "</td>";
							echo "<td>$ " . $row["Credit"] . "</td>";
							echo "</tr>";
							$totalCostSum += $row["TotalCost"];
							$owingSum += $row["Owed"];
							$creditSum += $row["Credit"];
						}
						echo "<tfoot><tr id='table_footer_row'><td colspan='5'>Total:</td><td>$ " . $totalCostSum . "</td><td>$ " . $owingSum . "</td><td></td><td>$ " . $creditSum . "</td></tr></tfoot>";
					} else {
						echo "<td colspan=9>No results found.</td>";
					}
					$conn->close();		
					?>
				</table>
			</aside>
		</div>
		<footer class="base">
			<h3>Footer</h3>
		</footer>
		<script>
		function clearSearch() {
			document.getElementById('searchInput').value = '';
		}
		function showTable(tableNumber) {
			if (tableNumber === 1) {
				document.getElementById('resultTable').style.display = 'table';
				document.getElementById('resultTable2').style.display = 'none';
				document.getElementById('btn1').classList.add('active');
				document.getElementById('btn2').classList.remove('active');
			} else if (tableNumber === 2) {
				document.getElementById('resultTable').style.display = 'none';
				document.getElementById('resultTable2').style.display = 'table';
				document.getElementById('btn1').classList.remove('active');
				document.getElementById('btn2').classList.add('active');
			}
		}
		</script>
		<script src="index.js"></script>
	</body>
</html>