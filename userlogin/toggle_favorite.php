<?php
session_start();
require_once("../config.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_login'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_login'];
$food_id = isset($_POST['food_id']) ? (int)$_POST['food_id'] : 0;

if ($food_id > 0) {
    try {
        // ตรวจสอบว่ามีรายการนี้อยู่ในรายการที่ชอบหรือไม่
        $stmt = $conn->prepare("SELECT * FROM user_favorites WHERE user_id = :user_id AND food_id = :food_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // ถ้ามีอยู่แล้ว ให้ลบรายการ
            $stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id = :user_id AND food_id = :food_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
            $stmt->execute();
            $status = "removed";
        } else {
            // ถ้ายังไม่มี ให้เพิ่มรายการ
            $stmt = $conn->prepare("INSERT INTO user_favorites (user_id, food_id) VALUES (:user_id, :food_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
            $stmt->execute();
            $status = "added";
        }

        // ส่งสถานะกลับไปยัง JavaScript
        echo json_encode(["status" => "success", "action" => $status]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid food ID."]);
}
?>
