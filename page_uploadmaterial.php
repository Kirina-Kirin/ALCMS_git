<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";

	if($_SESSION['status']=="teacher" && ISSET($_SESSION["topicid"])) {
		$topics_db = mysqli_query($con,"
			SELECT * FROM topics
			WHERE Topic_ID='".$_SESSION["topicid"]."'
		");
		/*$resDB = mysqli_query($con,"
			SELECT * FROM resource
			WHERE Topic_ID='".$_SESSION["topicid"]."'
		");*/
		$appDB = mysqli_query($con,"
			SELECT * FROM student_aptitude
		");
		/*$resLvDB = mysqli_query($con,"
			SELECT * FROM resource_level
		");*/
	} else { header("Location: page_login.php"); }
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!--JS-->
	<script type="text/javascript">
		function downloadMaterial(resAddr){
			alert(resAddr);
			//doMultipartAjaxRequest('system_uploadmaterial.php', 'download_material_button=', 'resAddr='+resAddr);
		}
	</script>
</head>

<body>
	<?php
	/* $appID
	 * 1 = VAK
	 * 2 = VKA
	 * 3 = AKV
	 * 4 = AVK
	 * 5 = KAV
	 * 6 = KVA 
	 */
	//$appID = 1;
	/* $resLvID
	 * 1 = L
	 * 2 = M
	 * 3 = H
	 */
	//$resLvID = 1;
	?>
	<?php 
	$topicsData = mysqli_fetch_array($topics_db);
	echo $topicsData['Topic_Name']." Materials";
	?>
	<br/>
	<br/>
	<table border="1">
		<tr><td>TYPE</td><td>Resource_Name</td><td>Resource_Address</td><td>Upload</td><td>Download</td></tr>
		<?php while ($appData = mysqli_fetch_array($appDB)) { ?>
			<?php for ($resLvID = 1; $resLvID <= 3; $resLvID++) { ?>
				<?php
				$resLvData = "";
				if($resLvID==1)	$resLvData = "L";
				else if($resLvID==2) $resLvData = "M";
				else if($resLvID==3) $resLvData = "H";
				?>
				<tr>
					<td> <?php echo $appData['Student_Aptitude_Level']."-".$resLvData; ?> </td>

					<td>
					<?php
					$resDB = mysqli_query($con,"
						SELECT * FROM resource
						WHERE Topic_ID='".$_SESSION["topicid"]."' AND Student_Aptitude_ID='".$appData['Student_Aptitude_ID']."' AND Resource_Level_ID='".$resLvID."'
					");
					$resData = mysqli_fetch_array($resDB);
					if(is_null($resData['Resource_Name'])){
						echo "No file uploaded";
					} else {
						echo $resData['Resource_Name'];
					}
					?>
					</td>
					
					<?php
					if(is_null($resData['Resource_Name'])){
						echo "<td> No file uploaded </td>";
					} else {
						echo "<td>".$resData['Resource_Address']."</td>";
					}
					?>
					
					<td>
					<form id="fileForm" name="fileForm" onsubmit="return doMultipartAjaxRequest(this,'system_uploadmaterial.php');" method="post" accept-charset="utf-8"> 
						<input type="file" name="file" id="file">
						<input type="submit" name="upload_material_button" value="Upload" />
						<?php 
						echo "<input type='text' name='appID' value='".$appData['Student_Aptitude_ID']."' hidden>";
						echo "<input type='text' name='resLvID' value='".$resLvID."' hidden>";
						if(is_null($resData['Resource_Name'])) {
							echo "<input type='text' name='command' value='insert' hidden>"; 
						}			
						?>
					</form>	
					</td>
					
					<td>
					
					
					<?php if($resData['Resource_Address']!='')echo '<a rel="nofollow" href="upload_material/'.$resData['Resource_Address'].'">Download here</a>'; 
					else echo'No file to download';?>
					<!--<form action="" method="post">
						<input type="submit" onclick="downloadMaterial(<?php echo $resData['Resource_Address']; ?>)" value="Download" />-->
						<!--<input type="submit" onclick="downloadMaterial(<?php if(!is_null($resData['Resource_Name'])){ echo $resData['Resource_Address']; } ?>)" value="Download" <?php if(is_null($resData['Resource_Name'])){ echo "disabled"; } ?> />
						-->
						<?php
						/*echo "<input type='submit' onclick='downloadMaterial(".$resData['Resource_Address'].") value='Download' ";
						if(is_null($resData['Resource_Name'])){ echo "disabled"; }
						echo " />";*/
						?>
					<!--</form>-->
					</td>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
</body>

</html>

<?php mysqli_close($con); ?>