<?php
session_start();
require_once("config.php");
if (!isset($_SESSION['admin_login'])) {
  $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
  header('location:login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="assets/images/title-logo.jpg.png" />
  <title>ThaiFood-ADMIN</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/thaifood.css">
  <link rel="stylesheet" href="assets/css/owl.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
</head>

<body>
  <?php
  if (isset($_SESSION['admin_login'])) {
    $user_id = $_SESSION['admin_login'];
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
            <a href="admin-index.php" class="logo">
              <img src="assets/images/logo.png" alt="">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Search End ***** -->
            <div class="search-input">
              <form id="search" action="#">
                <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" onkeypress="handle" />
                <i class="fa fa-search"></i>
              </form>
            </div>
            <!-- ***** Search End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li><a href="admin-index.php" class="active">หน้าแรก</a></li>
              <li><a href="food/admin-food.php">อาหาร</a></li>
              <li><a href="drink/admin-drink.php">เครื่องดื่ม</a></li>
              <li><a href="review/admin-review.php">รีวิวลูกค้า</a></li>
              <li><a><?php echo $row['firstname'] ?> <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
            <a href="logout.php" class="btn btn-danger mb-5 mt-1">logout</a>
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
        <div class="page-content" id="searchResults">
          <?php
          $countStmt = $conn->prepare("SELECT COUNT(userID) AS total FROM users");
          $countStmt->execute();
          $totalUsers = $countStmt->fetchColumn();

          echo "<h2>Welcome, " . $row['firstname'] . "</h2>";

          echo "<h6 class=\"mb-4\" style=\"color: #999;\">ตอนนี้มีจำนวน User ทั้งหมด " . $totalUsers . " ไอดี</h6>";
          echo "<table class=\"table table-dark table-striped\">";
          echo "<thead>";
          echo "<tr>";
          echo "<th scope=\"col\">userID</th>";
          echo "<th scope=\"col\">ชื่อ</th>";
          echo "<th scope=\"col\">นามสกุล</th>";
          echo "<th scope=\"col\">ตัวเลือก</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          $itemsPerPage = 5;
          $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
          $offset = ($currentPage - 1) * $itemsPerPage;

          $stmt = $conn->prepare("SELECT * FROM users ORDER BY userID LIMIT :offset, :itemsPerPage");
          $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
          $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
          $stmt->execute();
          $users = $stmt->fetchAll();

          if (empty($users)) {
            echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
          } else {
            foreach ($users as $user) {
              echo "<tr>";
              echo "<th scope=\"row\">" . $user['userID'] . "</th>";
              echo "<td>" . $user['firstname'] . "</td>";
              echo "<td>" . $user['lastname'] . "</td>";
              echo "<td>" . "<a href=\"view.php?userID=" . $user['userID'] . "\" class=\"btn btn-warning\">info</a>" . "</td>";
              echo "</tr>";
            }
          }

          echo "</tbody>";
          echo "</table>";

          $totalPages = ceil($totalUsers / $itemsPerPage);

          $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
          $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
          $startPage = max(1, $currentPage - 1);
          $endPage = min($totalPages, $currentPage + 1);

          if ($currentPage == 1) {
            $endPage = min($totalPages, $startPage + 2);
          }

          if ($currentPage == $totalPages) {
            $startPage = max(1, $endPage - 2);
          }

          echo "<div class=\"pagination\">";
          echo "<a class='prev-next' href='?page=$prevPage'>&laquo; ก่อนหน้า</a>";

          for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($currentPage == $i) ? 'active' : '';
            echo "<a class='$activeClass' href='?page=$i'>$i</a>";
          }

          echo "<a class='prev-next' href='?page=$nextPage'>ถัดไป &raquo;</a>";
          echo "</div>";
          ?>
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
  <script>
    $(document).ready(function() {
      $('#searchText').on('input', function() {
        var searchText = $(this).val();

        $.ajax({
          url: 'searchAdmin.php',
          type: 'POST',
          dataType: 'json',
          data: {
            searchKeyword: searchText
          },
          success: function(response) {
            // เรียกใช้ฟังก์ชั่นสำหรับแสดงผลลัพธ์
            displayResults(response);
          }
        });
      });
    });

    // ฟังก์ชั่นสำหรับแสดงผลลัพธ์ค้นหา
    function displayResults(results) {
      var searchResultsContainer = $('#searchResults');
      searchResultsContainer.empty(); // เคลียร์ข้อมูลเดิมทุกครั้งที่มีการค้นหาใหม่

      if (results.length === 0) {
        searchResultsContainer.append('<h4>ไม่พบข้อมูล</h4>');
      } else {
        var tableHead =
          "<table class=\"table table-dark table-striped\">" +
          "<thead>" +
          "<tr>" +
          "<th scope=\"col\">userID</th>" +
          "<th scope=\"col\">ชื่อ</th>" +
          "<th scope=\"col\">นามสกุล</th>" +
          "<th scope=\"col\">ตัวเลือก</th>" +
          "</tr>" +
          "</thead>" +
          "<tbody>";

        var tableBody = '';
        results.forEach(function(user) {
          tableBody += "<tr>" +
   "<th scope=\"row\">" + user['userID'] + "</th>" +
   "<td>" + user['firstname'] + "</td>" +
   "<td>" + user['lastname'] + "</td>" +
   "<td>" + "<a href=\"view.php?userID=" + user['userID'] + "\" class=\"btn btn-warning\">info</a>" + "</td>" +
   "</tr>";
        });

        var tableEnd = '</tbody>' +
          '</table>';

        searchResultsContainer.append(tableHead + tableBody + tableEnd);
      }
    }
  </script>

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