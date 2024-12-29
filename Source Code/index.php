<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "finance_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['full_name'], $_POST['email'], $_POST['password'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $check_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "User with this email already exists!";
    } else {
        $sql = "INSERT INTO users (email, password, full_name) VALUES ('$email', '$hashed_password', '$full_name')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['email'] = $email;
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password']) && !isset($_POST['full_name'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            header("Location: homepage.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Track - Login & Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-x: hidden;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }
        .container.show {
            opacity: 1;
            transform: translateY(0);
        }
        h2 {
            margin-bottom: 1em;
            color: #4CAF50;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 92%;
            padding: 0.8em;
            margin: 0.8em 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        button {
            width: 100%;
            padding: 0.8em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover {
            background-color: #45a049;
        }
        .switch-link {
            margin-top: 1em;
            color: #4CAF50;
            text-decoration: none;
        }
        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container" id="signup-container">
        <h2>Create an Account</h2>
        <form action="index.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Signup</button>
        </form><br>
        <a href="#login" class="switch-link">Already have an account? Login</a>
    </div>

    <div class="container" id="login-container">
        <h2>Login to Finance Track</h2>
        <form action="index.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form><br>
        <a href="#signup" class="switch-link">Don't have an account? Signup</a>
    </div>

    <footer>
        <p>Finance Track © 2024. All Rights Reserved.</p>
    </footer>

    <script>
        window.onload = function() {
            document.getElementById("login-container").classList.add("show");
        };

        function showLoginForm() {
            const loginContainer = document.getElementById("login-container");
            const signupContainer = document.getElementById("signup-container");

            signupContainer.classList.remove("show");
            setTimeout(() => {
                loginContainer.classList.add("show");
            }, 200);
        }

        function showSignupForm() {
            const loginContainer = document.getElementById("login-container");
            const signupContainer = document.getElementById("signup-container");

            loginContainer.classList.remove("show");
            setTimeout(() => {
                signupContainer.classList.add("show");
            }, 200);
        }

        document.querySelectorAll(".switch-link").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                const linkText = this.textContent.trim();
                if (linkText === "Don't have an account? Signup") {
                    showSignupForm();
                } else if (linkText === "Already have an account? Login") {
                    showLoginForm();
                }
            });
        });
    </script>

</body>
</html>
