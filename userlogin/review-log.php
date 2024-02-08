<?php
    session_start();
    require_once("../config.php");

    if(isset($_POST['post'])){
        $comment = $_POST['comment'];
        $user_id = $_SESSION['user_login'];
        date_default_timezone_set('Asia/Bangkok');
        $date = date('Y-m-d H:i:s');
        if(empty($comment)){
            $_SESSION['error'] = 'กรุณากรอกข้อความ';
            header("location:userreview.php");
            exit;
        } else {
        $pcm = $conn->prepare("INSERT INTO review (userID, comment, date) VALUES (:userID, :comment, :date)");
        $pcm->bindParam(":userID", $user_id, PDO::PARAM_INT);
        $pcm->bindParam(":comment", $comment, PDO::PARAM_STR);
        $pcm->bindParam(":date", $date, PDO::PARAM_STR);
        $pcm->execute();
            if($pcm){
                header("location:userreview.php");
                exit;
            } else {
                $_SESSION['error'] = "ไม่สามารถบันทึกข้อมูลได้";
                header("location:userreview.php");
                exit;
            }
        }
    }else if(isset($_POST['cancel'])){
        header("location:userreview.php");
        exit;
    }

?>
