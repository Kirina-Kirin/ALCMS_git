	var checkUrl = "system_signup_checkUniqueValue.php";
			var loginNameCond = false;
			var idCond = false;
			var emailCond = false;
			var passCond = false;

			$(document).ready(function () {
					if(window.location.href.indexOf("tologin") > -1) {
					   showScrollbar(false);
					}else{
						showScrollbar(true);
					}
					if(document.getElementById("command").value=="edit") {
								emailCond = true;
					}
	/////------------ Check Login Name before submit
					$("#usernamesignup").change(function() { 
						var usr = $("#usernamesignup").val();
						if(usr.length >= 4)
						{
							$("#unameTest").html('<img src="image/loader.gif">&nbsp;Checking availability...');
							$.ajax({  
							type: "POST",  
							url: checkUrl,  
							data: "username="+ usr,  
							success: function(msg){  
							   $("#unameTest").ajaxComplete(function(event, request, settings){ 
								if(msg == 'OK'){ 
									$(this).html('&nbsp;<img src="image/tick.gif">');
									loginNameCond=true;
								}  
								else  {
									$(this).html(msg);
									loginNameCond=false;
								}  
							   });
							} 
						   }); 
						}
						else{
							$("#unameTest").html('<font color="red"> at least <strong>4</strong> characters.</font>');
							loginNameCond=false;
						}
					});
	/////------------ End Check Login Name
	/////------------ Check ID before submit
					$("#idsignup").change(function() {
						var id = $("#idsignup").val();
						$("#idTest").html('<img src="image/loader.gif">&nbsp;Checking availability...');
						if($("#status")==="student"){
							var regex =/\d{12}-\d/;
							if(!regex.test(id)){
								$("#idTest").html('<font color="red"> wrong id format.</font>');
								idCond=false;
								return;
							}
						}

						$.ajax({  
						type: "POST",  
						url: checkUrl,  
						data: "id="+ id+"",  
						success: function(msg){  
						   $("#idTest").ajaxComplete(function(event, request, settings){ 
							if(msg == 'OK'){ 
								$(this).html('&nbsp;<img src="image/tick.gif">');
								idCond=true;
							}  
							else  {
								$(this).html(msg);
								idCond=false;
							}  
						   });
						} 
					   }); 
					   return;
					});
	/////------------ End Check ID
	/////------------ Check Email before submit
					$("#emailsignup").change(function() { 
						var email = $("#emailsignup").val();
						if(validateEmail(email)){
							$("#emailTest").html('<img src="image/loader.gif">&nbsp;Checking availability...');
							if(document.getElementById("command").value=="edit") {
								var id = $("#idsignup").val();
								$.ajax({  
								type: "POST",  
								url: checkUrl,  
								data: "ide="+ id+"" + "&emaile="+ email,  
								success: function(msg){  
								   $("#emailTest").ajaxComplete(function(event, request, settings){ 
									if(msg == 'OK'){ 
										$(this).html('&nbsp;<img src="image/tick.gif">');
										emailCond=true;
									}  
									else  {
										$(this).html(msg);
										emailCond=false;
									}  
								   });
								} 
								});
							} else {
								$.ajax({  
								type: "POST",  
								url: checkUrl,  
								data: "email="+ email,  
								success: function(msg){  
								   $("#emailTest").ajaxComplete(function(event, request, settings){ 
									if(msg == 'OK'){ 
										$(this).html('&nbsp;<img src="image/tick.gif">');
										emailCond=true;
									}  
									else  {
										$(this).html(msg);
										emailCond=false;
									}  
								   });
								} 
								}); 
							}
						}else{
							$("#emailTest").html('<font color="red"> wrong email format.</font>');
							emailCond=false;
						}
						
					});
	/////------------ End Check Email
	/////------------ Check Password before submit
					$("#passwordsignup_confirm").change(function() { 
						var passIn = $("#passwordsignup").val();
						var passRe = $("#passwordsignup_confirm").val();
						$("#passTest").html('<img src="image/loader.gif">&nbsp;Checking re-enter password...');
						if(passIn == passRe){ 
							$("#passTest").html('&nbsp;<img src="image/tick.gif">');
							passCond=true;
						}else  {
							$("#passTest").html('<font color="red"> The password is not correct.</font>');
							passCond=false;
						}  
					});
	/////------------ End Check Password
			});

			function validateEmail(email) {
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				return re.test(email);
			}

			function checkFormCondition(){
				return loginNameCond&&emailCond&&idCond&&passCond;
			}
			
			function checkFormUpdateCondition(){
				return emailCond&&passCond;
			}

			function showScrollbar(show) {
				if(show){ document.body.style.overflowY = 'scroll';}
				else { document.body.style.overflow = 'hidden';}
				$('html,body').scrollTop(0);
			}