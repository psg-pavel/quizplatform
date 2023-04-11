<?php
include_once(__DIR__.'/library.php');
include_once(DIR_AUTH.'config.php');

if ($_POST){

	if (isset($_POST['quiz_id'])){

		$sql = "INSERT INTO quiz_data (
			`data_id`, 
			`name`, 
			`phone`, 
			`date_added`, 
			`text`, 
			`quiz_id`
		) VALUES (
			NULL, 
			'".trim($_POST['quiz_name'])."', 
			'".trim($_POST['quiz_phone'])."', 
			'".date('Y-m-d')."', 
			'".json_encode($_POST, JSON_UNESCAPED_UNICODE)."', 
			'".trim($_POST['quiz_id'])."' 
		)";
		mysqli_query($conn, $sql);
	}

	header('Location: '.$_POST['redirect']);
	exit();
}
?>