<?php
session_start();
require_once("config.php");


if (isset($_POST['signup'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $password = $_POST['password'];
    $role = 'user';
    
    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strpos($firstname, ' ') !== false){
        $_SESSION['error'] = 'กรุณากรอกชื่อโดยไม่มีช่องว่าง';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location: login.php");
        exit; 
    } else if (!ctype_alpha($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อเป็นตัวอักษรภาษาอังกฤษ';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (empty($lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุล';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strpos($lastname, ' ') !== false){
        $_SESSION['error'] = 'กรุณากรอกนามสกุลโดยไม่มีช่องว่าง';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location: login.php");
        exit; 
    } else if (!ctype_alpha($lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุลเป็นตัวอักษรภาษาอังกฤษ';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (empty($number)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์โทรศัพท์';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strpos($number, '0') !== 0) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์โทรศัพท์โดยมีเลข 0 นำหน้า';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (!is_numeric($number)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์โทรศัพท์เป็นตัวเลข';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strpos($number, ' ') !== false){
        $_SESSION['error'] = 'กรุณากรอกเบอร์โดยไม่มีช่องว่าง';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location: login.php");
        exit; 
    } else if (strlen($_POST['number']) != 10) {
        $_SESSION['error'] = 'กรอกเบอร์โทรศัพท์ให้ครบ 10 ต้ว';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strlen($_POST['password']) < 5) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวมากกว่า 5 ตัวอักษร';
        $_SESSION['input_values'] = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'number' => $number,
        ];
        header("location:login.php");
        exit;
    } else if (strpos($password, ' ') !== false){
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
            $check_email = $conn->prepare("SELECT * FROM users WHERE email = :email OR number = :number");
            $check_email->bindParam(":email", $email);
            $check_email->bindParam(":number", $number);
            $check_email->execute();
            $row =  $check_email->fetch(PDO::FETCH_ASSOC);
            if ($row['email'] == $email) {
                $_SESSION['warning'] = 'อีเมลนี้ถูกใช้งานแล้ว';
                $_SESSION['input_values'] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'number' => $number,
                ];
                header("location:login.php");
            } else if ($row['number'] == $number) {
                $_SESSION['warning'] = 'เบอร์นี้ถูกใช้งานแล้ว';
                $_SESSION['input_values'] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'number' => $number,
                ];
                header("location:login.php");
            } else if (!isset($_POST['error'])) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users(firstname, lastname, email, number, password, role)
                                            VALUES(:firstname, :lastname, :email, :number, :password, :role)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":number", $number);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":role", $role);
                $stmt->execute();
                $_SESSION['success'] = "สมัครสมาชิคเรียบร้อยแล้ว!";
                unset($_SESSION['input_values']);
                header("location:login.php");
            } else {
                $_SESSION['error'] = "มีบางอย่างผิดพลาด!";
                header("location:login.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
