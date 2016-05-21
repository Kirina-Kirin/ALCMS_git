<?php 	
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
	include "chgable_db_connect.php";
?>
<!DOCTYPE html>
 <html>
 <head>
 	<script type="text/javascript" src="script/jquery.js"></script>
	<script type="text/javascript" src="script/loginScript.js"></script>
 	<script>
 			function submitEditAcct(){
			if(checkFormUpdateCondition()){

				var idsignup = document.getElementById("idsignup").value;
				var namesignup = document.getElementById("namesignup").value;
				var snamesignup = document.getElementById("snamesignup").value;
				var telsignup = document.getElementById("telsignup").value;
				var emailsignup = document.getElementById("emailsignup").value;
				var usernamesignup = document.getElementById("usernamesignup").value;
				var passwordsignup = document.getElementById("passwordsignup").value;
				var passwordsignup_confirm = document.getElementById("passwordsignup_confirm").value;

				doAjaxRequest('system_admin_student.php', 'admin_edit_student_button=','idsignup='+idsignup,'namesignup='+namesignup
				,'snamesignup='+snamesignup,'telsignup='+telsignup,'emailsignup='+emailsignup,'usernamesignup='+usernamesignup
				,'passwordsignup='+passwordsignup,'passwordsignup_confirm='+passwordsignup_confirm);
			}
		}
 	</script>
 </head>
 <body>
 		<?php 
		$students_db = mysqli_query($con,"
			SELECT * FROM student
			WHERE Student_ID='".$_SESSION["editid"]."'
		");

		$student = mysqli_fetch_array($students_db);
 		?>
			
		<!--<form  action="system_signup.php" autocomplete="on" method="post" onsubmit="return checkFormCondition();">-->
		<h1> Edit Student</h1> 
		<input type="hidden" name="command" id="command" value="edit">
		<input type="hidden" name="status" id="status" value="student">
		<table cellspacing="0" cellpadding="0" class='signup'>
		   <col style="width:30%">
			<col style="width:45%">
			<col style="width:25%">
			<tbody>
		<tr>
			<!--<td> <label for="idsignup" class="youid"> Teacher ID  </label></td>-->
			<input id="idsignup" name="idsignup" value="<?php echo $student['Student_ID']; ?>" hidden/>
			<!--<td> <font id="idTest"></font> </td>-->
		</tr>
		
		<tr>
			<td> <label for="namesignup" class="youname" > Name</label> </td>
			<td> <input id="namesignup" name="namesignup" required="required" type="text" placeholder="enter name" value="<?php echo $student['Student_Name']; ?>"/>  </td>
		</tr>
		<tr>
			<td> <label for="snamesignup" class="yousname"> Last name</label> </td>
			<td> <input id="snamesignup" name="snamesignup" required="required" type="text" placeholder="enter last name" value="<?php echo $student['Student_Lastname']; ?>"/>  </td>
		</tr>
		<tr>
			<td> <label for="telsignup" class="youtel"> Telephone number</label> </td>
			<td> <input id="telsignup" name="telsignup" required="required" type="text" placeholder="enter telephone number" minlength="9" value="<?php echo $student['Student_Tel']; ?>"/>  </td>
		</tr>
		
		<tr>
			<td> <label for="emailsignup" class="youmail"> Email</label></td>
			<td> <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="enter email" value="<?php echo $student['Student_Email']; ?>"/>  </td>
			<td> <font id="emailTest"></font></td>
		</tr>
		
		
		<tr>
			<td> <label for="usernamesignup" class="uname">Login name</label></td>
			<td> <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="enter login name" value="<?php echo $student['Student_LoginName']; ?>" readonly="readonly"/></td>
			<td> <font id="unameTest"></font> </td>
		</tr>
		
		<tr>
			<td><label for="passwordsignup" class="youpasswd">Password </label> </td>
			<td><input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="enter password" value="<?php echo $student['Student_Password']; ?>"/> </td>
		</tr>

		<tr>
			<td> <label for="passwordsignup_confirm" class="youpasswd">Confirm Password </label></td>
			<td> <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="re-enter password" /></td>
			<td><font id="passTest"></font> </td>
		</tr>
		<tr>
		<td colspan="2">
		<input type="submit" onclick="submitEditAcct()" value="Submit" /> <input type="button" onclick="backToPage('page_student_form.php');" value="Reset" /> <input type="button" onclick="backToPage('page_student_account.php');" value="Cancel" />
		</td>

		<td> 
		</td>
		</tr>
		<tbody>
		<!--</form>-->

	</body>
</html>