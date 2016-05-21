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
		if(isset($_POST['admin_edit_teacher_button'])) {

			$sql = "UPDATE employee SET 
			Employee_Name='".$_POST['namesignup']."', Employee_Lastname='".$_POST['snamesignup']."', 
			Employee_Tel='".$_POST['telsignup']."', Employee_Email='".$_POST['emailsignup']."',
			Employee_LoginName='".$_POST['usernamesignup']."', Employee_Password='".$_POST['passwordsignup']."'
			WHERE Employee_ID='".$_SESSION["editid"]."'";
			$row = mysqli_query($con,$sql);
			
			$data = array("url"=>"page_teacher_account.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );

		} else if (isset($_POST['edit'])) {
			edit($_POST['edit']);
		} else if(isset($_POST['del'])){
			delete();
			
			$data = array("url"=>"page_teacher_account.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );
		}
	}
?>