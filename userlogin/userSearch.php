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
              <li><a href="userindex.php">หน้าแรก</a></li>
              <li><a href="userfood.php">อาหาร</a></li>
              <li><a href="userdrink.php">เครื่องดื่ม</a></li>
              <!-- <li><a href="streams.html">โปรโมชั่น</a></li> -->
              <li><a href="userreview.php">รีวิวลูกค้า</a></li>
              <li><a href="userfavorite.php">Favorite</a></li>
              <!-- <li><a href="info.html">ติดต่อเรา</a></li> -->
              <li><a href="userprofile.php"><?php echo $row['firstname'] ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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
          <!-- ***** Most Popular Start ***** -->
          <div class="most-popular mt-0">
            <div class="row">
              <div class="col-lg-12">
                <?php
                if (isset($_POST['searchfood']) || isset($_POST['searchdrink']) || isset($_POST['search'])) {
                  // ตั้งค่าคำค้นหาตามแต่ละกรณี
                  $searchTerm = isset($_POST['searchfood']) ? $_POST['searchfood'] : (isset($_POST['searchdrink']) ? $_POST['searchdrink'] : $_POST['search']);

                  // ค้นหาข้อมูลอาหาร
                  $stmtFood = $conn->prepare("
                  SELECT food.id, food.name, food.img, food.price, type.typeName,
                  IF(user_favorites.food_id IS NOT NULL, 1, 0) AS is_favorite
                  FROM food 
                  JOIN type ON food.type = type.typeID
                  LEFT JOIN user_favorites ON food.id = user_favorites.food_id AND user_favorites.user_id = ?
                  WHERE food.name LIKE ? OR type.typeName LIKE ?
              ");
                  $stmtFood->execute([$user_id, "%$searchTerm%", "%$searchTerm%"]);
                  $foods = $stmtFood->fetchAll();

                  // ค้นหาข้อมูลเครื่องดื่ม
                  $stmtDrink = $conn->prepare("
                  SELECT drink.id, drink.name, drink.img, drink.price, type.typeName,
                  IF(user_favorites_drink.drink_id IS NOT NULL, 1, 0) AS is_favorite 
                  FROM drink 
                  JOIN type ON drink.type = type.typeID 
                  LEFT JOIN user_favorites_drink ON drink.id = user_favorites_drink.drink_id AND user_favorites_drink.user_id = ?
                  WHERE drink.name LIKE ? OR type.typeName LIKE ?
              ");
                  $stmtDrink->execute([$user_id, "%$searchTerm%", "%$searchTerm%"]);
                  $drinks = $stmtDrink->fetchAll();

                  // รวมจำนวนอาหารและเครื่องดื่มที่พบ
                  $totalFoods = count($foods);
                  $totalDrinks = count($drinks);
                  $totalItems = $totalFoods + $totalDrinks;

                  echo "<h6 class=\"mb-4\" style=\"color: #666;\">ค้นพบ " . $totalItems . " รายการ</h6>";

                  // ฟังก์ชันสำหรับแสดงรายการอาหาร/เครื่องดื่ม
                  function displayItems($items, $title, $type, $user_id)
                  {
                    if ($items) {
                      echo '<div class="heading-section"><h4><em>' . $title . '</em></h4></div>';
                      echo '<div class="row text-white">';
                      foreach ($items as $item) {
                        $isFavorite = isset($item['is_favorite']) && $item['is_favorite'] ? 'fa-star' : 'fa-star-o';
                        echo '<div class="col-lg-3 col-sm-6">
                                    <div class="item">
                                        <img class="zoom" src="../' . $type . '/uploads/' . $item['img'] . '" alt="">
                                        <h4>' . $item['name'] . '<br><span>ประเภท : ' . $item['typeName'] . '</span></h4>
                                        <ul>
                                          <li>
                                            <i class="fa ' . $isFavorite . ' star-toggle"
                                              data-'. $type . '-id="' . $item['id'] . '"
                                              data-user-id="' . $user_id . '"
                                              style="cursor:pointer"></i>
                                            ' . $item['price'] . ' .-
                                          </li>
                                        </ul>
                                    </div>
                                  </div>';
                      }
                      echo '</div>';
                    }
                  }

                  // แสดงรายการอาหาร
                  displayItems($foods, 'อาหาร', 'food', $user_id);

                  // แสดงรายการเครื่องดื่ม
                  displayItems($drinks, 'เครื่องดื่ม', 'drink', $user_id);

                  // กรณีที่ไม่พบข้อมูล
                  if (!$foods && !$drinks) {
                    echo "<h3>ไม่พบข้อมูล</h3>";
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


</body>../

</html>