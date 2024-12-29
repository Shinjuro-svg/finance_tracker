<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete']) && isset($_GET['table'])) {
    $id = $_GET['delete'];
    $table = $_GET['table'];

    $sql = "DELETE FROM $table WHERE id = $id AND email = '$email'";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$income_sql = "SELECT * FROM income WHERE email = '$email'"; 
$income_result = $conn->query($income_sql);

$expenses_sql = "SELECT * FROM expenses WHERE email = '$email'";
$expenses_result = $conn->query($expenses_sql);

$budget_sql = "SELECT * FROM budget WHERE email = '$email'";
$budget_result = $conn->query($budget_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Data - Finance Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-x: hidden;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            background-color: white;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .button {
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .back-button {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Data</h2>

    <h3>Income</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Source</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $income_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['source']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>&table=income" class="button">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>&table=income" class="button">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Expenses</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $expenses_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>&table=expenses" class="button">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>&table=expenses" class="button">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Budget</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Monthly Budget</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $budget_result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['monthly_budget']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>&table=budget" class="button">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>&table=budget" class="button">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="homepage.php" class="back-button">Back to Homepage</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
