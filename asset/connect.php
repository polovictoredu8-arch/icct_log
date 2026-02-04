<?php

// Database credentials
$host = "localhost"; // Replace with your database host (e.g., localhost)
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "icct_emp"; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Form data handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect value of input field
  $firstname = $_POST['firstname'];
  $surname = $_POST['surname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

  // Prepare SQL query
  $sql = "INSERT INTO users_tb (firstname, surname, username, password) VALUES (?, ?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ssss", $firstname, $surname, $username, $password);

  // Execute query
  if ($stmt->execute()) {
    echo "New record created successfully";
    header("Location: ../index.html"); // Redirect to a success page
    exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close statement
  $stmt->close();
}

// Close connection
$conn->close();
?>