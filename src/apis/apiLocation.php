<?php
session_start();
header("Content-Type: application/json");
require('../database/db.php');

$idUser = isset($_POST['idUser']) ? intval($_POST['idUser']) : (isset($_GET['idUser']) ? intval($_GET['idUser']) : null);

if ($idUser) {
    if ($stmt = $link->prepare("SELECT * FROM locations WHERE idUser = ?")) {
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resultLocations = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'data' => [
                    'lat'           => $resultLocations['lat'],
                    'lng'           => $resultLocations['lng'],
                    'titleLocation' => $resultLocations['titleLocation'],
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Toạ độ không tồn tại']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn cơ sở dữ liệu']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID user không hợp lệ']);
}
