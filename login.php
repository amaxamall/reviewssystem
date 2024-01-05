<?php 
    include 'components/connection.php';

    // Check if the login form has been submitted
    if(isset($_POST['submit'])){
        
        // Collect and sanitize email and password
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = $_POST['pass'];
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        // Check if the email exists
        $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
        $verify_email->execute([$email]);

        // If true, verify the entered password
        if ($verify_email->rowCount() > 0) {
            $fetch = $verify_email->fetch(PDO::FETCH_ASSOC);
            $verify_pass = password_verify($pass, $fetch['password']);
           
           // If pass verification is successful, set a cookie and redirect
            if ($verify_pass == 1) {
                setcookie('user_id', $fetch['id'], time() + 60*60*24*30, '/');
                header('location:all_posts.php');
            }else{
                $warning_msg[] = 'Incorrect password!';
            }
        }else{
            $warning_msg[] = 'Incorrect email!';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
     <!-- Login form -->
    <section class="account-form">
        <h3 class="heading">Log into your account</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <p class="placeholder">Your email <span>*</span></p>
                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Your password <span>*</span></p>
                <input type="password" name="pass" required maxlength="50" placeholder="Enter your password" class="box">
            </div>

            <input type="submit" name="submit" value="login now" class="btn">
            <p class="link">Do not have an account? <a href="register.php">Register now</a></p>
        </form>        
    </section>
    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="js/script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>