<?php

// config/login_register.php

require_once 'connect.php'; // Include database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
    $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Basic form validation (you can add more)
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $check_username_query = "SELECT * FROM users_tb WHERE username = '$username'";
    $check_username_result = mysqli_query($conn, $check_username_query);

    if (mysqli_num_rows($check_username_result) > 0) {
        echo "Username already exists. Please choose a different username.";
        exit();
    }

    // Insert user data into the database
    $insert_query = "INSERT INTO users_tb (firstname, surname, username, password, role) VALUES ('$firstname', '$surname', '$username', '$hashed_password', 'user')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Registration successful!";
        // Redirect to login page or display success message
        header("Location: ../index.html"); // Redirect to homepage after successful registration
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}

?>