<?php
session_start();
require_once("../config.php");
if (!isset($_SESSION['user_login'])) {
  $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
  header('location:../login.php');
  exit;
}
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
              <li><a href="userfavorite.php">Favorite</a></li>
              <!-- <li><a></a></li> -->
              <li><a href="userprofile.php"><?php echo  $row['firstname'] ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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

          <!-- ***** Banner Start ***** -->
          <div class="main-banner">
            <div class="row">
              <div class="col-lg-7">
                <div class="header-text">
                  <h6>Welcome To THAI FOOD</h6>
                  <h4><em>เว็บไซต์</em> รวมเมนูอาหารต่างๆภายในร้าน</h4>
                  <div class="main-button">
                    <a href="userfood.php">คลิกที่นี่</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ***** Banner End ***** -->

          <!-- ***** Most Popular Start ***** -->
          <div class="most-popular">
            <div class="row">
              <div class="col-lg-12">
                <div class="heading-section">
                  <h4><em>เมนูแนะนำ</em> วันนี้</h4>
                </div>
                <div class="row">
                  <?php
                  $itemsPerPage = 8;
                  $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  $offset = ($currentPage - 1) * $itemsPerPage;

                  $stmt = $conn->prepare("
                                        SELECT food.id, food.name, food.img, food.price, type.typeName, 
                                        IF(user_favorites.food_id IS NOT NULL, 1, 0) AS is_favorite
                                        FROM food 
                                        LEFT JOIN user_favorites ON food.id = user_favorites.food_id AND user_favorites.user_id = :user_id
                                        JOIN type ON food.type = type.typeID
                                        LIMIT :offset, :itemsPerPage
                                    ");
                  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                  $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
                  $stmt->execute();
                  $foods = $stmt->fetchAll();

                  if (!$foods) {
                    echo "<div class='col-lg-12 text-center' style='color: white;'>ไม่พบอาหาร</div>";
                  } else {
                    foreach ($foods as $food) {
                  ?>
                      <div class="col-lg-3 col-sm-6">
                        <div class="item">
                          <img class="zoom" src="../food/uploads/<?= htmlspecialchars($food['img']); ?>" alt="">
                          <h4><?= htmlspecialchars($food['name']); ?><br><span>ประเภท : <?= htmlspecialchars($food['typeName']); ?></span></h4>
                          <ul>
                            <li>
                              <i class="fa <?= $food['is_favorite'] ? 'fa-star' : 'fa-star-o'; ?> star-toggle"
                                data-food-id="<?= $food['id']; ?>"
                                data-user-id="<?= $user_id; ?>"
                                style="cursor:pointer"></i>
                              <?= $food['price']; ?> .-
                            </li>
                          </ul>
                        </div>
                      </div>
                  <?php
                    }
                  }
                  ?>
                  <div class="col-lg-12">
                    <div class="main-button">
                      <a href="userfood.php">Discover Popular</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ***** Most Popular End ***** -->


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
  <script src="../assets/js/star.js"></script>
  <script src="../assets/js/isotope.min.js"></script>
  <script src="../assets/js/owl-carousel.js"></script>
  <script src="../assets/js/tabs.js"></script>
  <script src="../assets/js/popup.js"></script>
  <script src="../assets/js/custom.js"></script>


</body>

</html>