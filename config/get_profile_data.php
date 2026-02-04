<?php
session_start();

// Database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "icct_emp";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and session ID is set
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Check if profile data exists in the session
    if (isset($_SESSION['email']) && isset($_SESSION['address']) && isset($_SESSION['gender']) && isset($_SESSION['age']) && isset($_SESSION['date_of_birth']) && isset($_SESSION['contact']) && isset($_SESSION['marital_status']) && isset($_SESSION['bio']) ) {
        // Use session data
        $profile_data = array(
            'firstname' => $_SESSION['firstname'],
            'email' => $_SESSION['email'],
            'address' => $_SESSION['address'],
            'gender' => $_SESSION['gender'],
            'age' => $_SESSION['age'],
            'date_of_birth' => $_SESSION['date_of_birth'],
            'contact' => $_SESSION['contact'],
            'marital_status' => $_SESSION['marital_status'],
            'bio' => $_SESSION['bio'],
            'profile_picture' => isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : null  // Use default if not set
        );
    } else {
        // Fetch data from the database
        // Modified SQL query to use user_id
        $sql = "SELECT firstname, email, contact, bio, age, date_of_birth, gender, marital_status, address, profile_picture FROM dash_inf WHERE user_id = ?";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("i", $user_id);

        // Execute query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $profile_data = $result->fetch_assoc();

            // Store data in session for future use
            $_SESSION['firstname'] = $profile_data['firstname'];
            $_SESSION['email'] = $profile_data['email'];
            $_SESSION['address'] = $profile_data['address'];
            $_SESSION['gender'] = $profile_data['gender'];
            $_SESSION['age'] = $profile_data['age'];
            $_SESSION['date_of_birth'] = $profile_data['date_of_birth'];
            $_SESSION['contact'] = $profile_data['contact'];
            $_SESSION['marital_status'] = $profile_data['marital_status'];
            $_SESSION['bio'] = $profile_data['bio'];
            $_SESSION['profile_picture'] = $profile_data['profile_picture'];
        } else {
            header('Content-Type: application/json'); // Ensure correct header
            echo json_encode(array('error' => 'Profile not found'));
            exit;
        }

        // Close statement
        $stmt->close();
    }

    // Set Content-Type header to application/json
    header('Content-Type: application/json');

    echo json_encode($profile_data); // Return data as JSON
} else {
    // Set Content-Type header to application/json
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'User not logged in'));
}

// Close connection
$conn->close();
?>