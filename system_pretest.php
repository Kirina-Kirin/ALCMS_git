<?php ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
function write($msg) {
   // echo "<br/>$msg<br/>";
}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		print_r($_POST);
		$apResult=array(0,0,0);
		write("");
		include "chgable_db_connect.php";
		 foreach ($_POST as $topicQuestion => $ans) {// [t?q?] => ans
			if($topicQuestion[0]=='t'){
				$replacedLetters = array('t', 'q');
				$output  = str_replace($replacedLetters, ' ', $topicQuestion);
				$arrTQ = explode(" ", $output);
				write("Topic ".$arrTQ[1]." Q.".$arrTQ[2]);
				if(!isset($topic[$arrTQ[1]]))$topic[$arrTQ[1]]=0;
				include "chgable_db_connect.php";
				$q = mysqli_query($con,"SELECT Answer FROM test_bank WHERE Test_ID = ".$arrTQ[2]);
				if($q === FALSE) { die(mysqli_error()); }
				$correctAns = mysqli_fetch_array($q);
				write("ans ".$correctAns[0]." rev ".$ans);
				if($correctAns[0]==$ans)$topic[$arrTQ[1]]++;
			}else if(strpos($topicQuestion,'ap') !== false){
				$apResult[($_POST[$topicQuestion]-1)]+=1;
			}
		}
		print_r($topic);
		write("");
		arsort($apResult);
		$aptType ="";
		foreach ($apResult as $key => $value) {
			write("Ap ".$key." = ".$value);
			if($key==0) $aptType.="V";
			else if($key==1) $aptType.="A";
			else if($key==2) $aptType.="K";
		}
		$sql = "SELECT Student_Aptitude_ID FROM student_aptitude WHERE Student_Aptitude_Level = '".$aptType."'";
		$q = mysqli_query($con,$sql);
		$aptId = mysqli_fetch_array($q);
					
		write("aptType ".$aptType." id ".$aptId[0]);
		foreach ($topic as $tid => $score) {
			$sql = "SELECT * FROM learning_records WHERE Student_ID = '".$_POST["id"]."' AND 
						Topic_ID = ".$tid;
			$q = mysqli_query($con,$sql);
write("Test come here".mysqli_num_rows($q));
			if (mysqli_num_rows($q) == 0) {
write("Test come here");
				$sql = "SELECT Weight, IntermediateThreshold, ExpertThreshold FROM  topics WHERE Topic_ID = ".$tid;
				$q =mysqli_query($con,$sql);
				$topicQuery = mysqli_fetch_array($q);
				
				$apt_Lv="L";
				if(($score*100.0/$topicQuery['Weight'])>=$topicQuery['IntermediateThreshold'])$apt_Lv="M";
				if(($score*100.0/$topicQuery['Weight'])>=$topicQuery['ExpertThreshold'])$apt_Lv="H";
				
				$sql = "INSERT INTO learning_records (Student_ID, Topic_ID, Resource_ID, Score, Max_Score, 	KnowledgeLevel)
						VALUES ('".$_POST["id"]."', ".$tid.", 1,".$score.",".$topicQuery['Weight'].",'".$apt_Lv."')";
write("student:".$_POST["id"]." topic:".$tid." score:".$score);
				$q =mysqli_query($con,$sql);
			}
		}
						
		$sql =	"UPDATE  student SET Student_Aptitude_ID=".$aptId[0]." WHERE Student_ID= '".$_POST["id"]."'";
		write($sql);
		$q =mysqli_query($con,$sql);
	}
	mysqli_close($con);
	header("Location: index.php");
?>
<html>
    <head>
		<title>pretest</title>
	</head>
	<body>
		<header>
		</header>





	</body>
</html>
