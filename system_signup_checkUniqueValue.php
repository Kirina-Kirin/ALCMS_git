<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";


	if(isSet($_POST['username'])){	
		$username = $_POST['username'];
		$sql_check = mysqli_query($con,"select Student_LoginName from student where Student_LoginName='".$username."'") or die(mysqli_error());
		
		if(mysqli_num_rows($sql_check)){
			echo '<font color="red">This login name is already in use.</font>';
		}else{
			$sql_check = mysqli_query($con,"select Employee_LoginName from employee where Employee_LoginName='".$username."'") or die(mysqli_error());
			if(mysqli_num_rows($sql_check)){
				echo '<font color="red">This login name is already in use.</font>';
			}else echo 'OK';
		}
	}
	
	if(isSet($_POST['id'])){	
		$id = $_POST['id'];
		$sql_check = mysqli_query($con,"select Student_ID from student where Student_ID='".$id."'") or die(mysqli_error());
		if(mysqli_num_rows($sql_check)){
			echo '<font color="red">This ID is already in use.</font>';
		}else{
			$sql_check = mysqli_query($con,"select Employee_ID from employee where Employee_ID='".$id."'") or die(mysqli_error());
			if(mysqli_num_rows($sql_check)){
				echo '<font color="red">This ID is already in use.</font>';
			}else echo 'OK';
		}
	}
	
	if(isSet($_POST['email'])){	
		$email = $_POST['email'];
		$sql_check = mysqli_query($con,"select Student_Email from student where Student_Email='".$email."'") or die(mysqli_error());
		if(mysqli_num_rows($sql_check)){
			echo '<font color="red">This email is already in use.</font>';
		}else{
			$sql_check = mysqli_query($con,"select Employee_Email from employee where Employee_Email='".$email."'") or die(mysqli_error());
			if(mysqli_num_rows($sql_check)){
				echo '<font color="red">This email is already in use.</font>';
			}else echo 'OK';
		}
	}

	if(isSet($_POST['ide'])&&isSet($_POST['emaile'])){
		$id = $_POST['ide'];
		$email = $_POST['emaile'];
		$sql_check = mysqli_query($con,"select Student_Email from student where Student_Email='".$email."'") or die(mysqli_error());
		if(mysqli_num_rows($sql_check)){
			error_log($id, 0);
			$sql_check = mysqli_query($con,"select Student_Email from student where Student_ID='".$id."' AND Student_Email='".$email."'") or die(mysqli_error());
			if(mysqli_num_rows($sql_check)) {
				echo 'OK';
			}
			else echo '<font color="red">This email is already in use.</font>';
		}else{
			$sql_check = mysqli_query($con,"select Employee_Email from employee where Employee_Email='".$email."'") or die(mysqli_error());
			if(mysqli_num_rows($sql_check)){
				$sql_check = mysqli_query($con,"select Employee_Email from employee where Employee_ID='".$id."' AND Employee_Email='".$email."'") or die(mysqli_error());
				if(mysqli_num_rows($sql_check)) {
					echo 'OK';
				}
				else echo '<font color="red">This email is already in use.</font>';
			}else echo 'OK';
		}
	}
?>