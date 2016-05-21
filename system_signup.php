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
		<title>sign up</title>
		
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
		
	</head>
	<body>
		<div class = "container">
			<header>
<?php
	if(isset($_POST['usernamesignup']))
	{
		$stid = $_POST['idsignup'];
		$name = $_POST['namesignup'];
		$sname = $_POST['snamesignup'];
		$tel = $_POST['telsignup'];
		$email = $_POST['emailsignup'];
		$uname = $_POST['usernamesignup'];
		$pass = $_POST['passwordsignup'];
		$passcon = $_POST['passwordsignup_confirm'];
		
		if(	$_POST['idsignup']=='' || $_POST['namesignup']=='' || $_POST['snamesignup']=='' ||
			$_POST['telsignup']=='' || $_POST['emailsignup']=='' ||
			$_POST['usernamesignup']=='' || $_POST['passwordsignup']=='' || 
			$_POST['passwordsignup_confirm']==''||$_POST['status']=='')
		{
			$_SESSION['Error_signup']=true;
		}else {
			if($_POST['status']=='student')$sql="insert into student(Student_ID,Student_Name,Student_Lastname,Student_Tel,Student_Email,Student_LoginName,Student_Password) 
			values('".$_POST['idsignup']."', '".$_POST['namesignup']."', '".$_POST['snamesignup']."', '".$_POST['telsignup']."', '".
					$_POST['emailsignup']."', '".$_POST['usernamesignup']."', '".$_POST['passwordsignup']."')";
			else $sql="insert into employee(Employee_ID,Employee_Name,Employee_Lastname,Employee_Tel,Employee_Email,Employee_LoginName,Employee_Password,Employee_Job) 
			values('".$_POST['idsignup']."', '".$_POST['namesignup']."', '".$_POST['snamesignup']."', '".$_POST['telsignup']."', '".
					$_POST['emailsignup']."', '".$_POST['usernamesignup']."', '".$_POST['passwordsignup']."', '".$_POST['status']."')";
			$res=mysqli_query($con,$sql);
			if($res){
				$_SESSION['Success_signup']=true;
			}else{
				$_SESSION['Error_signup']=true;
				$_SESSION['Error_msg']=mysqli_errno($con);
			}
		}
	}
	else
	{
		$_SESSION['Error_signup']=true;
	}
	
	if($_SESSION['status']=="admin")  header("Location:index.php"); 
	else header("Location:page_login.php");
	exit;
	//session_destroy(); 
?> 
			</header>
		</div>
	</body>
</html>