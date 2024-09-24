<?php
session_start();
require_once("../config.php");

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_login'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_login'];
$drink_id = isset($_POST['drink_id']) ? (int)$_POST['drink_id'] : 0;

if ($drink_id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM user_favorites_drink WHERE user_id = :user_id AND drink_id = :drink_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':drink_id', $drink_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("DELETE FROM user_favorites_drink WHERE user_id = :user_id AND drink_id = :drink_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':drink_id', $drink_id, PDO::PARAM_INT);
            $stmt->execute();
            $status = "removed";
        } else {
            $stmt = $conn->prepare("INSERT INTO user_favorites_drink (user_id, drink_id) VALUES (:user_id, :drink_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':drink_id', $drink_id, PDO::PARAM_INT);
            $stmt->execute();
            $status = "added";
        }

        echo json_encode(["status" => "success", "action" => $status]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid drink ID."]);
}
?>
