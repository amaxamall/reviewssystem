<?php 
    include 'components/connection.php';
?>
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title>All posts</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <section class="all_posts">
        <h1 class="heading">all posts</h1>
        <div class="box-container">
            <?php 
                //Fetch all posts 
                $select_posts = $conn->prepare("SELECT * FROM `dramas`");
                $select_posts->execute();

                //Check if there are any posts
                if ($select_posts->rowCount() > 0) {
                    // Fetch and display each post
                    while ($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                        $post_id = $fetch_post['id'];
                        // Counting the number of reviews for the current post
                        $count_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ?");
                        $count_reviews->execute([$post_id]);
                        $total_reviews = $count_reviews->rowCount();

            ?>
            
            <div class="box">
                <img src="uploaded_file/<?= $fetch_post['image']; ?>" class="image">
                <h3 class="title"><?= $fetch_post['name']; ?></h3>
                <p class="total-reviews"><i class="fas fa-star"></i><span><?= $total_reviews; ?></span></p>
                <a href="view_post.php?get_id=<?= $post_id; ?>" class="btn">view post</a>
            </div>
            <?php
                    }
                }else{
                    echo '<p class= "empty"> no post added yet!</p>';
                }
             ?>
            
        </div>        
    </section>
    <script type="text/javascript" src="js/script.js"></script>
</body>
</html>