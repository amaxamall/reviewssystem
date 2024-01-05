<?php 
    include 'components/connection.php';

    if (isset($_POST['add_drama'])){
        $id = create_unique_id();
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $airedeps = $_POST['airedeps'];
        $airedeps = filter_var($airedeps, FILTER_SANITIZE_STRING);
        $alleps = $_POST['alleps'];
        $alleps = filter_var($alleps, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_file/'.$rename;

        if($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large';
        }else{
            $insert_drama = $conn->prepare("INSERT INTO `dramas`(id, name, airedeps, alleps, image, user_post_id) VALUES(?,?,?,?,?,?)");
            $insert_drama->execute([$id, $name, $airedeps, $alleps, $rename, $user_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'Drama added successfully!';
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
    
    <title>Add drama's page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <!-- Add drama form -->
    <section class="add-drama">
        <h1 class="heading">Add drama</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Drama details</h3>
            <div class="input-field">
                <p>Drama name<span>*</span></p>
                <input type="text" name="name" required maxlength="50" placeholder="Enter drama name" class="input">
            </div>
            <div class="input-field">
                <p>Aired episodes<span>*</span></p>
                <input type="number" name="airedeps" required maxlength="10" placeholder="Enter aired episodes" min="0" max="9999999999" class="input">
            </div>
            <div class="input-field">
                <p>Total episodes<span>*</span></p>
                <input type="number" name="alleps" required maxlength="10" placeholder="Total episodes available" min="0" max="9999999999" class="input">
            </div>
            <div class="input-field">
                <p>Drama image<span>*</span></p>
                <input type="file" name="image" required accept="image/*" class="input">
            </div>
            <div class="flex-btn">
            <input type="submit" name="add_drama" value="add drama" class="btn">
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