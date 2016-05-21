<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	
	include "chgable_db_connect.php";
	
	if(isset($_POST['create_topic_button'])) {
		if($_SESSION["topicid"]!=1){
			$sql = "INSERT INTO test_bank (Topic_ID, Test_Name, Proposition, Choice_1, Choice_2, Choice_3, Choice_4, Answer) 
			VALUES ('".$_SESSION["topicid"]."','".$_POST['testname']."','".$_POST['proposition']."','".$_POST['first_choice']."',
			'".$_POST['second_choice']."','".$_POST['third_choice']."','".$_POST['fourth_choice']."','".$_POST['answer']."')";
		}else{
			$sql = "INSERT INTO test_bank (Topic_ID, Test_Name, Proposition, Choice_1, Choice_2, Choice_3) 
			VALUES ('".$_SESSION["topicid"]."','".$_POST['testname']."','".$_POST['proposition']."','".$_POST['first_choice']."',
			'".$_POST['second_choice']."','".$_POST['third_choice']."')";
		}
		$row = mysqli_query($con,$sql);
		
		$data = array("url"=>"page_topic.php","name"=>"loadBack");
		header('Content-type: application/json');
		echo json_encode( $data );
		//header("Location:page_topic.php");
	}
		
	//mysqli_close($con);
?>