<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	if(!isset($_SESSION['login'])){ //if login in session is not set
		header("Location: page_login.php");
	}
	if (isset($_GET['logout'])){
		session_start();
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);
		header("Location: page_login.php");
	}
	
	include "chgable_db_connect.php";
	if($_SESSION['status']=="student"){
		$result = mysqli_query($con,"SELECT Student_Aptitude_ID FROM student WHERE Student_ID = '".$_SESSION['login']."' ");
		$row = mysqli_fetch_array($result);
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Adaptive Learning Content Management System</title>
		
		<!--Web Icon-->
		<link rel="shortcut icon" href="image/faviconRT-blue.ico"/>
		
		<!--CSS-->
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<!--JS-->
		<!--<script type="text/javascript" src="script/jquery.js"></script>-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="script/mainScript.js"></script>
		<script type="text/javascript" src="script/studentResultScript.js"></script>
		
	</head>

	<body>
		<div id="main">
			<header id="logo"><img src="image/RMUTT.png">
			<div id="accinfo">
<?php
				echo "Welcome, ";
				if($_SESSION['status']=="student")echo $_SESSION['login']." ";
				echo $_SESSION['loginName'];
?>
				<br/> <a href="?logout">Logout&nbsp;</a>
			</div>
			</header>
			
			<nav id="navigator">
				<ul id="navigation" class="container">
				<?php 
					if($_SESSION['status']=="admin"){
					echo '<li><a href="page_student_account.php" rel="href_wrapper" name="Student" id="">&nbsp;Student&nbsp;</a> | </li>
					<li><a href="page_teacher_account.php" rel="home" name="Teacher">&nbsp;Teacher&nbsp;</a> | </li>';
					}
					if($_SESSION['status']=="student"){
						if($row[0]<=0) echo '<li><a href="page_pretest.php" rel="home" name="Pretest"></a></li>';
						else echo '<li><a href="page_student_result.php" rel="home" name="Result"></a></li>';
					}
				?>
				</ul>
				<ul id="linkPath" class="container">
				<?php 
					if($_SESSION['status']=="teacher"){
						// should change href link to teacher's topic list page
						echo '<li><a href="page_topiclist.php" rel="home" name="Topics">topiclist</a></li>';
					}
					if($_SESSION['status']=="admin"){
						echo '<li><a href="page_teacher_account.php" rel="home" name="Teacher">Teacher</a></li>';
					}
				?>
				</ul>
			</nav>
			<br style="clear: left" />
			<section id="wrapper"> 	
				Loading please wait...
			</section>
			
			<footer>
				<span>
					Copyright &copy; 2015 Adaptive Content Learning Management System, All right reserved.</br> 
					Contact us <a href="#"> >>> click <<< </a>.
				</span>
			</footer>
		</div>	
	
		<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>

	</body>
</html>