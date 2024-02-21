<?php
require_once("config.php");

if (isset($_POST['searchKeyword'])) {
    $searchKeyword = $_POST['searchKeyword'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE firstname LIKE ? OR lastname LIKE ? OR email LIKE ?");
    $stmt->execute(["%$searchKeyword%", "%$searchKeyword%", "%$searchKeyword%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
