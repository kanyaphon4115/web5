<?php
header('Content-Type: application/json');
include("../db2/connection.php");

// ดึงเฉพาะฟิลด์ที่ต้องการ
$sql = "SELECT id, title, description FROM news ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

$news = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $news[] = $row;
    }
    echo json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Query failed']);
}
?>
