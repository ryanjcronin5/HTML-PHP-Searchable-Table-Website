<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Data Discovery Club</title>

		<!-- Preconnect to external resources -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<!-- Include external fonts for the page -->
		<link href="https://fonts.googleapis.com/css2?family=Averia+Sans+Libre:wght@300&family=Ubuntu&display=swap" rel="stylesheet">

		<!-- Include Chart.js library for charts -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/Chart.min.js"></script>

		<!-- Link to external stylesheet (index.css) for styling -->
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<!-- Page Header -->
		<header class="base">
			<h1>Data Discovery Club Members List</h1>
		</header>

		<!-- Introduction Section -->
		<section class="base">
			<p>Welcome to our Data Discovery Club Database! Our platform provides a simple and effective way to search for members and their information. Whether you're looking for specific individuals or just exploring, our user-friendly search feature allows you to find members based on their last name, first name, or other criteria. With a clear and intuitive interface, you can quickly access details such as date of birth, contact information, and more. Plus, our responsive design ensures that you can access the database on any device. Explore, search, and discover with ease on our Data Discovery Club Database!</p>
		</section>

		<!-- Main Content Container -->
		<div class="base container_base">
			<!-- Search and Server Status Section -->
			<section>
				<h3>Club Member Look Up</h3>
				<form action="" method="post">
					<input type="text" id="searchInput" name="searchInput" placeholder="Search...">
					<select name="option" id="option">
						<option value="u.LastName">Last Name</option>
						<option value="u.FirstName">First Name</option>
						<option value="u.DateofBirth">Date of Birth</option>
						<option value="a.Suburb">Suburb</option>
						<option value="a.City">City</option>
						<option value="c.Phone">Phone</option>
						<option value="c.Email">Email</option>
					</select>
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

			<!-- Table Display and Switching Section -->
			<aside>
				<div class="buttonGroup" style="width: 100%;">
					<button id="btn1" class="button swapBtn active" onclick="showTable(1)">User Details</button>
					<button id="btn2" class="button swapBtn" onclick="showTable(2)">Financial Details</button>
				</div>

				<!-- User Details Table -->
				<table id="resultTable">
					<!-- PHP code to retrieve and display user details -->
					<?php
					include 'includes/dbc_inc.php';
					$sql = 
					"SELECT 
						u.LastName, 
						u.FirstName, 
						COALESCE(u.DateofBirth,'Not Listed') AS DateofBirth, 
						COALESCE(a.Suburb, 'Not Listed') AS Suburb, 
						COALESCE(a.City, 'Not Listed') AS City, 
						COALESCE(c.Phone, 'Not Listed') AS Phone, 
						COALESCE(c.Email, 'Not Listed') AS Email 
					FROM users u 
					LEFT JOIN address a ON u.AddressID = a.ID 
					LEFT JOIN contact c ON u.ContactID = c.ID";
					$searchColumns = ["LastName", "FirstName", "DateofBirth", "Suburb", "City", "Phone", "Email"];
					if(isset($_POST['submit'])) {
						$search = $_POST['searchInput'];
						$option = $_POST['option'];
						$whereClause = "";
						foreach ($searchColumns as $column) {
							if ($whereClause !== "") {
								$whereClause .= " OR ";
							}
							$whereClause .= "$column LIKE '%$search%'";
						}
						if (!empty($whereClause)) {
							$sql .= " WHERE $whereClause ORDER BY $option";
						}	
					}
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						echo "<thead><tr><th>Last Name</th><th>First Name</th><th>Date of Birth</th><th>Suburb</th><th>City</th><th>Phone</th><th>Email</th></tr></thead>";
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

				<!-- Financial Details Table -->
				<table id="resultTable2" style="display: none;">
					<!-- PHP code to retrieve and display financial details -->
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

		<!-- Graph Section -->
		<section class="base">
			<div style="width: 80%; margin: 0 auto;">
        		<canvas id="byteGraph"></canvas>
				<script>
					// JavaScript code to create and update a line chart
					var data = {
					labels: [],
					datasets: [
							{label: 'Bytes Sent', borderColor: 'rgb(75, 192, 192)', fill: false, data: [],},
							{label: 'Bytes Received', borderColor: 'rgb(255, 99, 132)', fill: false, data: [],},
						],
					};
					var config = {
						type: 'line',
						data: data,
						options: {
							responsive: true,
							scales: {
								x: {display: true, min: 0, title: {display: true, text: 'Time',},},
								y: {beginAtZero: true, title: {display: true, text: 'Bytes',},},
							},
						},
					};
					var ctx = document.getElementById('byteGraph').getContext('2d');
					var chart = new Chart(ctx, config);
					function updateGraph() {
						fetch('includes/graph.php')
							.then(response => response.json())
							.then(data => {
								var time = new Date().toLocaleTimeString();
								var bytesSent = parseInt(data['BYTES_SENT']);
								var bytesReceived = parseInt(data['BYTES_RECEIVED']);
								if (!Array.isArray(config.data.labels)) {
									config.data.labels = [];
									config.data.datasets[0].data = [];
									config.data.datasets[1].data = [];
								}
								config.data.labels.push(time);
								config.data.datasets[0].data.push(bytesSent);
								config.data.datasets[1].data.push(bytesReceived);
								if (config.data.labels.length > 10) {
									config.data.labels.shift();
									config.data.datasets[0].data.shift();
									config.data.datasets[1].data.shift();
								}
								chart.update();
							})
							.catch(error => console.error(error));
					}
					setInterval(updateGraph, 2000);
				</script>
    		</div>
		</section>

		<!-- Footer Section -->
		<footer class="base">
			<h3>Footer</h3>
		</footer>

		<!-- JavaScript functions for clearing search and switching tables -->
		<script>
		function clearSearch() {
			document.getElementById('searchInput').value = '';
		}
		function showTable(tableNumber) {
			// JavaScript code to switch between user and financial details tables
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

		<!-- Include external Javascript file (index.js) -->
		<script src="index.js"></script>
	</body>
</html>
