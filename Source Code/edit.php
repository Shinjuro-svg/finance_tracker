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

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];

    if ($table == "income") {
        $sql = "SELECT * FROM income WHERE id = $id AND email = '$email'";
    } elseif ($table == "expenses") {
        $sql = "SELECT * FROM expenses WHERE id = $id AND email = '$email'";
    } elseif ($table == "budget") {
        $sql = "SELECT * FROM budget WHERE id = $id AND email = '$email'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found or not authorized to edit.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($table == "income") {
        $amount = $_POST['amount'];
        $source = $_POST['source'];
        $date = $_POST['date'];

        $update_sql = "UPDATE income SET amount = '$amount', source = '$source', date = '$date' WHERE id = $id AND email = '$email'";
    } elseif ($table == "expenses") {
        $amount = $_POST['amount'];
        $category = $_POST['category'];
        $date = $_POST['date'];

        $update_sql = "UPDATE expenses SET amount = '$amount', category = '$category', date = '$date' WHERE id = $id AND email = '$email'";
    } elseif ($table == "budget") {
        $monthly_budget = $_POST['monthly_budget'];
        $date = $_POST['date'];

        $update_sql = "UPDATE budget SET monthly_budget = '$monthly_budget', date = '$date' WHERE id = $id AND email = '$email'";
    }

    if ($conn->query($update_sql) === TRUE) {
        header("Location: manage_data.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record - Finance Tracker</title>
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
            max-width: 600px;
            background-color: white;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .button {
            padding: 10px 20px;
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
    <h2>Edit Record</h2>

    <form method="POST">
        <?php if ($table == "income") : ?>
            <div class="input-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required>
            </div>
            <div class="input-group">
                <label for="source">Source:</label>
                <input type="text" id="source" name="source" value="<?php echo $row['source']; ?>" required>
            </div>
            <div class="input-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $row['date']; ?>" required>
            </div>
        <?php elseif ($table == "expenses") : ?>
            <div class="input-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required>
            </div>
            <div class="input-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" value="<?php echo $row['category']; ?>" required>
            </div>
            <div class="input-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $row['date']; ?>" required>
            </div>
        <?php elseif ($table == "budget") : ?>
            <div class="input-group">
                <label for="monthly_budget">Monthly Budget:</label>
                <input type="text" id="monthly_budget" name="monthly_budget" value="<?php echo $row['monthly_budget']; ?>" required>
            </div>
            <div class="input-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $row['date']; ?>" required>
            </div>
        <?php endif; ?>

        <button type="submit" class="button">Save Changes</button>
    </form><br>

    <a href="manage_data.php" class="back-button">Back to Manage Page</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
