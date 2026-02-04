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

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_SESSION['id'];

// Collect form data
$email = $_POST['email'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$dob = $_POST['date_of_birth'];
$contact = $_POST['contact'];
$marital = $_POST['marital_status'];
$bio = $_POST['bio'];

// Handle profile picture upload
$profile_picture = null;
if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['profilePicture'];
    $filename = uniqid() . '_' . $file['name']; // Generate a unique filename
    $destination = '../img/' . $filename; // Set the destination path
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $profile_picture = $destination; // Store the path in the database
    } else {
        echo "Error uploading profile picture.";
    }
}

// Prepare SQL query
$sql = "UPDATE dash_inf SET email=?, address=?, gender=?, age=?, date_of_birth=?, contact=?, marital_status=?, bio=?";
$types = "ssssssss";
$params = [$email, $address, $gender, $age, $dob, $contact, $marital, $bio];

// If a new profile picture was uploaded, add it to the query
if ($profile_picture !== null) {
    $sql .= ", profile_picture=?";
    $types .= "s";
    $params[] = $profile_picture;
}

$sql .= " WHERE user_id=?"; // Corrected WHERE clause
$types .= "i";
$params[] = $user_id;

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$stmt->bind_param($types, ...$params);

// Execute query
if ($stmt->execute()) {
    // Update session variables
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;
    $_SESSION['gender'] = $gender;
    $_SESSION['age'] = $age;
    $_SESSION['date_of_birth'] = $dob;
    $_SESSION['contact'] = $contact;
    $_SESSION['marital_status'] = $marital;
    $_SESSION['bio'] = $bio;
    if ($profile_picture !== null) {
        $_SESSION['profile_picture'] = $profile_picture;
    }

    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $conn->error;
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>