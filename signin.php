<?php
session_start();
require_once("config.php");

if (isset($_POST['signin'])) {
    $email = $_POST['email_l'];
    $password = $_POST['password_l'];

    if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        $_SESSION['input_value'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        $_SESSION['input_value'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        $_SESSION['input_value'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
    } else if (strlen($_POST['password_l']) < 5) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวมากกว่า 5 ตัวอักษร';
        $_SESSION['input_value'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
    }else if (strpos($password, ' ') !== false){
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่านโดยไม่มีช่องว่าง';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location: login.php");
        exit; 
    } else {
        try {
            $check_data = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $check_data->bindParam(":email", $email);
            $check_data->execute();
            $row =  $check_data->fetch(PDO::FETCH_ASSOC);
            if ($check_data->rowCount() > 0) {
                if ($email == $row['email']) {
                    if (password_verify($password, $row['password'])) {
                        if ($row['role'] == 'admin') {
                            $_SESSION['admin_login'] = $row['userID'];
                            unset($_SESSION['input_value']);
                            header("location:admin-index.php");
                            exit;
                        } else {
                            $_SESSION['user_login'] = $row['userID'];
                            unset($_SESSION['input_value']);
                            header("location:userlogin/userIndex.php");
                            exit;
                        }
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านผิด';
                        $_SESSION['input_value'] = [
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'email' => $email,
                            'number' => $number,
                        ];
                        header("location:login.php");
                        exit;
                    }
                } else {
                    $_SESSION['error'] = 'อีเมลผิด';
                    header("location:login.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "ไม่มีข้อมูลในระบบ!";
                $_SESSION['input_value'] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'number' => $number,
                ];
                header("location:login.php");
                exit;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
