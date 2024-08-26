<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Orders</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../indexadmin.php">GameHub Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewgame.php">Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewgenre.php">Genres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewmembership.php">Membership Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewsubscription.php">Subscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewpurchase.php">Purchase</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewuser.php">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewadmin.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../includes/logout.php">Log Out</a>
                </li>
            </ul>
        </div>
</nav>
<body class="vieworder">
    <div class="container">
        <div class="table-container">
            <h1>View Orders</h1>
            <form class="search-form" method="GET" action="">
                <input type="text" name="search" placeholder="Search...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connect to the database
                    $conn = mysqli_connect("localhost", "root", "", "test");
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Pagination settings
                    $resultsPerPage = 10;
                    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                    $startFrom = ($currentPage - 1) * $resultsPerPage;

                    // Handle search query
                    if (isset($_GET['search'])) {
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        $sql = "SELECT * FROM orders WHERE orderID LIKE '%$search%' OR orderDate LIKE '%$search%' OR statusOrder LIKE '%$search%' OR total LIKE '%$search%' LIMIT $startFrom, $resultsPerPage";
                        $sqlCount = "SELECT COUNT(*) AS totalCount FROM orders WHERE orderID LIKE '%$search%' OR orderDate LIKE '%$search%' OR statusOrder LIKE '%$search%' OR total LIKE '%$search%'";
                    } else {
                        $sql = "SELECT * FROM orders LIMIT $startFrom, $resultsPerPage";
                        $sqlCount = "SELECT COUNT(*) AS totalCount FROM orders";
                    }

                    // Fetch orders data from the database
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        die("Error retrieving orders: " . mysqli_error($conn));
                    }

                    // Display orders in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['orderID'] . "</td>";
                        echo "<td>" . $row['orderDate'] . "</td>";
                        echo "<td>" . $row['statusOrder'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "<td><a href='editorder.php?id=" . $row['orderID'] . "' class='btn btn-primary'>Edit</a></td>";
                        echo "<td><a href='deleteorder.php?id=" . $row['orderID'] . "' class='btn btn-danger'>Delete</a></td>";
                        echo "</tr>";
                    }

                    // Pagination links
                    $resultCount = mysqli_query($conn, $sqlCount);
                    $row = mysqli_fetch_assoc($resultCount);
                    $totalCount = $row['totalCount'];
                    $totalPages = ceil($totalCount / $resultsPerPage);

                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($currentPage == $i) ? 'active' : '';
                        echo "<a href='?page=" . $i . "&search=" . urlencode(isset($_GET['search']) ? $_GET['search'] : '') . "' class='$activeClass'>" . $i . "</a>";
                    }
                    echo "</div>";

                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	<br>
</body>
</html>
<?php include 'footer.php'; ?>
