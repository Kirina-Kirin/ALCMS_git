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
	<title>Login and Registration Form</title>
	<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>
	<!--CSS-->
		<link rel="stylesheet" type="text/css" href="css/admin-style.css"/>

	<!--JS-->
		<!--<script type="text/javascript" src="script/jquery.js"></script>
		<script type="text/javascript" src="script/loginScript.js"></script>
        <script type="text/javascript" src="script/adminScript.js"></script>-->
        <script type="text/javascript">

			  function copyTextToClipboard(text) {
			  var textArea = document.createElement("textarea");

			  //
			  // *** This styling is an extra step which is likely not required. ***
			  //
			  // Why is it here? To ensure:
			  // 1. the element is able to have focus and selection.
			  // 2. if element was to flash render it has minimal visual impact.
			  // 3. less flakyness with selection and copying which **might** occur if
			  //    the textarea element is not visible.
			  //
			  // The likelihood is the element won't even render, not even a flash,
			  // so some of these are just precautions. However in IE the element
			  // is visible whilst the popup box asking the user for permission for
			  // the web page to copy to the clipboard.
			  //

			  // Place in top-left corner of screen regardless of scroll position.
			  textArea.style.position = 'fixed';
			  textArea.style.top = 0;
			  textArea.style.left = 0;

			  // Ensure it has a small width and height. Setting to 1px / 1em
			  // doesn't work as this gives a negative w/h on some browsers.
			  textArea.style.width = '2em';
			  textArea.style.height = '2em';

			  // We don't need padding, reducing the size if it does flash render.
			  textArea.style.padding = 0;

			  // Clean up any borders.
			  textArea.style.border = 'none';
			  textArea.style.outline = 'none';
			  textArea.style.boxShadow = 'none';

			  // Avoid flash of white box if rendered for any reason.
			  textArea.style.background = 'transparent';


			  textArea.value = text;

			  document.body.appendChild(textArea);

			  textArea.select();

			  try {
			    var successful = document.execCommand('copy');
			    var msg = successful ? 'successful' : 'unsuccessful';
			    console.log('Copying text command was ' + msg);
			  } catch (err) {
			    console.log('Oops, unable to copy');
			  }

			  document.body.removeChild(textArea);
			}

			function execCopy(id) {
				var idsignup = document.getElementById("id_"+id).innerHTML;
				var namesignup = document.getElementById("name_"+id).innerHTML;
				var snamesignup = document.getElementById("sname_"+id).innerHTML;
				var emailsignup = document.getElementById("email_"+id).innerHTML;
				var telsignup = document.getElementById("tel_"+id).innerHTML;

				var text = idsignup + String.fromCharCode(9) + namesignup + String.fromCharCode(9) + snamesignup +
				String.fromCharCode(9) + emailsignup + String.fromCharCode(9) + telsignup;
				copyTextToClipboard(text);
			}

			function editAcct(edit_id) {
				doAjaxRequest('system_admin_teacher.php','edit='+edit_id);
			}

			function delAcct(del_id) {
				var confirmDel = confirm("Press OK to delete");
				if(confirmDel){
					doAjaxRequest('system_admin_teacher.php','del='+del_id);
					//return true;
				}
				//return false;
				return false;
			}
        </script>
    </head>

    <body>
		<header>
            <h1>Teacher Info</h1>
        </header>
        <input type="button" onclick="loadNewPage('page_teacher_add.php', 'Create New Teacher');" value="Add Teacher" /></br></br>
		<!--<form action="system_admin.php" method="post">-->
		<table cellspacing="0" cellpadding="0" class="fixed">
		<tr><td>ID</td>
		<td>Name</td>
		<td>Surname</td>
		<td>Email</td>
		<td class="phone">Telephone</td>
		<td>Copy</td>
		<td>Edit</td>
		<td>Del</td>
		</tr>
<?php
		$query = mysqli_query($con,"SELECT * FROM employee WHERE Employee_Job = 'teacher';");
		$r=0;
		while ($acc = mysqli_fetch_array($query)){
			echo '<tr>';
			echo '<td><div id="id_'.$acc['Employee_ID'].'">'.$acc['Employee_ID'].'</div></td>';
			echo '<td><div id="name_'.$acc['Employee_ID'].'">'.$acc['Employee_Name'].'</div></td>';
			echo '<td><div id="sname_'.$acc['Employee_ID'].'">'.$acc['Employee_Lastname'].'</div></td>';
			echo '<td><div id="email_'.$acc['Employee_ID'].'">'.$acc['Employee_Email'].'</div></td>';
			echo '<td class="phone"><div id="tel_'.$acc['Employee_ID'].'">'.$acc['Employee_Tel'].'</div></td>';

			echo '<td> <a href="javascript:execCopy('.$acc["Employee_ID"].');"> 
			<input type="image" src="image/Copy.png" height="20em" alt="copy clipboard"	name="copy"/> 
			</a> </td>';

			echo '<td> <a href="javascript:editAcct('.$acc["Employee_ID"].');">
			<img src="image/Edit.png" height="25em" alt="" />
			</a> </td>';

			echo '<td> <a href="javascript:delAcct('.$acc["Employee_ID"].');">
			<img src="image/Delete.png" height="25em" alt="" />
			</a> </td>';

			echo '</tr>';
		}
?>
		</table>
		<!--</form>-->
    </body>
</html>