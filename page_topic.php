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
		$test_bank_db = mysqli_query($con,"
			SELECT * FROM test_bank
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Deletion=0
		");
		$test_bank_v_db = mysqli_query($con,"
			SELECT * FROM test_bank
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Deletion=0 AND Visibility=1
		");
	} else { header("Location: page_login.php"); }
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!--CSS-->
	<style>
	#edit {
		background-image: url(image/Edit.png);
		background-size: contain;
		background-repeat: no-repeat;
	}
	#del {
		background-image: url(image/Delete.png);
		background-size: contain;
		background-repeat: no-repeat;
	}
	</style>
	<!--JS-->
	<script type="text/javascript">

		function submitEditTopic(maxweight, topicid){
			if(topicid==1) {
				var topicname = document.getElementById("topicname").value;
				var topicdesc = document.getElementById("topicdesc").value;
				var topicobj = document.getElementById("topicobj").value;
				var weight = 0;
				var intermediate = 0;
				var expert = 0;
				doAjaxRequest('system_topic.php', 'update_topic_button=','topicname='+topicname,'weight='+weight
				,'topicdesc='+topicdesc,'topicobj='+topicobj,'intermediate='+intermediate,'expert='+expert);
			} else if(checkTopiclistUpdate(maxweight)) {
				//alert("Updated");
				var topicAcademic = document.getElementById("topicAcademic").value;
				var topicname = document.getElementById("topicname").value;
				var topicdesc = document.getElementById("topicdesc").value;
				var topicobj = document.getElementById("topicobj").value;
				var weight = document.getElementById("weight").value;
				var intermediate = document.getElementById("intermediate").value;
				var expert = document.getElementById("expert").value;
				doAjaxRequest('system_topic.php', 'update_topic_button=','topicAcademic='+topicAcademic,
				'topicname='+topicname,'weight='+weight,'topicdesc='+topicdesc,'topicobj='+topicobj,
				'intermediate='+intermediate,'expert='+expert);
			}
		}

		function checkTopiclistUpdate(maxweight){
			//CHECK TWO STEP!!
			if(document.getElementById("topicname").value != ""){
				if(document.getElementById("intermediate").value == "" && document.getElementById("expert").value == ""){
					if(document.getElementById("weight").value <= maxweight){
						return true;
					}
				}
				if(document.getElementById("intermediate").value != "" && document.getElementById("expert").value != ""){
					if(document.getElementById("intermediate").value <= document.getElementById("expert").value){
						if(document.getElementById("intermediate").value <= 100 && document.getElementById("expert").value <= 100){
							if(document.getElementById("weight").value <= maxweight){
								return true;
							}
						}
					}
				}
			}
			return false;
		}
		
		function checkboxIsCheck(id){			
			var testbankid = id.split("_");
			
			if(document.getElementById(id).checked){
				//post('system_topic.php', {cbID: testbankid[1], check: "1"});
				doAjaxRequest('system_topic.php', 'check=1','cbID='+testbankid[1]);
			} else {
				//post('system_topic.php', {cbID: testbankid[1], check: "0"});
				doAjaxRequest('system_topic.php', 'check=0','cbID='+testbankid[1]);
			}
		}

		function notMoreThan100(id){
			if(document.getElementById(id).value>100) {
    			document.getElementById(id).value = 100;
			}
		}
		
		//USE=> post('/contact/', {name: 'Johnny', surname: 'Bravo'});
		function post(path, params, method) {
			method = method || "post"; // Set method to post by default if not specified.

			// The rest of this code assumes you are not using a library.
			// It can be made less wordy if you use one.
			var form = document.createElement("form");
			form.setAttribute("method", method);
			form.setAttribute("action", path);

			for(var key in params) {
				if(params.hasOwnProperty(key)) {
					var hiddenField = document.createElement("input");
					hiddenField.setAttribute("type", "hidden");
					hiddenField.setAttribute("name", key);
					hiddenField.setAttribute("value", params[key]);

					form.appendChild(hiddenField);
				 }
			}

			document.body.appendChild(form);
			form.submit();
		}
		
		function delTest(del_id) {
			var confirmDel = confirm("Press OK to delete");
			if(confirmDel){
				doAjaxRequest('system_topic.php', 'delete_topic_button=','testID='+del_id);
				//return true;
			}
			//return false;
			return false;
		}
	</script>
</head>

<body>
	<!--<form action="system_topic.php" method="post" onsubmit="return checkTopiclistUpdate(<?php //echo mysqli_num_rows($test_bank_db); ?>)">--> 
	<?php $topic = mysqli_fetch_array($topics_db); 
	if($topic['Topic_ID']!=1)
	//echo 'Topic ID: <input type="text" id="topicAcademic" name="topicAcademic"
			//onkeypress=\'return event.charCode >= 48 && event.charCode <= 57\' value="'.$topic['Academic_ID'].'"><br/>';
		echo 'Topic ID: <input type="text" id="topicAcademic" name="topicAcademic" value="'.$topic['Academic_ID'].'" readonly><br/>';
	?>
	Topic Name: <input type="text" id="topicname" name="topicname" value="<?php echo $topic['Topic_Name']; ?>"><br/>
	
	Topic Description:<br/>
	<textarea rows="4" cols="50" id="topicdesc" name="topicdesc"><?php echo $topic['Topic_Desc']; ?></textarea><br/>
	
	Topic Objective:<br/>
	<textarea rows="4" cols="50" id="topicobj" name="topicobj"><?php echo $topic['Topic_Objective']; ?></textarea><br/>
	
	<?php if($topic['Topic_ID']!=1) { ?>
	Number of Question: <input type="number" min="0" max="<?php echo mysqli_num_rows($test_bank_v_db); ?>" size="1" id="weight" name="weight" value="<?php echo $topic['Weight']; ?>"> / Total Question: <?php echo mysqli_num_rows($test_bank_v_db); ?><br/>
	
	Intermediate >= <input type="number" min="0" max="100" size="1" id="intermediate" name="intermediate" onchange="notMoreThan100(this.id)" value="<?php echo $topic['IntermediateThreshold']; ?>"> %<br/>
	
	Expert >= <input type="number" min="0" max="100" size="1" id="expert" name="expert" onchange="notMoreThan100(this.id)" value="<?php echo $topic['ExpertThreshold']; ?>"> %<br/>
	<?php } ?>
	<input type="submit" onclick="submitEditTopic(<?php echo mysqli_num_rows($test_bank_v_db); ?>, <?php echo $topic['Topic_ID']; ?>)" value="Submit" /> <input type="button" onclick="backToPage('page_topic.php');" value="Reset" /> <input type="button" onclick="backToPage('page_topiclist.php');" value="Cancel" />
	<!--<input type="submit" name="update_topic_button" value="Update" /> <input type="button" onclick="location.href='page_topiclist.php';" value="Cancel" />--> 
	<!--</form>--> 
	<?php if($topic['Topic_ID']!=1) { ?>
	<?php if(mysqli_num_rows($test_bank_db)> 0){ ?>
	<hr/>
	
	<!--<form action="system_topic.php" method="post">-->
		<!--Click here to >>> <input type="submit" name="upload_material_button" value="Upload Material" />-->
		Click here to >>> <input type="button" onclick="loadNewPage('page_uploadmaterial.php', 'Upload Material');" value="Upload Material" />
	<!--</form>--> 
	<?php } ?>
	<?php } ?>
	<hr/>
	<!--<form action="system_topic.php" method="post">-->
		<!--<input type="submit" name="create_test_bank_button" value="Create Test Bank" />-->
		<input type="button" onclick="loadNewPage('page_topic_create.php', 'Create Test Bank');" value="Create Test Bank" />
	<!--</form>-->
	
	<?php if(mysqli_num_rows($test_bank_db)> 0){ ?>
	<table border="1">
	<?php while ($test_banks = mysqli_fetch_array($test_bank_db)) { ?>
	<tr>
		<?php 
		$isCheckbox = "";
		if($test_banks['Visibility']) {
			$isCheckbox = "checked";
		}
		?>
		<td> <?php echo '<input type="checkbox" id="cb_'.$test_banks['Test_ID'].'" value="'.$test_banks['Test_ID'].'" onclick="checkboxIsCheck(this.id)" '.$isCheckbox.'/>'; ?> </td>
		
		<td>
			<?php $edit_topic_param = "'system_topic.php','edit_topic_button=','testID=".$test_banks['Test_ID']."'"; ?>
			<a href="javascript:doAjaxRequest(<?php echo $edit_topic_param; ?>);">
			<?php echo $test_banks['Test_Name']; ?>
			</a>
		</td>

		<!--<td>
			<form action="system_topic.php" method="post">
			<?php //echo '<input id="edit" type="submit" name="edit_topic_button" value="" />'; 
			//echo "<input type='text' name='testID' value='".$test_banks['Test_ID']."' hidden>"; ?>
			</form>
		</td>
		-->
		<td> 
			<!--<form action="system_topic.php" method="post" onsubmit="return delTest()">
			<?php /*echo '<input id="del" type="submit" name="delete_topic_button" value="" />'; 
			echo "<input type='text' name='testID' value='".$test_banks['Test_ID']."' hidden>";*/ ?>
			</form>-->
			<a href="javascript:delTest(<?php echo $test_banks['Test_ID']; ?>);">
			<img src="image/Delete.png" height="20em" alt="" />
			</a>
		</td>
	</tr>
	<?php } ?>
	</table>
	<?php } ?>
</body>

</html>

<?php mysqli_close($con); ?>