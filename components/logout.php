<?php
	include 'connection.php';

	//Log out-deleting the cookie and redirecting to all posts
	setcookie('user_id', '', time() - 1, '/');
	header('location:../all_posts.php');
?>