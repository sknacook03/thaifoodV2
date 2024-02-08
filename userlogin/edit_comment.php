<?php
    session_start();
    require_once("../config.php");
    if(!isset($_SESSION['user_login'])){
      $_SESSION['error'] = 'กรุณาอย่าเหลี่ยม!!!!!!!';
      header('location:../login.php');
  }
  if(isset($_POST['update'])){
    $idReview = $_POST['idReview'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_login'];
    date_default_timezone_set('Asia/Bangkok');
    $date = date('Y-m-d H:i:s');
    if(empty($comment)){
        $_SESSION['error'] = 'กรุณากรอกข้อความ';
        header("location:userreview.php");
        exit;
    } else {
    $pcm = $conn->prepare("UPDATE review SET userID = :userID, comment = :comment, date = :date WHERE idReview = :idReview");
    $pcm->bindParam(":idReview", $idReview);
    $pcm->bindParam(":userID", $user_id);
    $pcm->bindParam(":comment", $comment);
    $pcm->bindParam(":date", $date);
    $pcm->execute();
        if($pcm){
          $_SESSION['success'] = "บันทึกข้อมูลได้";
            header("location:userreview.php");
            exit;
        } else {
            $_SESSION['error'] = "ไม่สามารถบันทึกข้อมูลได้";
            header("location:userreview.php");
            exit;
        }
    }
}else if(isset($_POST['cancel'])){
    header("location:userreview.php");
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
    <link rel="stylesheet" href="../assets/css/thaifood.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
  </head>

<body>
<?php
        if(isset($_SESSION['user_login'])){
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
                      <form id="search" action="#">
                        <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" onkeypress="handle" />
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
                        <li><a href="userreview.php" class="active">รีวิวลูกค้า</a></li>
                        <!-- <li><a href="info.html">ติดต่อเรา</a></li> -->
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

            <form action="edit_comment.php" method="post">
            <?php
             if(isset($_GET['idReview'])){
                $idReview = $_GET['idReview'];
                $stmt = $conn->query("SELECT * FROM review WHERE idReview = $idReview");
                $stmt->execute();
                $data = $stmt->fetch();
             }else{
              echo $_SESSION['error'] = "error";
             }
            ?>
            <input type="hidden" value="<?= $data['idReview']; ?>" required class="form-control" name="idReview">
            <?php if(isset($_SESSION['error'])) {?>
            <div class="alert alert-danger" role="alert">
                <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
            <?php }?>
            <?php if(isset($_SESSION['success'])) {?>
            <div class="alert alert-success" role="alert">
                <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
            <?php }?>
          <div class="con-comments">
            <div class="profile-com">
              <p><img src="../assets/images/profile-header.jpg" alt=""> <?php echo $row['firstname']?></p>
              <div class="text-area">
                <textarea placeholder="Write someting..." name="comment" class="form-text"><?= $data['comment']; ?></textarea>
              </div>
          </div>
            <div class="text-btn">
              <button type="submit" name="update" class="btn-post">Update comment</button>
              <button type="submit" name="cancel" class="btn-cancel">Cancel</button>
            </div>
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


  </body>

</html>
