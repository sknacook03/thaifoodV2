<?php
session_start();
require_once("../config.php");

if(isset($_GET['delete_comment'])){
    $delete_id = $_GET['delete_comment'];
    
    if(is_numeric($delete_id)) {
        $deletestmt = $conn->prepare("DELETE FROM review WHERE idReview = :delete_id");
        $deletestmt->bindParam(':delete_id', $delete_id);
        $deletestmt->execute();

        if($deletestmt){
            echo "<script>alert('Data has been deleted successfully');</script>";
            header("refresh:1; url=userreview.php");
        }
    } else {
            echo "<script>alert('Invalid comment ID');</script>";
            ("refresh:1; url=userreview.php");
        }
    }
?>
