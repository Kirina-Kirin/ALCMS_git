<?php

	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
	
	function editstu() {
		$_SESSION["editid"] = $_POST['editstu'];

		$data = array("url"=>"page_student_form.php","name"=>"edit student account");
		header('Content-type: application/json');
		echo json_encode( $data );
	}

	function deletestu() {
		$sql = "DELETE FROM student WHERE Student_ID='".$_POST['delstu']."'";
		$row = mysqli_query($con,$sql);
	}

	if($_SESSION['status']=="admin"){
		if(isset($_POST['admin_edit_student_button'])) {

			$sql = "UPDATE student SET 
			Student_Name='".$_POST['namesignup']."', Student_Lastname='".$_POST['snamesignup']."', 
			Student_Tel='".$_POST['telsignup']."', Student_Email='".$_POST['emailsignup']."',
			Student_LoginName='".$_POST['usernamesignup']."', Student_Password='".$_POST['passwordsignup']."'
			WHERE Student_ID='".$_SESSION["editid"]."'";
			$row = mysqli_query($con,$sql);
	
			$data = array("url"=>"page_student_account.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );

		} else if (isset($_POST['editstu'])) {
			editstu($_POST['editstu']);
		} else if(isset($_POST['delstu'])){
			deletestu();
			
			$data = array("url"=>"page_student_account.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );
		}
	}
?>