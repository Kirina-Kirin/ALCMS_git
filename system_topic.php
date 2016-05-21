<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	error_reporting(E_ERROR | E_PARSE);
	include "chgable_db_connect.php";
	
	if(isset($_POST['update_topic_button'])) {
		$sql = "UPDATE topics SET 
		Topic_Name='".$_POST['topicname']."', Academic_ID='".$_POST['topicAcademic']."',
		Topic_Desc='".$_POST['topicdesc']."', 
		Topic_Objective='".$_POST['topicobj']."', Weight='".$_POST['weight']."',
		IntermediateThreshold='".$_POST['intermediate']."', ExpertThreshold='".$_POST['expert']."'
		WHERE Topic_ID='".$_SESSION["topicid"]."'";
		$row = mysqli_query($con,$sql);
		if( mysqli_errno() == 1062) {
    // Duplicate key
			$data = array("msg"=>"This Topic ID is already used.","name"=>"error");
			header('Content-type: application/json');
			echo json_encode( $data );
		}else if(!$row){
			$data = array("msg"=>"Cannot insert data".mysqli_errno(),"name"=>"error");
			header('Content-type: application/json');
			echo json_encode( $data );
		}else{ 
			$file = file_get_contents('./ALCMS.owl', true);
			$dom = new DomDocument;
			$dom->loadXml($file);
			$uri = $dom->documentElement->lookupnamespaceURI(NULL);
			$xph = new DOMXPath($dom);

			$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
			$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
			$xph->registerNamespace('default', $uri);
			
			$qstr = '(//owl:NamedIndividual[default:tp_ID="Topic_'.$_POST['topicAcademic'].'"])';
			$tpName = $xph->query($qstr.'/default:tp_name');
			foreach($tpName as $name){
				$name->nodeValue = $_POST['topicname'];
			}
			$filename = './ALCMS.owl';
			file_put_contents($filename, $dom->saveXML());
			
			$data = array("url"=>"page_topiclist.php","name"=>"loadBack");
			header('Content-type: application/json');
			echo json_encode( $data);
			//header("Location:page_topic.php");
		}
	} else if(isset($_POST['edit_topic_button'])) {
		$_SESSION["testid"] = $_POST['testID'];
		
		$data = array("url"=>"page_topic_edit.php","name"=>"edit test");
		header('Content-type: application/json');
		echo json_encode( $data );
		//header("Location:page_topic_edit.php");
		
	} else if(isset($_POST['delete_topic_button'])) {
		$_SESSION["testid"] = $_POST['testID'];
		$sql = "UPDATE test_bank SET Deletion=1 WHERE Test_ID='".$_SESSION["testid"]."'";
		$row = mysqli_query($con,$sql);
		

		$test_bank_v_db = mysqli_query($con,"
			SELECT * FROM test_bank
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Deletion=0 AND Visibility=1
		");

		$topic = mysqli_query($con,"
			SELECT * FROM topics WHERE Topic_ID='".$_SESSION["topicid"]."'
		");

		$atopic = mysqli_fetch_array($topic);

		if(mysqli_num_rows($test_bank_v_db) < $atopic['Weight']) {
			$sql = "UPDATE topics SET 
			Weight='".mysqli_num_rows($test_bank_v_db)."'
			WHERE Topic_ID='".$_SESSION["topicid"]."'";
			$row = mysqli_query($con,$sql);
		}

		/*
		$sql = "DELETE FROM test_bank WHERE Test_ID='".$_SESSION["testid"]."'";
		$row = mysqli_query($con,$sql);
		*/

		$data = array("url"=>"page_topic.php","name"=>"redirect");
		header('Content-type: application/json');
		echo json_encode( $data );
	
		//header("Location:page_topic.php");
		
	} /*else if(isset($_POST['upload_material_button'])) {
		
		//echo $_SESSION["topicid"];
		
		header("Location:page_uploadmaterial.php");
	}*/ /*else if(isset($_POST['create_test_bank_button'])) {
		
		header("Location:page_topic_create.php");
		
	}*/  else if(isset($_POST['cbID'])) {		
		
		$sql = "UPDATE test_bank SET Visibility=".$_POST['check']." WHERE Test_ID='".$_POST['cbID']."'";
		$row = mysqli_query($con,$sql);

		$test_bank_v_db = mysqli_query($con,"
			SELECT * FROM test_bank
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Deletion=0 AND Visibility=1
		");

		$topic = mysqli_query($con,"
			SELECT * FROM topics WHERE Topic_ID='".$_SESSION["topicid"]."'
		");

		$atopic = mysqli_fetch_array($topic);

		if(mysqli_num_rows($test_bank_v_db) < $atopic['Weight']) {
			$sql = "UPDATE topics SET 
			Weight='".mysqli_num_rows($test_bank_v_db)."'
			WHERE Topic_ID='".$_SESSION["topicid"]."'";
			$row = mysqli_query($con,$sql);
		}
		
		$data = array("url"=>"page_topic.php","name"=>"loadBack");
		header('Content-type: application/json');
		echo json_encode( $data );
		//header("Location:page_topic.php");		
	}

	//mysqli_close($con);
?>