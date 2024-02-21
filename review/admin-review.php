<?php
    session_start();
    require_once("../config.php");
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
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
  </head>

<body>
<?php
        if(isset($_SESSION['admin_login'])){
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
                      <li><a href="../drink/admin-drink.php">เครื่องดื่ม</a></li>
                      <li><a href="../review/admin-review.php"class="active">รีวิวลูกค้า</a></li>
                      <li><a><?php echo $row['firstname']?> <img src="../assets/images/profile-header.jpg" alt=""></a></li>
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


        <?php
            $stmt = $conn->query("SELECT review.*, users.firstname, users.userID FROM review JOIN users ON review.userID = users.userID ORDER BY idReview DESC");
            $stmt->execute();
            $reviews = $stmt->fetchAll();
            $countStmt = $conn->prepare("SELECT COUNT(idReview) AS total FROM review JOIN users ON review.userID = users.userID ORDER BY idReview");
            $countStmt->execute();
            $totalReviews = $countStmt->fetchColumn();
            echo "<h6 class=\"mb-4\">" . $totalReviews . " ความคิดเห็น</h6>";

            if (!$reviews) {
                echo "<tr><td colspan='6' class='text-center'>No comment found</td></tr>";
            } else {
                foreach ($reviews as $review) {
                    ?>
                    <div class="con-comments">
                        <div class="con-pro">
                            <p><img src="../assets/images/profile-header.jpg" alt=""> <?php echo $review['firstname']?> <span> - <?php echo date('d M Y H:i น.', strtotime($review['date']));?></span></p>
                        </div>
                        <div class="comments">
                        <p style="max-width: 1000px; word-wrap : break-word;"><?php echo $review['comment'];?></p>
                        </div>
                    </div>
                    <?php
                }
            }
        ?>
        </div>
      </div>
    </div>
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
     imgInput.onchange = evt=> {
      const [file] = imgInput.files;
      if(file){
        previewImg.src = URL.createObjectURL(file);
      }
     }
  </script>

  </body>

</html>
