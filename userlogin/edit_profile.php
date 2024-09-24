<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
    header('location:../login.php');
    exit;
}

if (isset($_POST['update'])) {
    $userID = $_POST['userID'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $number = $_POST['number'];

    $check = $conn->prepare("SELECT email FROM users WHERE email = :email AND userID != :userID");
    $check->bindParam(':email', $email);
    $check->bindParam(':userID', $userID);
    $check->execute();
    $count = $check->rowCount();

    if ($count > 0) {
        $_SESSION['error'] = "อีเมลนี้มีผู้ใช้แล้ว";
        header("location: edit_profile.php");
        exit;
    }
    if (strpos($firstname, ' ') !== false) {
        $_SESSION['error'] = 'กรุณากรอกชื่อโดยไม่มีช่องว่าง';
        header("location: edit_profile.php");
        exit;
    } else if (strpos($lastname, ' ') !== false) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุลโดยไม่มีช่องว่าง';
        header("location: edit_profile.php");
        exit;
    } else if (strpos($email, ' ') !== false) {
        $_SESSION['error'] = 'กรุณากรอกอีเมลล์โดยไม่มีช่องว่าง';
        header("location: edit_profile.php");
        exit;
    } else if (strpos($number, ' ') !== false) {
        $_SESSION['error'] = 'กรุณากรอกหมายเลขโดยไม่มีช่องว่าง';
        header("location: edit_profile.php");
        exit;
    } else if (!preg_match("/^[a-zA-Zก-๏เ\s]+$/u", $firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อเป็นภาษาไทยหรืออังกฤษเท่านั้น';
        header("location: edit_profile.php");
        exit;
    } else if (!preg_match("/^[a-zA-Zก-๏เ\s]+$/u", $lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุลเป็นภาษาไทยหรืออังกฤษเท่านั้น';
        header("location: edit_profile.php");
        exit;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("location: edit_profile.php");
        exit;
    }

    $sql = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, number = :number WHERE userID = :userID");
    $sql->bindParam(":userID", $userID);
    $sql->bindParam(":firstname", $firstname);
    $sql->bindParam(":lastname", $lastname);
    $sql->bindParam(":email", $email);
    $sql->bindParam(":number", $number);
    $sql->execute();
    if ($sql) {
        $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
        header("location: userprofile.php");
        exit;
    } else {
        $_SESSION['error'] = "ไม่สามารถบันทึกข้อมูลได้: ";
        header("location: edit_profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/title-logo.jpg.png" />
    <title>Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/thaifood.css">
    <link rel="stylesheet" href="../assets/css/user_profile.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

</head>

<body>
    <?php
    if (isset($_SESSION['user_login'])) {
        $user_id = $_SESSION['user_login'];
        $stmt = $conn->query("SELECT * FROM users WHERE userID = $user_id");
        $stmt->execute();
        $row =  $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="userIndex.php" class="logo">
                            <img src="../assets/images/logo.png" alt="">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Search End ***** -->
                        <div class="search-input">
                            <form id="search" action="userSearch.php" method="POST">
                                <input type="text" placeholder="Type Something" id='searchText' name="search" oninput="searchFood()" />
                                <i class="fa fa-search"></i>
                            </form>
                        </div>
                        <!-- ***** Search End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="userindex.php" class="active">หน้าแรก</a></li>
                            <li><a href="userfood.php">อาหาร</a></li>
                            <li><a href="userdrink.php">เครื่องดื่ม</a></li>
                            <!-- <li><a href="streams.html">โปรโมชั่น</a></li> -->
                            <li><a href="userreview.php">รีวิวลูกค้า</a></li>
                            <!-- <li><a></a></li> -->
                            <li><a><?php echo  $row['firstname'] ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
                        </ul>
                        <a href="../logout.php" class="btn btn-danger mb-5 mt-1">logout</a>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
                    <div class="user-profile">
                        <h1 class="mb-3">Edit Profile</h1>
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php } ?>
                        <form action="edit_profile.php" method="POST">
                            <input type="text" class="form-control" name="userID" value="<?php echo $row['userID']; ?>" hidden required>
                            <div class="form-groupedit">
                                <label for="firstname">Firstname :</label>
                                <input type="text" class="form-control" name="firstname" value="<?php echo $row['firstname']; ?>" required>
                            </div>
                            <div class="form-groupedit">
                                <label for="lastname">Lastname :</label>
                                <input type="text" class="form-control" name="lastname" value="<?php echo $row['lastname']; ?>" required>
                            </div>
                            <div class="form-groupedit">
                                <label for="email">Email :</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                            </div>
                            <div class="form-groupedit">
                                <label for="number">Tel. :</label>
                                <input type="text" class="form-control" name="number" value="<?php echo $row['number']; ?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary mt-5">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>© 2024 <a href="#">Thai Food</a> Company. All rights reserved.
                </div>
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="../assets/js/isotope.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/tabs.js"></script>
    <script src="../assets/js/popup.js"></script>
    <script src="../assets/js/custom.js"></script>


</body>

</html>