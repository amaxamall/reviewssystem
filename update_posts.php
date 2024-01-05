<?php 
    include 'components/connection.php';

    // Check if the drama ID is provided in the URL
    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else {
        $get_id = '';
        header('location:all_posts.php');
    }

    // Update drama logic
    if (isset($_POST['submit'])) {

        $select_drama = $conn->prepare("SELECT * FROM `dramas` WHERE id = ? LIMIT 1");
        $select_drama->execute([$get_id]);

        $fetch_drama = $select_drama->fetch(PDO::FETCH_ASSOC);

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $airedeps = $_POST['airedeps'];
        $airedeps = filter_var($airedeps, FILTER_SANITIZE_STRING);
        $alleps = $_POST['alleps'];
        $alleps = filter_var($alleps, FILTER_SANITIZE_STRING);

        $update_drama = $conn->prepare("UPDATE `dramas` SET name=?, airedeps=?, alleps=? WHERE id=?");
        $update_drama->execute([$name, $airedeps, $alleps, $get_id]);
        $success_msg[] = 'Drama updated successfully!';
    
        //Update image
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
                $update_image = $conn->prepare("UPDATE `dramas` SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $get_id]);
                move_uploaded_file($image_tmp_name, $image_folder);

                // Delete the old image if it exists
                if ($fetch_drama['image'] != '') {
                    unlink('uploaded_file/'.$fetch_drama['image']);
                }
                $success_msg[] = 'Drama image updated successfully';
            }
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
    
    <title>Update Drama</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <section class="update-drama">
        <h3 class="heading">Update drama</h3>
        <?php
            // Fetch drama details for pre-filling the form
            $select_drama = $conn->prepare("SELECT * FROM `dramas` WHERE id = ? LIMIT 1");
            $select_drama->execute([$get_id]);
            if ($select_drama->rowCount() > 0) {
                while ($fetch_drama = $select_drama->fetch(PDO::FETCH_ASSOC)) {
            
            
        ?>    
        <!-- Edit form -->
        <form action="" method="post" enctype="multipart/form-data" class="account-form">
            <table class="editclss">
                <tr>
                    <td class="imagecol">
                <?php if($fetch_drama['image'] != ''){?>
                <!-- Display the current image if one exists -->
                <img src="uploaded_file/<?= $fetch_drama['image']; ?>" class="image"><br>
                <label for="editimg" class="editlabel">Edit Image</label>
            <?php } ?>
                <!-- Choose a new image -->
                <input type="file" id="editimg" name="image" accept="image/*" class="box" hidden>
            </td>
            <td class="formcol">
                <!-- Drama details -->
                <div class="input-field">
                <p class ="placeholder">Drama name<span>*</span></p>
                <input type="text" name="name" required maxlength="50" placeholder="<?= $fetch_drama['name']; ?>" class="box">
            </div>
            <div class="input-field">
                <p class ="placeholder">Aired episodes<span>*</span></p>
                <input type="text" name="airedeps" required maxlength="10" value="<?= $fetch_drama['airedeps']; ?>" class="box">
            </div>
            <div class="input-field">
                <p class ="placeholder">Total episodes<span>*</span></p>
                <input type="text" name="alleps" required maxlength="10" value="<?= $fetch_drama['alleps']; ?>" class="box">
            </div></td></tr></table>
            
        
            <div class="flex-btn">
                <input type="submit" name="submit" value="Update Drama" class="btn" style="width: 30%;">
            </div>
        </form> 
    </section>
    <?php 
                }
            }else{
                echo '<p class= "empty">something went wrong</p>';
            }
    ?>  
    </section>
    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="js/script.js"></script>
    
    <?php include 'components/alert.php'; ?>
</body>
</html>