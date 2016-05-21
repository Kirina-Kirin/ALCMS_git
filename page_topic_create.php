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
	} else { header("Location: page_login.php"); }
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!--JS-->
	<script type="text/javascript">

		function submitNewTest(tid) {
			if(tid==1 && checkAptitudeCreate()){
				var testname = document.getElementById("testname").value;
				var proposition = document.getElementById("proposition").value;
				var first_choice = document.getElementById("first_choice").value;
				var second_choice = document.getElementById("second_choice").value;
				var third_choice = document.getElementById("third_choice").value;

				doAjaxRequest('system_topic_create.php', 'create_topic_button=','testname='+testname ,'proposition='+proposition
				,'first_choice='+first_choice,'second_choice='+second_choice,'third_choice='+third_choice);
			}
			else if(checkTopicCreate()){
				var testname = document.getElementById("testname").value;
				var proposition = document.getElementById("proposition").value;
				var first_choice = document.getElementById("first_choice").value;
				var second_choice = document.getElementById("second_choice").value;
				var third_choice = document.getElementById("third_choice").value;
				var fourth_choice = document.getElementById("fourth_choice").value;
				var radios = document.getElementsByName("answer");
				var answer = 0;
				for (var i = 0, len = radios.length; i < len; i++) {
					if (radios[i].checked) {
						answer = i;
					}
				}
				answer++;
				var answert = answer + "";
				doAjaxRequest('system_topic_create.php', 'create_topic_button=','testname='+testname ,'proposition='+proposition
				,'first_choice='+first_choice,'second_choice='+second_choice,'third_choice='+third_choice,'fourth_choice='+fourth_choice
				,'answer='+answert);
			}
		}
		
		function checkAptitudeCreate(){
			//CHECK TWO STEP!!
			if(document.getElementById("testname").value != "" &&
				document.getElementById("proposition").value != "" &&
				document.getElementById("first_choice").value != "" &&
				document.getElementById("second_choice").value != "" &&
				document.getElementById("third_choice").value != ""){
				return true;
			}
			return false;
		}

		function checkTopicCreate(){
			//CHECK TWO STEP!!
			if(document.getElementById("testname").value != "" &&
				document.getElementById("proposition").value != "" &&
				document.getElementById("first_choice").value != "" &&
				document.getElementById("second_choice").value != "" &&
				document.getElementById("third_choice").value != "" &&
				document.getElementById("fourth_choice").value != ""){
				return checkRadios();
			}
			return false;
		}
		
		function checkRadios(){
			var radios = document.getElementsByName("answer");

			for (var i = 0, len = radios.length; i < len; i++) {
				if (radios[i].checked) {
					return true;
				}
			}
			return false;
		}
	</script>
</head>

<body>
	<?php 
	$topicsData = mysqli_fetch_array($topics_db);
	echo $topicsData['Topic_Name']."";
	?>
	<br/>
	<br/>
	Test Name:<br/>
	<textarea rows="4" cols="50" id="testname" name="testname"></textarea><br/>

	Proposition:<br/>
	<textarea rows="4" cols="50" id="proposition" name="proposition"></textarea><br/>
	
	Choice 1: 
<?php if($_SESSION["topicid"]!=1)
echo '(Answer <input type="radio" name="answer" value="1">)';
?>	<br/>
	<textarea rows="4" cols="50" id="first_choice" name="first_choice"></textarea><br/>
	
	Choice 2: 
<?php if($_SESSION["topicid"]!=1)
echo '(Answer <input type="radio" name="answer" value="2">)';
?>	<br/>
	<textarea rows="4" cols="50" id="second_choice" name="second_choice"></textarea><br/>
	
	Choice 3: 
<?php if($_SESSION["topicid"]!=1)
echo '(Answer <input type="radio" name="answer" value="3">)';
?>	<br/>
	<textarea rows="4" cols="50" id="third_choice" name="third_choice"></textarea><br/>
<?php if($_SESSION["topicid"]!=1)	
	echo 'Choice 4: (Answer <input type="radio" name="answer" value="4">)<br/>
	<textarea rows="4" cols="50" id="fourth_choice" name="fourth_choice"></textarea><br/>';
?>	
	<br/>
	<input type="submit" onclick="submitNewTest(
	<?php echo $_SESSION["topicid"]; ?>
	);" value="Submit" /> <input type="button" onclick="backToPage('page_topic.php');" value="Cancel" />

</body>

</html>

<?php mysqli_close($con); ?>
