<?php
session_start(); // Start the session

// Database credentials
$host = "localhost"; // Replace with your database host
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "icct_emp"; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $sql = "SELECT id, firstname, password FROM users_tb WHERE username = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("s", $username);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, so start a new session
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $username;
            $_SESSION['firstname'] = $firstname;
            // Redirect to dashboard with firstname as a query parameter
            header("Location: ../employee/dashboard.html");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Incorrect username.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>