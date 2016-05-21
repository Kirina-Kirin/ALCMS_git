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
 </head>
 <body>
			
		<form  action="system_signup.php" autocomplete="on" method="post" onsubmit="return checkFormCondition();">
		<h1> Sign Up Teacher</h1> 
		<input type="hidden" name="command" id="command" value="add">
		<input type="hidden" name="status" id="status" value="teacher">
		<table cellspacing="0" cellpadding="0" class='signup'>
		   <col style="width:30%">
			<col style="width:45%">
			<col style="width:25%">
			<tbody>
		<tr>
			<td> <label for="idsignup" class="youid"> Teacher ID  </label></td>
			<td> <input id="idsignup" name="idsignup" required="required" type="number" placeholder="enter ID"/> </td>
			<td> <font id="idTest"></font> </td>
		</tr>
		
		<tr>
			<td> <label for="namesignup" class="youname" > Name</label> </td>
			<td> <input id="namesignup" name="namesignup" required="required" type="text" placeholder="enter name"/>  </td>
		</tr>
		<tr>
			<td> <label for="snamesignup" class="yousname"> Last name</label> </td>
			<td> <input id="snamesignup" name="snamesignup" required="required" type="text" placeholder="enter last name"/>  </td>
		</tr>
		<tr>
			<td> <label for="telsignup" class="youtel"> Telephone number</label> </td>
			<td> <input id="telsignup" name="telsignup" required="required" type="text" placeholder="enter telephone number" minlength="9"/>  </td>
		</tr>
		
		<tr>
			<td> <label for="emailsignup" class="youmail"> Email</label></td>
			<td> <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="enter email"/>  </td>
			<td> <font id="emailTest"></font></td>
		</tr>
		
		
		<tr>
			<td> <label for="usernamesignup" class="uname">Login name</label></td>
			<td> <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="enter login name" /></td>
			<td> <font id="unameTest"></font> </td>
		</tr>
		

		
		<tr>
			<td><label for="passwordsignup" class="youpasswd">Password </label> </td>
			<td><input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="enter password"/> </td>
		</tr>
		 
		
		<tr>
			<td> <label for="passwordsignup_confirm" class="youpasswd">Confirm Password </label></td>
			<td> <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="re-enter password"/></td>
			<td><font id="passTest"></font> </td>
		</tr>
		<tr>
		<td colspan="2">
			<input type="submit" value="Submit" /> <input type="button" onclick="backToPage('page_teacher_account.php');" value="Cancel" />
		</td>
		<td> 
		</td>
		</tr>
		<tbody>
		</form>

	</body>
</html>