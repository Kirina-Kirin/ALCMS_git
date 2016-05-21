<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	
	include "chgable_db_connect.php";
	
	if(isset($_POST['update_topic_button'])) {
		if($_SESSION["topicid"]!=1){
			$sql = "UPDATE test_bank SET 
			Test_Name='".$_POST['testname']."', 
			Proposition='".$_POST['proposition']."', Choice_1='".$_POST['first_choice']."', 
			Choice_2='".$_POST['second_choice']."', Choice_3='".$_POST['third_choice']."',
			Choice_4='".$_POST['fourth_choice']."', Answer='".$_POST['answer']."'
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Test_ID='".$_SESSION["testid"]."'";
		}else{
			$sql = "UPDATE test_bank SET 
			Test_Name='".$_POST['testname']."',
			Proposition='".$_POST['proposition']."', Choice_1='".$_POST['first_choice']."', 
			Choice_2='".$_POST['second_choice']."', Choice_3='".$_POST['third_choice']."'
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Test_ID='".$_SESSION["testid"]."'";
		}
		$row = mysqli_query($con,$sql);
		if(!$row){
			$data = array("msg"=>"Cannot insert data","name"=>"error");
			header('Content-type: application/json');
			echo json_encode( $data );
		}else{
			$data = array("url"=>"page_topic.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data );
			//header("Location:page_topic.php");
		}
	}
	
	//mysqli_close($con);
?>