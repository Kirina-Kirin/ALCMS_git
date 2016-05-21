<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	error_reporting(E_ERROR | E_PARSE);
	include "chgable_db_connect.php";
	
	if(isset($_POST['edit_button'])) {
		$_SESSION["topicid"] = $_POST['topicID'];
		//header("Location:page_topic.php");
		$topic = mysqli_query($con,"
			SELECT Topic_Name FROM topics WHERE Topic_ID='".$_SESSION["topicid"]."'
		");
		$topicName = mysqli_fetch_array($topic);
		$data = array("url"=>"page_topic.php","name"=>"Edit: ".$topicName[0]);
		
		header('Content-type: application/json');
		echo json_encode( $data );
		
	} else if(isset($_POST['delete_button'])) {
		$_SESSION["topicid"] = $_POST['topicID'];
		$topic = mysqli_query($con,"
			SELECT Academic_ID FROM topics WHERE Topic_ID='".$_SESSION["topicid"]."'
		");
		$atopic = mysqli_fetch_array($topic);
		
		$sql = "UPDATE topics SET Deletion=1, Academic_ID=NULL WHERE Topic_ID='".$_SESSION["topicid"]."'";
		$row = mysqli_query($con,$sql);
		
			$file = file_get_contents('./ALCMS.owl', true);
			$dom = new DomDocument;
			$dom->loadXml($file);
			$uri = $dom->documentElement->lookupnamespaceURI(NULL);
			$xph = new DOMXPath($dom);

			$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
			$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
			$xph->registerNamespace('default', $uri);
			
			$qstr = '(//owl:NamedIndividual[default:cp_topic="Topic_'.$atopic['Academic_ID'].'"])';
			$tpPackages = $xph->query($qstr);
			foreach($tpPackages as $tpPackage){
				$tpPackage->parentNode->removeChild($tpPackage);
			}
			$qstr = '(//owl:NamedIndividual[default:tp_ID="Topic_'.$atopic['Academic_ID'].'"])';
			$tpTopics = $xph->query($qstr);
			foreach($tpTopics as $tpTopic){
				$tpTopic->parentNode->removeChild($tpTopic);
			}
			$filename = './ALCMS.owl';
			file_put_contents($filename, $dom->saveXML());
		
		$data = array("url"=>"page_topiclist.php","name"=>"redirect");
		header('Content-type: application/json');
		echo json_encode( $data );
		
	} else if(isset($_POST['cbID'])) {		
		
		$sql = "UPDATE topics SET Visibility=".$_POST['check']." WHERE Topic_ID='".$_POST['cbID']."'";
		$row = mysqli_query($con,$sql);
		
		$data = array("url"=>"page_topiclist.php","name"=>"redirect");
		header('Content-type: application/json');
		echo json_encode( $data );
		//header("Location:page_topiclist.php");		
	}
	
	//mysqli_close($con);
?>