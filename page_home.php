<?php
	session_start();
	include "connect.php";	
?>
<html>
	<head>
		<script type="text/javascript" src="script/winPopup.js"></script>
		<link rel="stylesheet" type="text/css" href="css/homeStyle.css"/>
		<script type="text/javascript" src="script/checksign.js"></script>
	</head>
	<body>
		<section id="box-1">
			<form>
				<!--News-->
				<table id="sub-box">
					<tbody>
						<tr>
							<td colspan="2" ><h1>News</h1></td>
						</tr>
						
						<tr>
							<td><a href="#">-news testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-news testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-news testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-news testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-news testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-next week is my ending.</a></td>
							<td>22/11/2012</td>
						</tr>
							<td><a href="#">-just write something here.</a></td>
							<td>22/11/2012</td>
						<tr>
							<td><a href="#">-the first time with OTZ. XD</a></td>
							<td>21/11/2012</td>
						</tr>
					</tbody>
				</table>
				<hr>
				<!--Event-->
				<table id="sub-box">
					<tbody>
						<tr>
							<td colspan="2"><h1>Events</h1></td>
						</tr>
						
						<tr>
							<td><a href="#">-events testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-events testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-events testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-events testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-events testing link.</a></td>
							<td>22/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-a free world depends on a free web.</a></td>
							<td>21/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-sell 1 buy 1 is okay.</a></td>
							<td>20/11/2012</td>
						</tr>
						<tr>
							<td><a href="#">-what will I say?</a></td>
							<td>19/11/2012</td>
						</tr>
					</tbody>
				</table>
			</form> 
		</section>
		<section id="box-2">
			
			<!--Login/Logout and Signup Box-->
			<?phpif(!isset($_SESSION['login'])){?>
				<a href="#login" name="modal">Login<br/>
					<span id="mini"> Please Login to PLAY!</span>
					<p class="errormsg">
					<!--Show wrong username or password-->
					<?phpif(isset($_SESSION['login'])){if(!$_SESSION['login']){echo "Wrong username or password.".$_SESSION['login'];}}?>
					</p>
				</a>
			<?php}
			else{?>
				<div id="userdisplay"><br/>Username: <?phpecho $_SESSION['username'];?></div>
				<div>
					<img style="margin-top:-20em; margin-left:6em;" width="150px" height="160px" src="<?php=$_SESSION['imgProfile']?>"/>
				</div>
				<a class="logout" href="logout.php">Logout</a>
			<?php}?>
			<?phpif(!$_SESSION['login']){?>
				<a href="#signup" name="modal">Signup<br/><span id="mini">have not had username and Password yet.</span></a>
			<?php}?>

			<div id="boxes">
				<form id="login" class="window" action="checklogin.php" autocomplete="on" method="post">
					<div>
						<label for="username">Username</label><br/>
						<input type="text" name="username" id="username" onblur="setbox('username')" required="required" onfocus="clearbox('username')" placeholder="Username"/><br/>
						<label for="password">Password</label><br/>
						<input type="password" name="password" id="password" onblur="setbox('password')" required="required" onfocus="clearbox('password')" placeholder="Password"/>
					</div>
					<div class="d-blank"></div>
					<div class="">
						<input type="submit" name="submit" alt="Login" title="Login" value="Login"/>
					</div>	
				</form>
				<!--End login sticky--> 	
				<form class="window" id="signup" action="signup.php" autocomplete="on" method="post" enctype="multipart/form-data">
					<h1>Sign up</h1>
					<p>
						<label for="usernamesignup" class=""> Username </label><br/>
						<input id="usernamesignup" name="usernamesignup" onblur="setbox('usernamesignup')" onfocus="clearbox('usernamesignup')" required="required" type="text" placeholder="6-15 english character/number"/>
						<span id="usernameR"></span>
					</p>
					<p>
						<label for="passwordsignup" class=""> Password </label><br/>
						<input id="passwordsignup" name="passwordsignup" onblur="setbox('passwordsignup')" onfocus="clearbox('passwordsignup')" required="required" type="password" placeholder="4-15 english character/number"/>
						<span id="passwordR"></span>
					</p>
					<p>
						<label for="confirm-passwordsignup" class=""> Confirm Password </label><br/>
						<input id="confirm-passwordsignup" name="confirm_passwordsignup" onblur="setbox('confirm_passwordsignup')" onfocus="clearbox('confirm_passwordsignup')" required="required" type="password" placeholder="type password again"/>
					</p>
					<p>
						<label for="emailsignup" class=""> E-mail </label><br/>
						<input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="some@mail.com"/>
					</p>
					<p>
						<label for="userpicsignup"> Display Picture </label><br/>
						<input id="userpicsignup" type="file" name="userpicsignup" size="2000000" accept="image/jpeg" size="26"/>
					<p id="mini">file's size must less than 200KB <br/> file's type must be .jpg or .jpeg</p>
					</p>
					<p>
					<input type="submit" name="submit" value="Sign up"/>
					</p>
				</form>
				<!--End of signup sticky-->
				<div class="window" id="display">
					<table>
						<tr>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
				<div id="mask"></div>
			</div>
		</section>
		
		<?php
				if($_SESSION['done']){
					echo "<script>";
					echo 'alert("Signup successful.");';
					echo "</script>";
					$_SESSION['done'] = false;
				}
		?>
		
	</body>
</html>