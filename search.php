<?php
session_start();
require_once("config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/title-logo.jpg.png" />
    <title>ThaiFood</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/thaifood.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
</head>

<body>

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
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo.png" alt="">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Search End ***** -->
                        <div class="search-input">
                            <form id="search" action="search.php" method="POST">
                                <input type="text" placeholder="Type Something" id='searchText' name="searchfood" oninput="searchFood()" onkeypress="handle" />
                                <i class="fa fa-search"></i>
                            </form>
                        </div>
                        <!-- ***** Search End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="index.php">หน้าแรก</a></li>
                            <li><a href="food.php">อาหาร</a></li>
                            <li><a href="drink.php">เครื่องดื่ม</a></li>
                            <!-- <li><a href="streams.html">โปรโมชั่น</a></li> -->
                            <li><a href="review.php">รีวิวลูกค้า</a></li>
                            <!-- <li><a href="info.html">ติดต่อเรา</a></li> -->
                            <li><a href="login.php">Login <img src="assets/images/profile-header.jpg" alt=""></a></li>
                        </ul>
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

                    <!-- ***** Most Popular Start ***** -->
                    <div class="most-popular">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                if (isset($_POST['searchfood'])) {
                                    $searchFood = $_POST['searchfood'];
                                    // Search for food in the database
                                    $stmt = $conn->prepare("SELECT * FROM food JOIN type ON food.type = type.typeID WHERE name LIKE ?");
                                    $stmt->execute(["%$searchFood%"]);
                                    $foods = $stmt->fetchAll();
                                    if (!$foods) {
                                        echo "<h3>ไม่พบข้อมูล</h3>";
                                    } else {
                                        echo '<div class="heading-section">
                                            <h4><em>ประเภท' . $foods[0]['typeName'] . '</em></h4>
                                            </div>';
                                ?>
                                        <div class="row text-white">
                                            <?php
                                            foreach ($foods as $food) {
                                            ?>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="item">
                                                        <img class="zoom" src="food/uploads/<?= $food['img']; ?>" alt="">
                                                        <h4><?= $food['name']; ?><br><span>ประเภท : <?= $food['typeName']; ?></span></h4>
                                                        <ul>
                                                            <li><i class="fa fa-star"></i> <?= $food['price']; ?> .-</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                } elseif (isset($_POST['searchdrink'])) {
                                    // Check if searching for drinks
                                    $searchDrink = $_POST['searchdrink'];
                                    // Search for drinks in the database
                                    $stmt = $conn->prepare("SELECT * FROM drink JOIN type ON drink.type = type.typeID WHERE name LIKE ?");
                                    $stmt->execute(["%$searchDrink%"]);
                                    $drinks = $stmt->fetchAll();
                                    if (!$drinks) {
                                        echo "<h3>ไม่พบข้อมูล</h3>";
                                    } else {
                                        echo '<div class="heading-section">
                                            <h4><em>ประเภท' . $drinks[0]['typeName'] . '</em></h4>
                                            </div>';
                                    ?>
                                        <div class="row text-white">
                                            <?php
                                            foreach ($drinks as $drink) {
                                            ?>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div class="item">
                                                        <img class="zoom" src="drink/uploads/<?= $drink['img']; ?>" alt="">
                                                        <h4><?= $drink['name']; ?><br><span>ประเภท : <?= $drink['typeName']; ?></span></h4>
                                                        <ul>
                                                            <li><i class="fa fa-star"></i> <?= $drink['price']; ?> .-</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                } elseif (isset($_POST['search'])) {
                                    $searchTerm = $_POST['search'];
                                    // Search for food in the database
                                    $stmtFood = $conn->prepare("SELECT * FROM food JOIN type ON food.type = type.typeID WHERE name LIKE ?");
                                    $stmtFood->execute(["%$searchTerm%"]);
                                    $foods = $stmtFood->fetchAll();
                                    // Search for drinks in the database
                                    $stmtDrink = $conn->prepare("SELECT * FROM drink JOIN type ON drink.type = type.typeID WHERE name LIKE ?");
                                    $stmtDrink->execute(["%$searchTerm%"]);
                                    $drinks = $stmtDrink->fetchAll();
                                    // Display search results
                                    if (!$foods && !$drinks) {
                                        echo "<h3>ไม่พบข้อมูล</h3>";
                                    } else {
                                        if ($foods) {
                                            echo '<div class="heading-section">
                                                <h4><em>อาหาร</em></h4>
                                                </div>';
                                        ?>
                                            <div class="row text-white">
                                                <?php
                                                foreach ($foods as $food) {
                                                ?>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="item">
                                                            <img class="zoom" src="food/uploads/<?= $food['img']; ?>" alt="">
                                                            <h4><?= $food['name']; ?><br><span>ประเภท : <?= $food['typeName']; ?></span></h4>
                                                            <ul>
                                                                <li><i class="fa fa-star"></i> <?= $food['price']; ?> .-</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        if ($drinks) {
                                            echo '<div class="heading-section">
                                                <h4><em>เครื่องดื่ม</em></h4>
                                                </div>';
                                        ?>
                                            <div class="row text-white">
                                                <?php
                                                foreach ($drinks as $drink) {
                                                ?>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="item">
                                                            <img class="zoom" src="drink/uploads/<?= $drink['img']; ?>" alt="">
                                                            <h4><?= $drink['name']; ?><br><span>ประเภท : <?= $drink['typeName']; ?></span></h4>
                                                            <ul>
                                                                <li><i class="fa fa-star"></i> <?= $drink['price']; ?> .-</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>


                    <!-- ***** Most Popular End ***** -->


                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright © 2036 <a href="#">THAI FOOD</a> Company. All rights reserved.


                    </div>
                </div>
            </div>
        </footer>


        <!-- Scripts -->
        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/rolling-back.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/owl-carousel.js"></script>
        <script src="assets/js/tabs.js"></script>
        <script src="assets/js/popup.js"></script>
        <script src="assets/js/custom.js"></script>


</body>

</html>