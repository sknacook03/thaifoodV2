<?php
session_start();
require_once("../config.php");

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $deletestmt = $conn->query("DELETE FROM drink WHERE id = $delete_id");
  $deletestmt->execute();

  if ($deletestmt) {
    echo "<script>alert('Data has been deleted successfully');</script>";
    $_SESSION['success'] = "'Data has been deleted successfully";
    header("refresh:1; url=admin-drink.php");
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
  <title>ThaiFood-ADMIN</title>

  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/thaifood.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
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
              <img src="../assets/images/logo.png" alt="">
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
              <li><a href="../admin-index.php">หน้าแรก</a></li>
              <li><a href="../food/admin-food.php">อาหาร</a></li>
              <li><a href="../drink/admin-drink.php" class="active">เครื่องดื่ม</a></li>
              <li><a href="../review/admin-review.php">รีวิวลูกค้า</a></li>
              <li><a><?php echo $row['firstname'] ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Food</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="insert.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="Name" class="col-form-label">ชื่ออาหาร:</label>
              <input type="text" required class="form-control" name="name">
            </div>
            <div class="mb-3">
              <label for="Name" class="col-form-label">ประเภทอาหาร:</label>
              <select type="text" required class="form-control" name="type">
                <?php
                $stmt = $conn->query("SELECT * FROM type ORDER BY typeID");
                $stmt->execute();
                $types = $stmt->fetchAll();
                if (!$types) {
                  echo "<tr><td colspan='6' class='text-center'>No type found</td></tr>";
                } else {
                  foreach ($types as $type) {
                ?>
                    <option value="<?= $type['typeID'] ?>"><?= $type['typeID'] ?> <?= $type['typeName'] ?></option>
                <?php   }
                } ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="Name" class="col-form-label">ราคา:</label>
              <input type="number" required class="form-control" name="price">
              <small id="priceHelp" class="form-text text-muted">โปรดป้อนตัวเลขเท่านั้น</small>
            </div>
            <div class="mb-3">
              <label for="img" class="col-form-label">รูปภาพ:</label>
              <input type="file" required class="form-control" id="imgInput" name="img">
              <img width="100%" id="previewImg" alt="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="submit" class="btn btn-success">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="page-content" id="searchResults">
      <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success">
          <?php
          echo $_SESSION['success'];
          unset($_SESSION['success']);
          ?>
        </div>
      <?php } ?>
      <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger">
          <?php
          echo $_SESSION['error'];
          unset($_SESSION['error']);
          ?>
        </div>
      <?php } ?>
        <div class="col-md-12 d-flex mb-3">
          <h2>Drink</h2>
          <div class="col-md-11 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add</button>
          </div>
        </div>
        <!-- Drink data -->
        <table class="table table-dark table-striped">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">ชื่อเครื่องดื่ม</th>
              <th scope="col">ประเภทเครื่องดื่ม</th>
              <th scope="col">ราคา</th>
              <th scope="col">รูปภาพ</th>
              <th scope="col">ตัวเลือก</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $itemsPerPage = 5;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($currentPage - 1) * $itemsPerPage;

            $stmt = $conn->prepare("SELECT * FROM Drink JOIN type ON Drink.type = type.typeID ORDER BY id LIMIT :offset, :itemsPerPage");
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
            $stmt->execute();
            $Drinks = $stmt->fetchAll();
            $countStmt = $conn->prepare("SELECT COUNT(id) AS total FROM drink JOIN type ON drink.type = type.typeID");
            $countStmt->execute();
            $totalDrinks = $countStmt->fetchColumn();
            echo "<h6 class=\"mb-4\" style=\"color: #666;\">มีจำนวนรายการเครื่องดื่มทั้งหมด " . $totalDrinks . " รายการ</h6>";
            if (empty($Drinks)) {
              echo "<tr><td colspan='6' class='text-center'>No Drink found</td></tr>";
            } else {
              foreach ($Drinks as $Drink) {
            ?>
                <tr>
                  <th scope="row"><?= $Drink['id']; ?></th>
                  <td><?= $Drink['name']; ?></td>
                  <td><?= $Drink['type']; ?> <?= $Drink['typeName']; ?></td>
                  <td><?= $Drink['price']; ?></td>
                  <td width="150px"><img width="150px" height="150px" style="object-fit: cover;" src="uploads/<?= $Drink['img']; ?>" class="rounded" alt=""></td>
                  <td>
                    <a href="edit.php?id=<?= $Drink['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="?delete=<?= $Drink['id']; ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการลบใช่หรือไม่?')">Delete</a>
                  </td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
        <div class="pagination">
          <?php
          $stmt = $conn->query("SELECT COUNT(*) AS total FROM Drink");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $totalItems = $row['total'];
          $totalPages = ceil($totalItems / $itemsPerPage);

          $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
          $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;

          $startPage = max(1, $currentPage - 1); // เริ่มต้นที่หน้าปัจจุบัน - 2
          $endPage = min($totalPages, $currentPage + 1); // สิ้นสุดที่หน้าปัจจุบัน + 2

          // แสดงเพียง 3 หน้า ถ้าหน้าปัจจุบันอยู่ที่หน้าแรก
          if ($currentPage == 1) {
            $endPage = min($totalPages, $startPage + 2); // ถ้าอยู่ที่หน้าแรก แสดง 4 หน้า
          }

          // แสดงเพียง 3 หน้า ถ้าหน้าปัจจุบันอยู่ที่หน้าสุดท้าย
          if ($currentPage == $totalPages) {
            $startPage = max(1, $endPage - 2); // ถ้าอยู่ที่หน้าสุดท้าย แสดง 4 หน้า
          }
          echo "<a class='prev-next' href='?page=$prevPage'>&laquo; ก่อนหน้า</a>";

          for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($currentPage == $i) ? 'active' : '';
            echo "<a class='$activeClass' href='?page=$i'>$i</a>";
          }

          echo "<a class='prev-next' href='?page=$nextPage'>ถัดไป &raquo;</a>";
          ?>
        </div>
      </div>
      <hr>
    </div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <p>Copyright © 2036 <a href="#">Thai Food</a> Company. All rights reserved.


          </div>
        </div>
      </div>
    </footer>


    <!-- Scripts -->
    <script>
      document.getElementById("priceInput").addEventListener("input", function(event) {
        let inputValue = event.target.value;
        if (!/^\d*\.?\d*$/.test(inputValue)) {
          event.target.value = inputValue.replace(/[^\d.]/g, '');
        }
      });
    </script>
    <script>
      $(document).ready(function() {
        $('#searchText').on('input', function() {
          var searchText = $(this).val();

          $.ajax({
            url: 'search.php',
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
            '<div class="col-md-12 d-flex mb-3">' +
            '<h2>Drink</h2>' +
            '<div class="col-md-11 d-flex justify-content-end">' +
            '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add</button>' +
            '</div>' +
            '</div>' +
            '<table class="table table-dark table-striped">' +
            '<thead>' +
            '<tr>' +
            '<th scope="col">ID</th>' +
            '<th scope="col">ชื่อเครื่องดื่ม</th>' +
            '<th scope="col">ประเภทเครื่องดื่ม</th>' +
            '<th scope="col">ราคา</th>' +
            '<th scope="col">รูปภาพ</th>' +
            '<th scope="col">ตัวเลือก</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';

          var tableBody = '';
          results.forEach(function(Drink) {
            tableBody += '<tr>' +
              '<th scope="row">' + Drink['id'] + '</th>' +
              '<td>' + Drink['name'] + '</td>' +
              '<td>' + Drink['type'] + ' ' + Drink['typeName'] + '</td>' +
              '<td>' + Drink['price'] + '</td>' +
              '<td width="150px"><img width="150px" height="150px" style="object-fit: cover;" src="uploads/' + Drink['img'] + '" class="rounded" alt=""></td>' +
              '<td>' +
              '<a href="edit.php?id=' + Drink['id'] + '" class="btn btn-warning">Edit</a>' +
              '<a href="?delete=' + Drink['id'] + '" class="btn btn-danger" onclick="return confirm(\'คุณต้องการลบใช่หรือไม่?\')">Delete</a>' +
              '</td>' +
              '</tr>';
          });

          var tableEnd = '</tbody>' +
            '</table>';

          searchResultsContainer.append(tableHead + tableBody + tableEnd);
        }

      }
    </script>
    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="../assets/js/isotope.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/tabs.js"></script>
    <script src="../assets/js/popup.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script>
      let imgInput = document.getElementById('imgInput');
      let previewImg = document.getElementById('previewImg');
      imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
          previewImg.src = URL.createObjectURL(file);
        }
      }
    </script>

</body>

</html>