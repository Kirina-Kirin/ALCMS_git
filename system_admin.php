<?php

	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
	
	function edit() {

		$_SESSION["editid"] = $_POST['edit'];

		$data = array("url"=>"page_teacher_form.php","name"=>"edit account");
		header('Content-type: application/json');
		echo json_encode( $data );
	}

	function delete() {
		$sql = "DELETE FROM employee WHERE Employee_ID='".$_POST['del']."'";
		$row = mysqli_query($con,$sql);
	}

	if($_SESSION['status']=="admin"){
		if (isset($_POST['edit'])) {
			edit($_POST['edit']);
		}else if(isset($_POST['del'])){
			delete();
			header("Location:index.php");
		}
	}
?>