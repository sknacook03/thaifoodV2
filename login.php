<?php
session_start();
require_once("config.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">


</head>

<body>

    <form action="signup.php" method="post">
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['warning'])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php
                echo $_SESSION['warning'];
                unset($_SESSION['warning']);
                ?>
            </div>
        <?php } ?>
        <div class="form-structor">
            <div class="signup">
                <div class="exit-btn">
                    <a href="food.php">Home</a>
                </div>
                <h2 class="form-title" id="signup"><span></span>Sign up</h2>
                <div class="form-holder">
                    <input name="firstname" id="firstname" type="text" class="input" placeholder="FirstName" value="<?= isset($_SESSION['input_values']['firstname']) ? htmlspecialchars($_SESSION['input_values']['firstname']) : "" ?>" />
                    <input name="lastname" id="lastname" type="text" class="input" placeholder="LastName" value="<?= isset($_SESSION['input_values']['lastname']) ? htmlspecialchars($_SESSION['input_values']['lastname']) : "" ?>" />
                    <input name="email" id="email" type="email" class="input" placeholder="Email" value="<?= isset($_SESSION['input_values']['email']) ? htmlspecialchars($_SESSION['input_values']['email']) : "" ?>" />
                    <input name="number" id="number" type="text" class="input" placeholder="TelephoneNumber" value="<?= isset($_SESSION['input_values']['number']) ? htmlspecialchars($_SESSION['input_values']['number']) : "" ?>" />
                    <input name="password" type="password" class="input" placeholder="Password" value="" />

                </div>
                <button type="submit" name="signup" class="submit-btn" onclick="saveInputValues()">Sign up</button>
            </div>
    </form>
    <form action="signin.php" method="post">
        <div class="login slide-up">
            <div class="center">
                <h2 class="form-title" id="login">Log in</h2>
                <div class="form-holder">
                    <input name="email_l" type="email" class="input" placeholder="Email" value="<?= isset($_SESSION['input_value']['email']) ? htmlspecialchars($_SESSION['input_value']['email']) : "" ?>" />
                    <input name="password_l" type="password" class="input" placeholder="Password" />
                </div>
                <button type="submit" name="signin" class="submit-btn">Log in</button>
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </form>
    <script src="assets/js/login.js"></script>
    </div>
</body>

</html>