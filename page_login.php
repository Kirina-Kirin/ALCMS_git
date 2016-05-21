<?php 	
	ob_start();
	if (version_compare(phpversion(), '5.4.0', '<')) {
		 if(session_id() == '') {session_start();}
	} else {
		if (session_status() == PHP_SESSION_NONE) {session_start();}
	}
?>
<!DOCTYPE html>
 <html>
    <head>
	<title>Login and Registration Form</title>
	<!--CSS-->
		<link rel="stylesheet" type="text/css" href="css/login-bg-style.css" />
        <link rel="stylesheet" type="text/css" href="css/login-style.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
	<!--JS-->
		<script type="text/javascript" src="script/jquery.js"></script>
		<script type="text/javascript"  src="script/loginScript.js"></script>
        

       
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Adaptive Learning Content <span>Management System</span></h1>
            </header>
            <section>				
                <div id="container_demo" >
                    <!-- hidden anchor to stop jump-->
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="system_login.php" autocomplete="on" method="post"> 
                                <h1>Log in</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Login Name </label>
                                    <input id="username" name="username" required="required" type="text" placeholder="Enter login name here"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="Enter password here" /> 
                                </p>
                                <p class="errormsg"> 
									<?php
									if(isset($_SESSION['error'])){
										echo "alert('Wrong username or password!')";
										//echo "Wrong username or password!";
										unset($_SESSION['error']);
										unset($_SESSION['Success_signup']);
									}else if(isset($_SESSION['Error_signup'])){
										echo "alert('Sign up error!')";
										//echo "Sign up error!";
										unset($_SESSION['Error_signup']);
										unset($_SESSION['Success_signup']);
									}
									?>
								</p>
								<p class="receivedmsg"> 
									<?php
										if(isset($_SESSION['Success_signup'])){
										echo "<script>alert('Sign up success!')</script>";
										//echo "Sign up success!";
										unset($_SESSION['Success_signup']);
									}?>
								</p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" /> 
								</p>
                                <p class="change_link">
									Do not have an account yet ?
									<a href="#toregister" class="to_register"  onclick="showScrollbar(true);">SIGN UP</a>
								</p>
                            </form>
                        </div>

                        <div id="register" class="animate form">
                            <form  action="system_signup.php" autocomplete="on" method="post" onsubmit="return checkFormCondition();"> 
                                <h1> Sign up </h1> 
                                <input type="hidden" name="command" id="command" value="add">
                                <p> 
									<input type="hidden" name="status" id="status" value="student">
                                    <label for="idsignup" class="youid" data-icon="s" > 
										Your student ID  <font id="idTest"></font>
										</label>
                                    <input id="idsignup" name="idsignup" required="required" type="text" 
									placeholder="enter your student ID here" size="14"/> 
                                </p>
								<p> 
                                    <label for="namesignup" class="youname" data-icon="u" > Your name</label>
                                    <input id="namesignup" name="namesignup" required="required" type="text" placeholder="enter your name here"/> 
                                </p>
								<p> 
                                    <label for="snamesignup" class="yousname" data-icon="u" > Your last name</label>
                                    <input id="snamesignup" name="snamesignup" required="required" type="text" placeholder="enter your last name here"/> 
                                </p>
								<p> 
                                    <label for="telsignup" class="youtel" data-icon="t" > Your telephone number</label>
                                    <input id="telsignup" name="telsignup" required="required" type="text" placeholder="eg. 0812345678" minlength="9"/> 
                                </p>
								<p> 
                                    <label for="emailsignup" class="youmail" data-icon="m" > 
									Your email <font id="emailTest"></font>
									</label>
                                    <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="eg. myemail@mail.com"/> 
                                </p>
								<p> 
                                    <label for="usernamesignup" class="uname" data-icon="i">
									Your login name <font id="unameTest"></font>
									</label>
                                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="eg. mylogin123" />
                                </p>
                                <p> 
                                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                                    <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="eg. myPass123"/>
                                </p>
                                <p> 
                                    <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">
									Please confirm your password <font id="passTest"></font>
									</label>
                                    <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="re-enter password from above"/>
                                </p>
                                <p class="signin button"> 
									<input type="submit" value="Sign up"/> 
								</p>
                                <p class="change_link">  
									Already have an account ?
									<a href="#tologin" class="to_register" onclick="showScrollbar(false);"> Go and log in </a>
								</p>
                            </form>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>