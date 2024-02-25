<?php
session_start();
require_once("../config.php");
if (!isset($_SESSION['admin_login'])) {
  $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
  header('location:../login.php');
  exit;
}

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $type = $_POST['type'];
  $price = $_POST['price'];
  $img = $_FILES['img'];

  $img2 = $_POST['img2'];
  $upload = $_FILES['img']['name'];

  if ($upload != '') {
    $allow = array('jpg', 'jpeg', 'png');
    $extension = explode(".", $img['name']);
    $fileActExt = strtolower(end($extension));
    $fileNew = rand() . "." . $fileActExt;
    $filePath = "uploads/" . $fileNew;

    if (in_array($fileActExt, $allow)) {
      if ($img['size'] > 0 && $img['error'] == 0) {
        move_uploaded_file($img['tmp_name'], $filePath);
      }
    }
  } else {
    $fileNew = $img2;
  }
  $check = $conn->prepare("SELECT name FROM food WHERE name = :name AND id != :id");
  $check->bindParam(':name', $name, PDO::PARAM_STR);
  $check->bindParam(':id', $id, PDO::PARAM_INT);
  $check->execute();
  $count = $check->rowCount();
  if ($count > 0) {
    $_SESSION['error'] = "ชื่ออาหารนี้มีอยู่แล้วในระบบ";
    header("location: admin-food.php");
    exit;
  } else if (strpos($name, ' ') !== false) {
    $_SESSION['error'] = 'กรุณากรอกชื่อโดยไม่มีช่องว่าง';
    header("location: admin-food.php");
    exit;
  } else if (!preg_match("/^[a-zA-Zก-๏เ\s]+$/u", $name)) {
    $_SESSION['error'] = 'กรุณากรอกชื่อเครื่องดื่มเป็นภาษาไทยหรืออังกฤษเท่านั้น';
    header("location: admin-drink.php");
    exit;
  }

  $sql = $conn->prepare("UPDATE food SET name = :name, type = :type, price = :price, img = :img WHERE id = :id");
  $sql->bindParam(":id", $id);
  $sql->bindParam(":name", $name);
  $sql->bindParam(":type", $type);
  $sql->bindParam(":price", $price);
  $sql->bindParam(":img", $fileNew);
  $sql->execute();
  if ($sql) {
    $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
    header("location: admin-food.php");
    exit;
  } else {
    $_SESSION['error'] = "ไม่สามารถบันทึกข้อมูลได้";
    header("location: admin-food.php");
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
  <title>ThaiFood-ADMIN</title>

  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/thaifood.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

  <style>
    .con-edit {
      width: 550px;

      & img {
        margin-top: 20px;
      }
    }
  </style>
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
            <a href="../admin-index.php" class="logo">
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
              <li><a href="../food/admin-food.php" class="active">อาหาร</a></li>
              <li><a href="../drink/admin-drink.php">เครื่องดื่ม</a></li>
              <li><a href="../review/admin-review.php">รีวิวลูกค้า</a></li>
              <li><a><?php echo $row['firstname'] ?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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
      <div class="page-content d-flex justify-content-center">
        <div class="con-edit">
          <h1>Edit food</h1>
          <hr>
          <form class="edit-form" action="edit.php" method="post" enctype="multipart/form-data">
            <?php
            if (isset($_GET['id'])) {
              $id = $_GET['id'];
              $stmt = $conn->query("SELECT * FROM food WHERE id = $id");
              $stmt->execute();
              $data = $stmt->fetch();
            }
            ?>

            <div class="mb-3 text-white">
              <label for="Name" class="col-form-label">ชื่ออาหาร:</label>
              <input type="text" value="<?= $data['name']; ?>" required class="form-control" name="name">
              <input type="text" hidden value="<?= $data['id']; ?>" required class="form-control" name="id">
              <input type="hidden" value="<?= $data['img']; ?>" required class="form-control" name="img2">
            </div>
            <div class="mb-3 text-white">
              <label for="Name" class="col-form-label">ประเภทอาหาร:</label>
              <select type="text" value="<?= $data['type']; ?>" required class="form-control" name="type">
                <?php
                $stmt = $conn->query("SELECT * FROM type ORDER BY typeID");
                $stmt->execute();
                $types = $stmt->fetchAll();
                if (!$types) {
                  echo "<tr><td colspan='6' class='text-center'>No type found</td></tr>";
                } else {
                  foreach ($types as $type) {
                ?>
                    <option value="<?= $type['typeID'] ?>" <?= $data['type'] == $type['typeID'] ? 'selected' : ''  ?>><?= $type['typeID'] ?> <?= $type['typeName'] ?></option>
                <?php   }
                } ?>
              </select>
            </div>
            <div class="mb-3 text-white">
              <label for="Name" class="col-form-label">ราคา:</label>
              <input type="number" value="<?= $data['price']; ?>" required class="form-control" name="price" id="priceInput">
              <small id="priceHelp" class="form-text text-muted">โปรดป้อนตัวเลขเท่านั้น</small>
            </div>
            <div class="mb-3 text-white ">
              <label for="img" class="col-form-label">รูปภาพ:</label>
              <input type="file" class="form-control" id="imgInput" name="img">
              <img width="100%" src="uploads/<?= $data['img']; ?>" id="previewImg" alt="">
            </div>
            <div class="modal-footer gap-1">
              <a class="btn btn-secondary" href="admin-food.php">Back</a>
              <button type="submit" name="update" class="btn btn-success">Update</button>
            </div>
          </form>

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
    document.getElementById("priceInput").addEventListener("input", function(event) {
      let inputValue = event.target.value;
      if (!/^\d*\.?\d*$/.test(inputValue)) {
        event.target.value = inputValue.replace(/[^\d.]/g, '');
      }
    });
  </script>
  <script>
    document.getElementById("priceInput").addEventListener("input", function() {
      var price = this.value.trim();
      if (price.startsWith("0")) {
        this.setCustomValidity("ห้ามใส่เลข 0 นำหน้า");
      } else {
        this.setCustomValidity("");
      }
    });
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