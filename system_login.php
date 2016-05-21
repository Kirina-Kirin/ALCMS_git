<?php 	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
?>
 <html>
    <head>
		<title>login</title>
		
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
		
<?php
		if(isset($_POST['username']) && isset($_POST['password'])){
			$result = mysqli_query($con,"SELECT Employee_ID, Employee_Password, Employee_Name, Employee_Lastname, Employee_Job
									FROM employee WHERE Employee_LoginName = '".$_POST['username']."' ");
			$row = mysqli_fetch_array($result);
			if(  $row[1] == $_POST['password']){
				
				$_SESSION['login'] =  $row[0];
				$_SESSION['loginName'] =  $row[2]." ".$row[3];
				$_SESSION['status'] = $row[4];
				mysqli_close($con);
				// I write this code for test teacher page without caring css of index page
				if($_SESSION['status']=="teacher")header("Location:#");
				header("Location:index.php");
				exit;	
			}
			$result = mysqli_query($con,"SELECT Student_ID, Student_Password, Student_Name, Student_Lastname
									FROM student WHERE Student_LoginName = '".$_POST['username']."' ");
			$row = mysqli_fetch_array($result);
			if(  $row[1] == $_POST['password']){
				
				$_SESSION['login'] =  $row[0];
				$_SESSION['loginName'] =  $row[2]." ".$row[3];
				$_SESSION['status'] = "student";
				mysqli_close($con);
				header("Location:index.php");
				exit;	
			}
		}
		mysqli_close($con);
		$_SESSION['error'] = true;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
			
?>
	</head>
	<body>
		<div class = "container">
			<header>
			</header>
		</div>
	</body>
</html>

