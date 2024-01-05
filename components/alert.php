<?php 
	//Success alert
	if (isset($success_msg)) {
		foreach ($success_msg as $success_msg) {
			echo '<script>swal("'.$success_msg.'", "", "Success");</script>';
		}
	}

	//Warning alert
	if (isset($warning_msg)) {
		foreach ($warning_msg as $warning_msg) {
			echo '<script>swal("'.$warning_msg.'", "", "Warning");</script>';
		}
	}

	//Error alert
	if (isset($error_msg)) {
		foreach ($error_msg as $error_msg) {
			echo '<script>swal("'.$error_msg.'", "", "Error");</script>';
		}
	}

	//Info alert
	if (isset($info_msg)) {
		foreach ($info_msg as $info_msg) {
			echo '<script>swal("'.$info_msg.'", "", "Info");</script>';
		}
	}
?>