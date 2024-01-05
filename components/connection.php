<?php
	// Database connection
	$db_name = 'mysql:host=localhost;dbname=reviews_db';
	$db_user_name = 'root';
	$db_user_password = '';
	$conn = new PDO($db_name, $db_user_name, $db_user_password);

	if (!$conn) {
		echo "Did not connect successfully!";
	}

	//Generating unique id
	function create_unique_id(){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen($characters);
		$random_string = '';
		for ($i=0; $i < 20; $i++) { 
			$random_string .= $characters[mt_rand(0, $characters_length - 1)];
		}
		return $random_string;
	}

	//Check for existing user id

	if (isset($_COOKIE['user_id'])) {
		$user_id = $_COOKIE['user_id'];
	}else{
		$user_id = '';
	}
?>