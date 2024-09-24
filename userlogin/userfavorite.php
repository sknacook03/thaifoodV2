<?php
session_start();
require_once("../config.php");
if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
    header('location:../login.php');
    exit;
}

$user_id = $_SESSION['user_login'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/title-logo.jpg.png" />
    <title>ThaiFood</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/thaifood.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    if (isset($user_id)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE userID = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
                                <input type="text" placeholder="Type Something" id='searchText' name="searchfood" oninput="searchFood()" />
                                <i class="fa fa-search"></i>
                            </form>
                        </div>
                        <!-- ***** Search End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="userindex.php">หน้าแรก</a></li>
                            <li><a href="userfood.php">อาหาร</a></li>
                            <li><a href="userdrink.php">เครื่องดื่ม</a></li>
                            <li><a href="userreview.php">รีวิวลูกค้า</a></li>
                            <li><a href="userfavorite.php" class="active">Favorite</a></li>
                            <li><a href="userprofile.php"><?php echo htmlspecialchars($row['firstname']) ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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
                    <div class="most-popular">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="heading-section">
                                    <h4><em>Favorite Foods</em></h4>
                                </div>
                                <div class="row text-white">
                                    <?php
                                    $itemsPerPage = 8;
                                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $offset = ($currentPage - 1) * $itemsPerPage;

                                    // แก้ไขคำสั่ง SQL เพื่อดึงข้อมูลของอาหารจาก user_favorites
                                    $stmt = $conn->prepare("
                                        SELECT food.id, food.name, food.img, food.price, type.typeName 
                                        FROM user_favorites 
                                        JOIN food ON user_favorites.food_id = food.id 
                                        JOIN type ON food.type = type.typeID 
                                        WHERE user_favorites.user_id = :user_id
                                        LIMIT :offset, :itemsPerPage
                                    ");
                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                                    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $foods = $stmt->fetchAll();

                                    if (!$foods) {
                                        echo "<div class='col-lg-12 text-center' style='color: white;'>ไม่พบอาหารที่ชอบ</div>";
                                    } else {
                                        foreach ($foods as $food) {
                                    ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <div class="item">
                                                    <img class="zoom" src="../food/uploads/<?= htmlspecialchars($food['img']); ?>" alt="">
                                                    <h4><?= htmlspecialchars($food['name']); ?><br><span>ประเภท : <?= htmlspecialchars($food['typeName']); ?></span></h4>
                                                    <ul>
                                                        <li><i class="fa fa-star star-toggle" data-food-id="<?= htmlspecialchars($food['id']); ?>" data-user-id="<?= htmlspecialchars($user_id); ?>" style="cursor:pointer"></i> <?= htmlspecialchars($food['price']); ?> .-</li>
                                                    </ul>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-lg-12">
                                    <div class="main-button">
                                        <a href="#top">กลับไปด้านบน</a>
                                    </div>
                                </div>
                                <div class="pagination">
                                    <?php
                                    // คำนวณจำนวนรายการทั้งหมด
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM user_favorites WHERE user_id = :user_id");
                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalItems = $row['total'];
                                    $totalPages = ceil($totalItems / $itemsPerPage);

                                    $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
                                    $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;

                                    $startPage = max(1, $currentPage - 1);
                                    $endPage = min($totalPages, $currentPage + 1);

                                    echo "<a class='prev-next' href='?page=$prevPage'>&laquo; ก่อนหน้า</a>";

                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        $activeClass = ($currentPage == $i) ? 'active' : '';
                                        echo "<a class='$activeClass' href='?page=$i'>$i</a>";
                                    }

                                    echo "<a class='prev-next' href='?page=$nextPage'>ถัดไป &raquo;</a>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="most-popular">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="heading-section">
                                    <h4><em>Favorite Drinks</em></h4>
                                </div>
                                <div class="row text-white">
                                    <?php
                                    $itemsPerPage = 8;
                                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $offset = ($currentPage - 1) * $itemsPerPage;

                                    // แก้ไขคำสั่ง SQL เพื่อดึงข้อมูลของเครื่องดื่มจาก user_favorites_drink
                                    $stmt = $conn->prepare("
                                       SELECT drink.id, drink.name, drink.img, drink.price, type.typeName 
                                       FROM user_favorites_drink 
                                       JOIN drink ON user_favorites_drink.drink_id = drink.id 
                                       JOIN type ON drink.type = type.typeID 
                                       WHERE user_favorites_drink.user_id = :user_id
                                       LIMIT :offset, :itemsPerPage
                                   ");
                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                                    $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $drinks = $stmt->fetchAll();

                                    if (!$drinks) {
                                        echo "<div class='col-lg-12 text-center' style='color: white;'>ไม่พบเครื่องดื่มที่ชอบ</div>";
                                    } else {
                                        foreach ($drinks as $drink) {
                                    ?>
                                            <div class="col-lg-3 col-sm-6">
                                                <div class="item">
                                                    <img class="zoom" src="../drink/uploads/<?= htmlspecialchars($drink['img']); ?>" alt="">
                                                    <h4><?= htmlspecialchars($drink['name']); ?><br><span>ประเภท : <?= htmlspecialchars($drink['typeName']); ?></span></h4>
                                                    <ul>
                                                        <li><i class="fa fa-star star-toggle" data-drink-id="<?= htmlspecialchars($drink['id']); ?>" data-user-id="<?= htmlspecialchars($user_id); ?>" style="cursor:pointer"></i> <?= htmlspecialchars($drink['price']); ?> .-</li>
                                                    </ul>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-lg-12">
                                    <div class="main-button">
                                        <a href="#top">กลับไปด้านบน</a>
                                    </div>
                                </div>
                                <div class="pagination">
                                    <?php
                                    // คำนวณจำนวนรายการทั้งหมด
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM user_favorites_drink WHERE user_id = :user_id");
                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $totalItems = $row['total'];
                                    $totalPages = ceil($totalItems / $itemsPerPage);

                                    $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
                                    $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;

                                    $startPage = max(1, $currentPage - 1);
                                    $endPage = min($totalPages, $currentPage + 1);

                                    echo "<a class='prev-next' href='?page=$prevPage'>&laquo; ก่อนหน้า</a>";

                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        $activeClass = ($currentPage == $i) ? 'active' : '';
                                        echo "<a class='$activeClass' href='?page=$i'>$i</a>";
                                    }

                                    echo "<a class='prev-next' href='?page=$nextPage'>ถัดไป &raquo;</a>";
                                    ?>
                                </div>
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
                        <p>© 2024 <a href="#">THAI FOOD</a> Company. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <!-- Bootstrap core JavaScript -->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/js/rolling-back.js"></script>
        <script src="../assets/js/star.js"></script>
        <script src="../assets/js/isotope.min.js"></script>
        <script src="../assets/js/owl-carousel.js"></script>
        <script src="../assets/js/tabs.js"></script>
        <script src="../assets/js/popup.js"></script>
        <script src="../assets/js/custom.js"></script>

</body>

</html>