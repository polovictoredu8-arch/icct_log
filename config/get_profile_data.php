<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$database = "icct_emp";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['id'];

// LEFT JOIN ensures we get user data even if they haven't set up their dashboard profile yet
$sql = "SELECT 
            u.firstname, 
            d.email, d.address, d.gender, d.age, d.date_of_birth, 
            d.contact, d.marital_status, d.bio, d.profile_picture 
        FROM users_tb u 
        LEFT JOIN dash_inf d ON u.id = d.user_id 
        WHERE u.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    
    // Set default image if null
    if (empty($data['profile_picture'])) {
        $data['profile_picture'] = '../img/default_profile_picture.png';
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>