<?php 
    include 'components/connection.php';

    // Check if the registration form has been submitted
    if(isset($_POST['submit'])){
        $id = create_unique_id();
        
        // Retrieve and sanitize username and email
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        // Hash the pass
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
        //Verify the pass
        $cpass = password_verify($_POST['cpass'], $pass);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        // Process the user-provided image
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_file/'.$rename;

        if(!empty($image)) {
            if($image_size > 2000000) {
                $warning_msg[] = 'Image size is too large';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        }else{
            $rename='';
        }

        // Check if the email already exists
        $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $verify_email->execute([$email]);

        if ($verify_email->rowCount() > 0) {
            $warning_msg[] = 'Email already exist';
        }else{
            // If cpass matches, insert user into the DB
            if ($cpass == 1) {
                 // Insert user into the database
                $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
                $insert_user->execute([$id, $name, $email, $pass, $rename]);
                $success_msg[] = 'Registered successfully!';
            }else{
                $warning_msg[] = 'Confirm password does not match';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Register page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <!-- User registration form -->
    <section class="account-form">
        <h3 class="heading">Make your account</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <p class="placeholder">Your name <span>*</span></p>
                <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Your email <span>*</span></p>
                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Your password <span>*</span></p>
                <input type="password" name="pass" required maxlength="50" placeholder="Enter your password" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Confirm password <span>*</span></p>
                <input type="password" name="cpass" required maxlength="50" placeholder="Confirm your password" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Profile pic <span>*</span></p>
                <input type="file" name="image" accept="image/*" class="box">
            </div>
            <input type="submit" name="submit" value="Register now" class="btn">
            <p class="link">Already have an account? <a href="login.php">Login now</a></p>
        </form>        
    </section>

    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="js/script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>