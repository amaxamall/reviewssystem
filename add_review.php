<?php 
    include 'components/connection.php';
    // Check if 'get_id' is set in the URL
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location: all_posts.php');
    }

    if (isset($_POST['submit'])) {
        // Check if the user is logged in
        if ($user_id != '') {
            $id = create_unique_id();
            // Sanitize and retrieve input values
            $title = $_POST['title'];
            $title = filter_var($title, FILTER_SANITIZE_STRING);
            $description = $_POST['description'];
            $description = filter_var($description, FILTER_SANITIZE_STRING);

            $rating = $_POST['rating'];
            $rating = filter_var($rating, FILTER_SANITIZE_STRING);

            
            $verify_rating = $conn->prepare("SELECT * FROM `reviews` WHERE post_id= ? AND user_id = ?");
            $verify_rating->execute([$get_id, $user_id]);

            // Check if the user added a review for this post before 
            if ($verify_rating->rowCount() > 0) {
                $warning_msg[] = 'Your review has already been added!';
            }else{
                // If not, add the review
                $add_review = $conn->prepare("INSERT INTO `reviews`(id, post_id, user_id, rating, title, description) VALUES(?,?,?,?,?,?)");
                $add_review->execute([$id, $get_id, $user_id, $rating, $title, $description]);
                $success_msg[] = 'Review added!';
            }
        }else{
            $warning_msg[] = "Please login first";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Add your reviews page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
     <!-- Add review form -->
    <section class="account-form">
        <h3 class="heading">Post your review</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <p class="placeholder">Review title<span>*</span></p>
                <input type="text" name="title" required maxlength="100" placeholder="Enter review title" class="box">
            </div>
            <div class="input-field">
                <p class="placeholder">Review description<span>*</span></p>
                <textarea name="description" class="box" placeholder="Enter review description" ></textarea>
            </div>
            <div class="input-field" >
                <select name="rating" class="box" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="flex-btn">
                <input type="submit" name="submit" value="submit review" class="btn" style="width: 30%;">
                <a href="view_post.php?get_id=<?= $get_id; ?>" class="delete-btn" style="width: 50%;">Go back</a>
            </div>
        </form>        
    </section>
    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="js/script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>