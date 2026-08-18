<?php

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $pdo = $database->connect();

    $sql = "SELECT tour_id, tour_name, price
            FROM tours
            ORDER BY tour_id ASC";

    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll();

    echo '<h2>Kết nối database thành công</h2>';

    foreach ($tours as $tour) {
        echo '<p>';
        echo $tour['tour_id'] . ' - ';
        echo htmlspecialchars($tour['tour_name']) . ' - ';
        echo number_format((float)$tour['price'], 0, ',', '.') . ' VNĐ';
        echo '</p>';
    }
} catch (PDOException $e) {
    echo 'Lỗi database: ' . $e->getMessage();
}