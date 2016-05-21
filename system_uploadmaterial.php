<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	
	include "chgable_db_connect.php";
	
	if(isset($_POST['upload_material_button'])) {
		/*echo $_SESSION["topicid"];
		echo $_POST['appID'];
		echo $_POST['resLvID'];*/
		$filename = $_FILES["file"]["name"];
		$splitfilename = explode(".", $filename);
		$filetype = $splitfilename[(sizeof($splitfilename)-1)];

		$appID = "";
		if($_POST['appID']==1) {
			$appID = "VAK";
		} else if($_POST['appID']==2) {
			$appID = "VKA";
		} else if($_POST['appID']==3) {
			$appID = "AKV";
		} else if($_POST['appID']==4) {
			$appID = "AVK";
		} else if($_POST['appID']==5) {
			$appID = "KAV";
		} else if($_POST['appID']==6) {
			$appID = "KVA";
		} 

		$resLvID = "";
		if($_POST['resLvID']==1) {
			$resLvID = "L";
		} else if($_POST['resLvID']==2) {
			$resLvID = "M";
		} else if($_POST['resLvID']==3) {
			$resLvID = "H";
		}
		
		$result = mysqli_query($con,"SELECT Academic_ID FROM topics WHERE Topic_ID = '".$_SESSION["topicid"]."'");
		$acid = mysqli_fetch_array($result);
		$acedemicId = str_pad("".$acid[0], 2, "0", STR_PAD_LEFT);
		$newfilename = "Package_".$appID.$resLvID."_".$acedemicId;
		$filename = $newfilename.".".$filetype;
		
		$target_dir = "upload_material/";
		$target_file = $target_dir . basename($filename);
		$uploadOk = 1;
		
		if($_FILES['file']['error']==UPLOAD_ERR_NO_FILE) {
			echo "There is no chosen file.";
			$uploadOk = 0;
		} else {			
			// Allow certain file formats
			if($filetype != "zip" && $filetype != "rar" ) {
				echo "Sorry, only ZIP & RAR files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "\nSorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
					echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
					//echo "The file ". $filename. " has been uploaded.";
					//INSERT
					if(isset($_POST['command'])) {
						$sql = "INSERT INTO resource (Resource_Name, Resource_Address, Topic_ID, Student_Aptitude_ID, Resource_Level_ID) 
						VALUES ('".basename( $_FILES["file"]["name"])."','".$filename."','".$_SESSION["topicid"]."','".$_POST['appID']."','".$_POST['resLvID']."')";
						$row = mysqli_query($con,$sql);
					}
					//UPDATE
					else {
						$sql = "UPDATE resource SET Resource_Name='".basename( $_FILES["file"]["name"])."' , Resource_Address='".$filename."'
						WHERE Topic_ID='".$_SESSION['topicid']."' AND Student_Aptitude_ID='".$_POST['appID']."' AND Resource_Level_ID='".$_POST['resLvID']."'";
						$row = mysqli_query($con,$sql);
					}
					// update owl file
					$file = file_get_contents('./ALCMS.owl', true);
					$dom = new DomDocument;
					$dom->loadXml($file);
					$uri = $dom->documentElement->lookupnamespaceURI(NULL);
					$xph = new DOMXPath($dom);
					$xph->registerNamespace('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
					$xph->registerNamespace('owl', "http://www.w3.org/2002/07/owl#");
					$xph->registerNamespace('default', $uri);
					$qxph = '//owl:NamedIndividual[default:cp_topic="Topic_'.$acedemicId.'" and default:cp_aptitude="'.$appID.
					'" and default:cp_level="'.$resLvID.'"]';
					$pkPath = $xph->query($qxph.'/default:cp_address');
					$pkName = $xph->query($qxph.'/default:cp_name');
					foreach($pkPath as $path){
						foreach($pkName as $name){
							$path->nodeValue = $target_dir;
							$name->nodeValue = $filename;
						}
					}
					file_put_contents('./ALCMS.owl', $dom->saveXML());
				} else {
					echo "\nSorry, there was an error uploading your file.";
				}
			}
		}
		
		//header("Location:page_uploadmaterial.php");
		
	} else if(isset($_POST['download_material_button'])) {
		$filePath = '/upload_material/'.$_GET['resAddr'];
		if(file_exists($filePath)) {
			$fileName = basename($filePath);
			$fileSize = filesize($filePath);
			// Output headers.
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Type: application/stream");
			header("Content-Length: ".$fileSize);
			header("Content-Disposition: attachment; filename=".$fileName);
			header("Content-Transfer-Encoding: binary ");
			// Output file.
			readfile ($filePath);                   
			exit();
		}
	}
?>