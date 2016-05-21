<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";

	if($_SESSION['status']=="teacher") {
		$result = mysqli_query($con,"
			SELECT * FROM topics WHERE Deletion=0
		");
		/*$result = mysqli_query($con,"SELECT t.Topic_Name,r.Resource_Address,r.Resource_Name,l.Score,l.Max_Score 
		FROM learning_records l 
		LEFT JOIN topics t ON l.Topic_ID = t.Topic_ID
		LEFT JOIN resource r ON l.Resource_ID = r.Resource_ID
		WHERE l.Student_ID = '".$_SESSION['login']."' ");*/
	} else { header("Location: page_login.php"); }
?>

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
	
		function checkboxIsCheck(id){			
			var topicid = id.split("_");
			
			if(document.getElementById(id).checked){
				//post('system_topiclist.php', {cbID: topicid[1], check: "1"});
				doAjaxRequest('system_topiclist.php', 'check=1','cbID='+topicid[1]);
			} else {
				//post('system_topiclist.php', {cbID: topicid[1], check: "0"});
				doAjaxRequest('system_topiclist.php', 'check=0','cbID='+topicid[1]);
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
		
		function editTopic() {
			return true;
		}

		function delTopic(del_id) {
			var confirmDel = confirm("Press OK to delete");
			if(confirmDel){
				doAjaxRequest('system_topiclist.php', 'delete_button=','topicID='+del_id);
			}
			return;
		}
		
	</script>

	<!--<input type="button" onclick="location.href='page_topiclist_create.php';" value="Create New" />-->
	<input type="button" onclick="loadNewPage('page_topiclist_create.php', 'Create New Topic');" value="Create New" />

	<br>
	<br>
	<table border="1">
		<?php while ($topics = mysqli_fetch_array($result)) { ?>
		<tr>
			<?php 
			$isCheckbox = "";
			if($topics['Visibility']) {
				$isCheckbox = "checked";
			}
			?>

			<td> 
				<?php if($topics['Topic_ID']!=1) { ?>
				<?php echo '<input type="checkbox" id="cb_'.$topics['Topic_ID'].'" value="'.$topics['Topic_ID'].'" onclick="checkboxIsCheck(this.id)" '.$isCheckbox.'/>'; ?> 
				<?php } ?>
			</td>
			
			<td> <?php 
				$edit_param = "'system_topiclist.php','edit_button=','topicID=".$topics['Topic_ID']."'";
				?>
				<a href="javascript:doAjaxRequest(<?php echo $edit_param; ?>);">
				<?php 
					if($topics['Topic_ID']!=1)echo "[".$topics['Academic_ID']."] "; 
					echo $topics['Topic_Name']; 
				?> 
				</a>
			</td>
			<td>
				<?php if($topics['Topic_ID']!=1) { ?>
				Number of Question: <?php echo $topics['Weight']; ?>
				<?php } ?>
			</td> 
			<td>
				<?php 
				$test_bank_v_db = mysqli_query($con,"
					SELECT * FROM test_bank
					WHERE Topic_ID='".$topics['Topic_ID']."' AND Deletion=0 AND Visibility=1
				");
				?>
				Total Question: <?php echo mysqli_num_rows($test_bank_v_db); ?>
			</td>
			<td> 
				<?php if($topics['Topic_ID']!=1) { ?>
				<!--<a href="javascript:doAjaxRequest(<?php //echo $del_param; ?>);">-->
				<a href="javascript:delTopic(<?php echo $topics['Topic_ID']; ?>);">
				<img src="image/Delete.png" height="20em" alt="" />
				</a>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
	</table>


<?php mysqli_close($con); ?>