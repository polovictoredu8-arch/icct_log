<?php
session_start();
header('Content-Type: application/json');
$conn = new mysqli("sql202.infinityfree.com", "if0_41088255", "adminicct10", "if0_41088255_icct_emp";
");
$user_id = $_SESSION['id'];

$sql = "SELECT date, day_of_week, time_in, time_out, status, late_minutes FROM attendance_tb WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while($r = $result->fetch_assoc()) {
    $rows[] = $r;
}
echo json_encode($rows);
?>
