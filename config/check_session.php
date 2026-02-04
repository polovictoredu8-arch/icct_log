<?php
session_start();
header('Content-Type: application/json');

// --- SETTINGS ---
// 30 minutes in seconds (30 * 60 = 1800)
// You can change 1800 to whatever duration you want (e.g., 300 for 5 mins)
$timeout_duration = 60; //30 minutes lahat to bago mag expire at logout

// 1. Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

// 2. Check for Timeout (Expiration)
if (isset($_SESSION['last_activity'])) {
    $seconds_inactive = time() - $_SESSION['last_activity'];
    
    if ($seconds_inactive >= $timeout_duration) {
        // Session expired
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'expired']);
        exit;
    }
}

// 3. Renew the Token (Update last activity time)
$_SESSION['last_activity'] = time();

echo json_encode(['status' => 'active']);
?>