<header class="header">
	<div class="flex">
		<!-- Logo -->
		<a href="all_posts.php" class="logo"> <img src="image/11.png" width="120"> </a>
		
		<!-- Navigation menu -->
		<nav class="navbar">
			<a href="add_dramas.php" class="far fa-plus"></a>
			<a href="all_posts.php" class="far fa-eye"></a>
			<a href="login.php" class="fas fa-arrow-right-to-bracket"></a>
			<a href="register.php" class="far fa-registered"></a>
			<div id="user-btn" class="far fa-user"></div>
		</nav>

		<!-- Profile section -->
		<div class="profile">
			<?php  
				$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
				$select_profile->execute([$user_id]);
				if ($select_profile->rowCount() > 0) {
					$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
			
			?>

			<!-- Display profile pic if available -->
			<?php if ($fetch_profile['image'] != '') { ?>
				<img src="uploaded_file/<?= $fetch_profile['image']; ?>" class="image">
			<?php } ?>
			 <!-- Display username -->
			<p><?= $fetch_profile['name']; ?></p>
			
			<!-- User profile actions -->
			<div class="flex-btn">
				<a href="update_user.php" class="btn">Update profile</a>
				<a href="components/logout.php" class="delete-btn" onclick="return confirm('Log out from this website');">Log out</a>
			</div>
			<?php }else{?>
				<!-- Display another img and message if the user is not logged in -->
				<img src="image/user.png" class="image">
				<p class="name">Please login or register first!</p>
				
				<!-- User actions-->
				<div class="flex-btn">
					<a href="login.php" class="btn">Login</a>
					<a href="register.php" class="delete-btn">Register</a>
				</div>
			<?php } ?>
		</div>
	</div>
</header>
<div class="banner" style="background-image: url('image/g.gif');"></div>