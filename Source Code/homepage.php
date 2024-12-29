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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['income-source']) && isset($_POST['income-amount'])) {
    $source = $conn->real_escape_string($_POST['income-source']);
    $amount = $conn->real_escape_string($_POST['income-amount']);

    $sql = "INSERT INTO income (email, source, amount) VALUES ('$email', '$source', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Income added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['expense-category']) && isset($_POST['expense-amount'])) {
    $category = $conn->real_escape_string($_POST['expense-category']);
    $amount = $conn->real_escape_string($_POST['expense-amount']);

    $sql = "INSERT INTO expenses (email, category, amount) VALUES ('$email', '$category', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Expense added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['budget-amount'])) {
    $budgetAmount = $conn->real_escape_string($_POST['budget-amount']);

    $sql = "INSERT INTO budget (email, monthly_budget) VALUES ('$email', '$budgetAmount')";

    if ($conn->query($sql) === TRUE) {
        echo "Budget set successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Track - Personal Finance Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1em;
            text-align: center;
            width: 100%;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 1em;
            margin: 1em 0;
            width: 100%;
        }
        nav a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5em;
            width: 100%;
            max-width: 1200px;
        }
        .card {
            background: white;
            padding: 1.5em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
        }
        #dashboard, #budget {
            width: 100%;
            text-align: center;
        }
        .income-expenses {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5em;
            width: 100%;
        }
        .income-expenses .card {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1em;
        }
        form label {
            font-weight: bold;
        }
        form input, form button {
            padding: 0.5em;
            font-size: 1em;
            width: 100%;
            max-width: 300px;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        footer {
            text-align: center;
            padding: 1em;
            background-color: #4CAF50;
            color: white;
            width: 100%;
            margin-top: auto;
        }
        @media (max-width: 768px) {
            .income-expenses {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header>
        <h1>Finance Track</h1>
        <p>Manage your income, expenses, and budget efficiently.</p>
    </header>

    <nav>
        <a href="#dashboard">Dashboard</a>
        <a href="#income">Income</a>
        <a href="#expenses">Expenses</a>
        <a href="#budget">Budget</a>
    </nav>

    <div class="container">
        <section id="dashboard" class="card">
            <h2>Dashboard</h2>
            <p>Overview of your financial status.</p>
        </section>

        <div class="income-expenses">
            <section id="income" class="card">
                <h2>Income</h2>
                <form action="homepage.php" method="POST">
                    <label for="income-source">Source:</label>
                    <input type="text" id="income-source" name="income-source" required>
                    <label for="income-amount">Amount:</label>
                    <input type="number" id="income-amount" name="income-amount" required>
                    <button type="submit">Add Income</button>
                </form>
            </section>

            <section id="expenses" class="card">
                <h2>Expenses</h2>
                <form action="homepage.php" method="POST">
                    <label for="expense-category">Category:</label>
                    <input type="text" id="expense-category" name="expense-category" required>
                    <label for="expense-amount">Amount:</label>
                    <input type="number" id="expense-amount" name="expense-amount" required>
                    <button type="submit">Add Expense</button>
                </form>
            </section>
        </div>

        <section id="budget" class="card">
            <h2>Budget</h2>
            <form action="homepage.php" method="POST">
                <label for="budget-amount">Monthly Budget:</label>
                <input type="number" id="budget-amount" name="budget-amount" required>
                <button type="submit">Set Budget</button>
            </form>
        </section>

        <section class="card">
            <a href="manage_data.php" class="button" style="padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; font-size: 1.2em; display: inline-block; text-align: center;">Manage Your Data</a>
        </section>
    </div>
    <br><br>
    <footer>
        <p>Finance Track © 2024. All Rights Reserved.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        gsap.from("header", { duration: 1, y: -50, opacity: 0, ease: "power2.out" });
        gsap.from("nav a", { duration: 1, opacity: 0, stagger: 0.2, ease: "power2.out" });

        gsap.to(".card", { 
            duration: 1, 
            opacity: 1, 
            y: 0, 
            stagger: 0.3, 
            ease: "power2.out",
            delay: 0.5
        });
    </script>

</body>
</html>
