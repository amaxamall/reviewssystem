<?php 
    include 'components/connection.php';

     // Check if 'get_id' is set in the URL parameters
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location:all_posts.php');
    }
    //Delete review logic
    if (isset($_POST['delete_review'])) {
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_delete = $conn->prepare("SELECT * FROM `reviews` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if ($verify_delete->rowCount() > 0) {
            $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id = ?");
            $delete_review->execute([$delete_id]);
            $success_msg[] = "Review Deleted";
        }else{
            $warning_msg[] = "Review Already Deleted";
        }
    }
    // Delete post(drama) logic
    if (isset($_POST['delete_post'])) {
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_delete = $conn->prepare("SELECT * FROM `dramas` WHERE id = ?");
        $verify_delete->execute([$delete_id]);

        if ($verify_delete->rowCount() > 0) {
            $delete_post = $conn->prepare("DELETE FROM `dramas` WHERE id = ?");
            $delete_post->execute([$delete_id]);
            $success_msg[] = "Post Deleted";
        } else {
            $warning_msg[] = "Post Already Deleted";
        }
    }
?>
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>View dramas page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <!-- View post section -->
    <section class="view-post">
        <div class="heading">
            <h1>post details</h1>
            <a href="all_posts.php" style="margin-top: .5rem;" class="btn">all posts</a>
        </div>
         <!-- Fetch drama details -->
        <?php  
            $select_post = $conn->prepare("SELECT * FROM `dramas` WHERE id = ? LIMIT 1");
            $select_post->execute([$get_id]);
            if ($select_post->rowCount() > 0) {
                while ($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)) {
                    $total_ratings = 0;
                    $rating_1 = 0;
                    $rating_2 = 0;
                    $rating_3 = 0;
                    $rating_4 = 0;
                    $rating_5 = 0;

                    // Fetch ratings
                    $select_ratings = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
                    $select_ratings->execute([$fetch_post['id']]);
                    $total_reviews = $select_ratings->rowCount();

                    // Calculate total ratings and individual ratings
                    while ($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)) {
                        $total_ratings += $fetch_rating['rating'];
                        // Count occurrences of each rating
                        if ($fetch_rating['rating'] == 1) {
                            $rating_1 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 2) {
                            $rating_2 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 3) {
                            $rating_3 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 4) {
                            $rating_4 += $fetch_rating['rating'];
                        }
                        if ($fetch_rating['rating'] == 5) {
                            $rating_5 += $fetch_rating['rating'];
                        }
                    }
                     // Calculate average rating
                    if ($total_reviews != 0) {
                        $avarage = round($total_ratings / $total_reviews, 1);
                    }else{
                        $avarage = 0;
                    }
        ?>
        <!-- Display drama details -->
        <div class="row">
            <div class="col">
                <!--Drama img and name -->
                <img src="uploaded_file/<?= $fetch_post['image']; ?>" class="image">
                <h3 class="title"><?= $fetch_post['name']; ?></h3>
            </div>
            <div class="col">
                <!-- Ratings -->
                <div class="flex">
                    <div class="total-reviews">
                        <h3><?= $avarage; ?><i class="fas fa-star"></i></h3>
                        <p><?= $total_reviews ?></p>
                    </div>
                    <div class="total-ratings">
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_5; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_4; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_3; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_2; ?></span>
                        </p>
                        <p>
                            <i class="fas fa-star"></i>
                            <span><?= $rating_1; ?></span>
                        </p>
                    </div>
                </div>                
            </div>
            <h3 class="title"><?= $fetch_post['name']; ?></h3>
               
                <?php if($fetch_post['user_post_id']===$user_id){ ?>
                    <!-- Delete/edit post -->
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="delete_id" value="<?= $fetch_post['id']; ?>"><a href="update_posts.php?get_id=<?= $fetch_post['id']; ?>" class="update-btn">Edit Post</a>
                        <input type="submit" name="delete_post" value="delete post" class="delete-btn" onclick="return confirm('delete this post');">
                    </form>
                <?php }?>
        </div>
        <?php
                }
            }else{
                echo '<p class= "empty"> No post added yet!</p>';
            }
        ?>
    </section>

    <!-- Reviews section -->
    <section class="reviews-container">
        <div class="heading"><h1>user's reviews</h1><a href="add_review.php?get_id=<?= $get_id; ?>" style="margin-top: .5rem;" class="btn">add review</a></div>
        <div class="box-container">
            <?php
                // Fetch reviews 
                $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
                $select_reviews->execute([$get_id]);
                if ($select_reviews->rowCount() > 0) {
                     while ($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)) {
                         

            ?>
            <!-- Individual review box -->
            <div class="box" <?php if ($fetch_review['user_id']==$user_id) {echo 'style="order:-1"';} ?>>
                <?php
                    // Fetch details of the user who wrote the review
                    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id =?");
                    $select_user->execute([$fetch_review['user_id']]);

                    // Display user information and image (or initials if no image)
                    while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){   
                ?>
                <div class="user">
                    <?php if ($fetch_user['image'] != ''){ ?>
                        <img src="uploaded_file/<?= $fetch_user['image']; ?>">
                    <?php }else{ ?>
                        <h3><?= substr($fetch_user['name'], 0,1); ?></h3>
                    <?php } ?>
                    <div>
                        <p><?= $fetch_user['name']; ?></p>
                        <span><?= $fetch_review['date']; ?></span>
                    </div>                
                </div>
                <?php } ?>


                <div class="ratings">
                    <?php if ($fetch_review['rating'] == 1){ ?>
                        <p style="background: red;"><i class="fas fa-star"></i><span><?= $fetch_review['rating']; ?></span></p>
                    <?php }; ?>
                    <?php if ($fetch_review['rating'] == 2){ ?>
                        <p style="background: red;"><i class="fas fa-star"></i><span><?= $fetch_review['rating']; ?></span></p>
                    <?php }; ?>
                    <?php if ($fetch_review['rating'] == 3){ ?>
                        <p style="background: orange;"><i class="fas fa-star"></i><span><?= $fetch_review['rating']; ?></span></p>
                    <?php }; ?>
                    <?php if ($fetch_review['rating'] == 4){ ?>
                        <p style="background: var(--main-color);"><i class="fas fa-star"></i><span><?= $fetch_review['rating']; ?></span></p>
                    <?php }; ?>
                    <?php if ($fetch_review['rating'] == 5){ ?>
                        <p style="background: var(--main-color);"><i class="fas fa-star"></i><span><?= $fetch_review['rating']; ?></span></p>
                    <?php }; ?>
                </div>
                
                <h3 class="title"><?= $fetch_review['title']; ?></h3>
                

                <?php if ($fetch_review['description'] != ''){ ?>
                    <p class="description"><?=$fetch_review['description']; ?></p>
                <?php }; ?>
                

                <?php if($fetch_review['user_id']==$user_id){ ?>
                    <!-- Delete/edit review -->
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">
                        <a href="update_review.php?get_id=<?= $fetch_review['id']; ?>" class="btn">edit review</a>
                        <input type="submit" name="delete_review" value="delete review" class="delete-btn" onclick="return confirm('delete this review');">
                    </form>
                <?php }?>
            </div>                       
            <?php     
                     }
                }else{
                    echo '<p class= "empty"> no review added yet!</p>';
                }
            ?>
        </div>
    </section>
    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="js/script.js"></script>

    <?php include 'components/alert.php'; ?>
</body>
</html>