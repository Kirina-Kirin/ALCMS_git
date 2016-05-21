<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";

	if($_SESSION['status']=="teacher") {

	} else { header("Location: page_login.php"); }
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!--JS-->
	<script type="text/javascript">
		function checkTopiclistCreate(){
			//CHECK TWO STEP!!
			if(document.getElementById("topicname").value != ""){
				if(document.getElementById("intermediate").value == "" && document.getElementById("expert").value == ""){
					return true;
				}
				if(document.getElementById("intermediate").value != "" && document.getElementById("expert").value != ""){
					if(document.getElementById("intermediate").value <= document.getElementById("expert").value){
						if(document.getElementById("intermediate").value <= 100 && document.getElementById("expert").value <= 100){
							return true;
						}
					}
				}
			}
			return false;
		}
		
		function submitNewTopic(){
			if(checkTopiclistCreate()){
				var topicAcademic = document.getElementById("topicAcademic").value;
				var topicname = document.getElementById("topicname").value;
				var topicdesc = document.getElementById("topicdesc").value;
				var topicobj = document.getElementById("topicobj").value;
				var intermediate = document.getElementById("intermediate").value;
				var expert = document.getElementById("expert").value;
				doAjaxRequest('system_topiclist_create.php', 'create_topic_button=','topicAcademic='+topicAcademic,
				'topicname='+topicname,'topicdesc='+topicdesc,'topicobj='+topicobj,
				'intermediate='+intermediate,'expert='+expert);
			}
		}

		function notMoreThan100(id){
			if(document.getElementById(id).value>100) {
    			document.getElementById(id).value = 100;
			}
		}
	</script>
</head>

<body>
	<!--<form action="system_topiclist_create.php" method="post" onsubmit="return checkTopiclistCreate()">--> 
	Topic ID: <input type="text" id="topicAcademic" name="topicAcademic" value="" 
	onkeypress='return event.charCode >= 48 && event.charCode <= 57' required><br/>
	
	Topic Name: <input type="text" id="topicname" name="topicname" value="" required><br/>
	
	Topic Description:<br/>
	<textarea rows="4" cols="50" id="topicdesc" name="topicdesc"></textarea><br/>
	
	Topic Objective:<br/>
	<textarea rows="4" cols="50" id="topicobj" name="topicobj"></textarea><br/>
	
	Intermediate >= <input type="number" min="0" max="100" size="1" id="intermediate" name="intermediate" onchange="notMoreThan100(this.id)" value=""> %<br/>
	
	Expert >= <input type="number" min="0" max="100" size="1" id="expert" name="expert" onchange="notMoreThan100(this.id)" value=""> %<br/>
	<!--<input type="submit" name="create_topic_button" value="Submit" />--> 
	<input type="submit" onclick="submitNewTopic();" value="Submit" /> 
	<input type="button" onclick="backToPage('page_topiclist.php');" value="Cancel" />
	<!--</form>--> 
</body>

</html>

<?php mysqli_close($con); ?>
