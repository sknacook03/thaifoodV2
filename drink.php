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
                <input type="text" placeholder="Type Something" id='searchText' name="searchdrink" oninput="searchFood()" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <!-- ***** Search End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li><a href="index.php">หน้าแรก</a></li>
              <li><a href="food.php">อาหาร</a></li>
              <li><a href="drink.php" class="active">เครื่องดื่ม</a></li>
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

          <!-- ***** Featured Games Start ***** -->
          <div class="row">
            <div class="col-lg-8">
              <div class="featured-games header-text">
                <div class="heading-section" id="js-menu">
                  <h4><em>แนะนำ</em> เครื่องดื่ม</h4>
                </div>
                <div class="owl-features owl-carousel">
                  <?php

                  $stmt = $conn->query("SELECT * FROM drink JOIN type ON drink.type = type.typeID");
                  $stmt->execute();
                  $drinks = $stmt->fetchAll();
                  if (!$drinks) {
                    echo "<tr><td colspan='6' class='text-center' style='color: white;'>No drink found</td></tr>";
                  } else {
                    foreach ($drinks as $drink) {
                  ?>
                      <div class="item">
                        <div class="thumb">
                          <img src="drink/uploads/<?= $drink['img']; ?>" alt="">
                          <div class="hover-effect">
                            <h6>แนะนำ</h6>
                          </div>
                        </div>
                        <h4><?= $drink['name']; ?><br><span>ประเภท : <?= $drink['typeName']; ?></span></h4>
                        <ul>
                          <li><i class="fa fa-star"></i> <?= $drink['price']; ?> .-</li>

                        </ul>
                      </div>
                  <?php  }
                  } ?>
                </div>
              </div>
            </div>
          </div>
          <!-- ***** Featured Games End ***** -->
          <!-- ***** Most Popular Start ***** -->
          <div class="most-popular">
            <div class="row">
              <div class="col-lg-12">
                <div class="heading-section">
                  <h4><em>เครื่องดื่ม</em></h4>
                </div>
                <div class="row text-white">
                  <?php
                  $itemsPerPage = 8;
                  $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  $offset = ($currentPage - 1) * $itemsPerPage;

                  $stmt = $conn->prepare("SELECT * FROM drink JOIN type ON drink.type = type.typeID LIMIT :offset, :itemsPerPage");
                  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                  $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                  $stmt->execute();
                  $drinks = $stmt->fetchAll();

                  if (!$drinks) {
                    echo "<div class='col-lg-12 text-center' style='color: white;'>ไม่พบเครื่องดื่ม</div>";
                  } else {
                    foreach ($drinks as $drink) {
                  ?>
                      <div class="col-lg-3 col-sm-6">
                        <div class="item">
                          <img class="zoom" src="drink/uploads/<?= $drink['img']; ?>" alt="">
                          <h4><?= $drink['name']; ?><br><span>ประเภท : <?= $drink['typeName']; ?></span></h4>
                          <ul>
                            <li><i class="fa fa-star"></i> <?= $drink['price']; ?>-.</li>
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
                  $stmt = $conn->query("SELECT COUNT(*) AS total FROM drink");
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
            <p>© 2024 <a href="#">Thai Food</a> Company. All rights reserved.


          </div>
        </div>
      </div>
    </footer>


    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>


</body>

</html>