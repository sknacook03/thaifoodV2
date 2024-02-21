<?php
    require_once("../config.php");

    if(isset($_POST['searchKeyword'])) {
        $searchKeyword = $_POST['searchKeyword'];
        
        $stmt = $conn->prepare("SELECT * FROM food JOIN type ON food.type = type.typeID WHERE name LIKE ? OR type.typeName LIKE ?");
        $stmt->execute(["%$searchKeyword%","%$searchKeyword%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($results);
    }
?>
