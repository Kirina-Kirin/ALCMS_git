<?php
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";

	if($_SESSION['status']=="teacher" && ISSET($_SESSION["topicid"]) && ISSET($_SESSION["testid"])) {
		$topics_db = mysqli_query($con,"
			SELECT * FROM topics
			WHERE Topic_ID='".$_SESSION["topicid"]."'
		");
		$test_bank_db = mysqli_query($con,"
			SELECT * FROM test_bank
			WHERE Topic_ID='".$_SESSION["topicid"]."' AND Test_ID='".$_SESSION["testid"]."'
		");
	} else { header("Location: page_login.php"); }
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!--JS-->
	<script type="text/javascript">

		function updateTest(tid) {
			console.log('tid'+tid);
			if(tid==1 && checkAptitudeEdit()){
				var testname = document.getElementById("testname").value;
				var proposition = document.getElementById("proposition").value;
				var first_choice = document.getElementById("first_choice").value;
				var second_choice = document.getElementById("second_choice").value;
				var third_choice = document.getElementById("third_choice").value;
				doAjaxRequest('system_topic_edit.php', 'update_topic_button=','testname='+testname ,'proposition='+proposition
				,'first_choice='+first_choice,'second_choice='+second_choice,'third_choice='+third_choice);
			}
			else if(checkTopicEdit()){
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
				doAjaxRequest('system_topic_edit.php', 'update_topic_button=','testname='+testname ,'proposition='+proposition
				,'first_choice='+first_choice,'second_choice='+second_choice,'third_choice='+third_choice,'fourth_choice='+fourth_choice,'answer='+answert);
			}
		}
		
		function checkAptitudeEdit(){
			if(document.getElementById("testname").value != "" &&
				document.getElementById("proposition").value != "" &&
				document.getElementById("first_choice").value != "" &&
				document.getElementById("second_choice").value != "" &&
				document.getElementById("third_choice").value != ""){
				return true;
			}
			return false;
		}

		function checkTopicEdit(){
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
	<!--<form action="system_topic_edit.php" method="post" onsubmit="return checkTopicEdit()">-->
	<?php $test_bank = mysqli_fetch_array($test_bank_db); ?>
	Test Name:<br/>
	<textarea rows="4" cols="50" id="testname" name="testname"><?php echo $test_bank['Test_Name']; ?></textarea><br/>

	Proposition:<br/>
	<textarea rows="4" cols="50" id="proposition" name="proposition"><?php echo $test_bank['Proposition']; ?></textarea><br/>
	
	Choice 1: 
	<?php if($_SESSION["topicid"]!=1){
		echo '(Answer <input type="radio" name="answer" value="1"';
		if($test_bank['Answer']==1) echo "checked";
		echo ">)"; 
	}?>
	<br/>
	<textarea rows="4" cols="50" id="first_choice" name="first_choice"><?php echo $test_bank['Choice_1']; ?></textarea><br/>
	
	Choice 2: 
	<?php if($_SESSION["topicid"]!=1){
	echo '(Answer <input type="radio" name="answer" value="2"';
	if($test_bank['Answer']==2) echo "checked";
	echo ">)"; 
	}?>
	<br/>
	<textarea rows="4" cols="50" id="second_choice" name="second_choice"><?php echo $test_bank['Choice_2']; ?></textarea><br/>
	
	Choice 3: 
	<?php if($_SESSION["topicid"]!=1){
	echo '(Answer <input type="radio" name="answer" value="3"';
	if($test_bank['Answer']==3) echo "checked";
	echo ">)"; 
	}?>
	<br/>
	<textarea rows="4" cols="50" id="third_choice" name="third_choice"><?php echo $test_bank['Choice_3']; ?></textarea><br/>
	<?php 
	if($_SESSION["topicid"]!=1){
	echo 'Choice 4:(Answer <input type="radio" name="answer" value="4"';
	if($test_bank['Answer']==4) echo "checked";
	echo ">)<br/>"; 
	echo '<textarea rows="4" cols="50" id="fourth_choice" name="fourth_choice">';
	echo $test_bank['Choice_4'].'</textarea><br/>';
	}?>

	<input type="submit" onclick="updateTest(<?php echo $_SESSION["topicid"]; ?>);" value="Submit" /> 
	<input type="button" onclick="backToPage('page_topic_edit.php');" value="Reset" /> <input type="button" onclick="backToPage('page_topic.php');" value="Cancel" />
	<!--</form>-->
</body>

</html>

<?php mysqli_close($con); ?>
