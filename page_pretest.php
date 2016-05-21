<?php 
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
	
	function shuffle_assoc($list) { 
	  if (!is_array($list)) return $list; 
	  $keys = array_keys($list); 
	  shuffle($keys); 
	  $random = array(); 
	  foreach ($keys as $key) { 
		$random[$key] = $list[$key]; 
	  }
	  return $random; 
	} 
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/testStyle.css"/>
<script type="text/javascript" src="script/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		toPreviousPage();
	});
	function toNextPage(){
		if(!formcheck())return;
		document.getElementById("box1").style.display= "none";
		document.getElementById("box2").style.display= "block";
	}
	function toPreviousPage(){
		$("#warning").html('');
		document.getElementById("box2").style.display= "none";
		document.getElementById("box1").style.display= "block";
	}
	function formcheck() {
		var inputSet = "inbox1";
		if(document.getElementById("box2").style.display=="block")inputSet = "inbox2";
		var inputs = document.getElementsByClassName(inputSet);
		if($('input.'+inputSet+'[type=radio]:checked').size()*4<$('input.'+inputSet+'[type=radio]').size()){
			alert("Please answer all question before submit");
			//$("#warning").html('<font color="red">Please answer all question before submit</font>');
			$('html,body').scrollTop(0);
			 return false;
		}
		  $("#warning").html('');
		return true;
	}
	
</script>
</head>
<body class="testStyle_body">


<div id="pretest" class="testStyle_div">
<p id="warning"></p>
		<form  action="system_pretest.php" autocomplete="on" method="post" onsubmit="return formcheck();"> 
<div id="box1" class="testStyle_testbox">
<table class="testStyle_table">
<?php
	echo '<input type="hidden" name="id" value='.$_SESSION['login'].'>';
	
	$topics = mysqli_query($con,"SELECT Topic_ID, Topic_Name, Weight FROM topics WHERE Weight > 0 AND Topic_ID != 1 AND Visibility =1 AND Deletion=0 ORDER BY RAND()");
	while ($topic = mysqli_fetch_array($topics)) {
		echo '<tr><th>'.$topic["Topic_Name"].'</th></tr>';
		$qnum=1;
		$testbank = mysqli_query($con,"SELECT * FROM test_bank WHERE Topic_ID = '".$topic["Topic_ID"]."' AND Visibility = 1".
								" ORDER BY RAND() LIMIT ".$topic["Weight"]);
		while ($quiz = mysqli_fetch_array($testbank)){
			echo '<tr><td><p class="testStyle_p"><b>'.$qnum.')</b> '.$quiz["Proposition"].'</p></td></tr>';
			$qname = 't'.$quiz["Topic_ID"].'q'.$quiz["Test_ID"];
			$multich = array(
					0 => '<tr><td><input type="radio" class="inbox1" name="'.$qname.'" value="1" >'.$quiz['Choice_1'].'</td></tr>',
					1 => '<tr><td><input type="radio" class="inbox1" name="'.$qname.'" value="2" >'.$quiz['Choice_2'].'</td></tr>',
					2 => '<tr><td><input type="radio" class="inbox1" name="'.$qname.'" value="3" >'.$quiz['Choice_3'].'</td></tr>',
					3 => '<tr><td><input type="radio" class="inbox1" name="'.$qname.'" value="4" >'.$quiz['Choice_4'].'</td></tr>',
				);
			$multich = shuffle_assoc($multich);
			foreach ($multich as $choice) echo $choice.'<tr><td></td></tr>';
			$qnum++;
		}  
	}
?>
</table>
	<a href="#" onclick="toNextPage();">next</a>
</div>
<div  id="box2" class="testStyle_testbox">
<table class="testStyle_table">
<?php
	$topics = mysqli_query($con,"SELECT Topic_ID, Topic_Name, Weight FROM topics WHERE Topic_ID = 1 AND Visibility =1 AND Deletion=0");
	while ($topic = mysqli_fetch_array($topics)) {
		echo '<tr><th>'.$topic["Topic_Name"].'</th></tr>';
		$qnum=1;
		$testbank = mysqli_query($con,"SELECT * FROM test_bank WHERE Topic_ID = 1 AND Visibility =1 AND Deletion=0 ORDER BY RAND()");
		/* LIMIT ".$topic["Weight"]*/
		while ($quiz = mysqli_fetch_array($testbank)){
			echo '<tr><td><p class="testStyle_p"><b>'.$qnum.')</b> '.$quiz["Proposition"].'</p></td></tr>';
			$qname = 'ap'.$quiz["Test_ID"];
			$multich = array(
					0 => '<tr><td><input type="radio" class="inbox2" name="'.$qname.'" value="1" >'.$quiz['Choice_1'].'</td></tr>',
					1 => '<tr><td><input type="radio" class="inbox2" name="'.$qname.'" value="2" >'.$quiz['Choice_2'].'</td></tr>',
					2 => '<tr><td><input type="radio" class="inbox2" name="'.$qname.'" value="3" >'.$quiz['Choice_3'].'</td></tr>'
				);
			$multich = shuffle_assoc($multich);
			foreach ($multich as $choice) echo $choice.'<tr><td></td></tr>';
			$qnum++;
		}  
	}
?>
</table>
<a href="#" onclick="toPreviousPage();">back</a>
<input type="submit" value="submit"/> 
</div>
		</form>
</div>


</body>
</html>