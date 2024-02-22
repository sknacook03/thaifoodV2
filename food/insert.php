<?php
session_start();
require_once "../config.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $img = $_FILES['img'];

    $allow = array('jpg', 'jpeg', 'png');
    $extension = explode(".", $img['name']);
    $fileActExt = strtolower(end($extension));
    $fileNew = rand() . "." . $fileActExt;
    $filePath = "uploads/" . $fileNew;

    if (in_array($fileActExt, $allow)) {
        if ($img['size'] > 0 && $img['error'] == 0) {
            if (move_uploaded_file($img['tmp_name'], $filePath)) {
                $check = $conn->prepare("SELECT name FROM food WHERE name=:name");
                $check->bindParam(':name', $name, PDO::PARAM_STR);
                $check->execute();
                $count = $check->rowCount();

                if ($count > 0) {
                    $_SESSION['error'] = "ชื่ออาหารซํ้ากัน";
                    header("location: admin-food.php");
                    exit;
                }else if (strpos($name, ' ') !== false){
                    $_SESSION['error'] = 'กรุณากรอกชื่ออาหารโดยไม่มีช่องว่าง';
                    header("location: admin-food.php");
                    exit; 
                } else {
                    $sql = $conn->prepare("INSERT INTO food (name, type, price, img) VALUES(:name, :type, :price, :img)");
                    $sql->bindParam(":name", $name, PDO::PARAM_STR);
                    $sql->bindParam(":type", $type, PDO::PARAM_STR);
                    $sql->bindParam(":price", $price, PDO::PARAM_STR);
                    $sql->bindParam(":img", $fileNew, PDO::PARAM_STR);
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
                        header("location: admin-food.php");
                        exit;
                    } else {
                        $_SESSION['error'] = "ไม่สามารถบันทึกข้อมูลได้";
                        header("location: admin-food.php");
                        exit;
                    }
                }
            }
        }
    }
}
