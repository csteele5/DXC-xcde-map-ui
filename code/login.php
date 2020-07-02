<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<script>
function allSysNavigate(UID,dest) {			
	document.getElementById("HTTP_SMUSER").value = UID;
	document.usrSession.value = UID;
	document.usrSession.action = dest;

	document.usrSession.submit();

  //var xhttp = new XMLHttpRequest();
  /*
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
     document.getElementById("demo").innerHTML = xhttp.responseText;
    }
  };
  */
  //xhttp.open("GET", "v7/ajax/cfsessioncreate.cfm?HTTP_SMUSER="+UID, true);
  //xhttp.send();

}
</script>
<form name="usrSession" id="usrSession" method="post" action="home.php">
	<input type="hidden" id="HTTP_SMUSER" name="HTTP_SMUSER" value="" />
</form>	

<?php
//put in a check for csc.com address
// if (stripos($_SERVER['HTTP_HOST'], 'csc.com') > 0) {
// 	echo 'THIS ADDRESS IS NO LONGER VALID - USE <a href="https://twos.dxc.com">https://twos.dxc.com</a>';exit;
// }

session_start();
session_unset(); 

include 'dbconn.inc';
/*
phpinfo();
*/
			
function RandomString($length) {
	$original_string = array_merge(range(0,9), range('a','z'), range('A', 'Z'));
	$original_string = implode("", $original_string);
	return substr(str_shuffle($original_string), 0, $length);
}

?>
<?php

$msg = 0;
if (isset($_GET['msg'])) {
	$msg = $_GET['msg'];
}
$resetAction = 0;
$resetEmail = '';
$userID = '';
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$msg = 0;
	if (isset($_POST['resetAction'])) {
		$resetAction = $_POST['resetAction'];
	}
	//echo 'resetAction '.$resetAction;exit;
	if ($resetAction == 1) {
		// password reset request sent from modal
		if (isset($_POST['lpwdUserID'])) {
			$lpwdUserID = addslashes($_POST['lpwdUserID']);
		}
		if (isset($lpwdUserID)) {
			//echo 'test6 '.$lpwdUserID.' | '.$_POST['lpwdUserID'];exit;
			echo 'This is not yet set up in the system.';exit;
			// verify this userID is actually valid
			$query="SELECT userID FROM UsersV2 WHERE userID=?";	
			/*echo $query.'<br>';*/
			$params = array($lpwdUserID);
			$result = sqlsrv_query($conn,$query,$params);	
			
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);				
				if (!$test) {
					// no record found
					$msg = 8;
					//echo 'lpwdUserID NOT FOUND! '.$lpwdUserID;exit;
				}				
			} catch (exception $e) {
				print_r($e);
			}			
			
			// this is not nested to improve readability.  This assumes a record was found
			if ($msg == 0) {
				// get random reset code - custom function				
				$resetCode = RandomString(10);
				//echo '<br>reset code '.$resetCode;
				
				// set current base web link - in dbconn.inc
				//echo '<br>appFullWebLink '.$appFullWebLink;
				
				// launch stored procedure to email link - spPWDResetEmailV2
				
				// there's got to be a better way to do this.  initializing varchar150
				/*$resetEmail = 'defaultvaluedefaultvaluedefaultvaluedefaultvalue1defaultvaluedefaultvaluedefaultvaluedefaultvalue1defaultvaluedefaultvaluedefaultvaluedefaultvalue1';*/
				$resetEmail = '                                                                                                                                                     ';
				//echo '<br>resetEmail_pre '.$resetEmail;


				/*--------- The next few steps call the stored procedure. ---------*/
				
				/* Define the Transact-SQL query. Use question marks (?) in place of
				 the parameters to be passed to the stored procedure */
				//$tsql_callSP = "{call spPWDResetEmailV2( ?, ?, ?, ? )}";
				
				/* Define the parameter array. By default, the first parameter is an
				INPUT parameter. The second parameter is specified as an OUTPUT
				parameter. Initializing $salesYTD to 0.0 sets the returned PHPTYPE to
				float. To ensure data type integrity, output parameters should be
				initialized before calling the stored procedure, or the desired
				PHPTYPE should be specified in the $params array.*/
				/*EXAMPLE
				$lastName = "Blythe";
				$salesYTD = 0.0;
				$params = array( 
								 array($lastName, SQLSRV_PARAM_IN),
								 array($salesYTD, SQLSRV_PARAM_OUT)
							   );*/
				
				//$tsql_callSP = "{call phpTest(?, ?, ?, ?)}";	
				$tsql_callSP = "{call spPWDResetEmailV2PHP( ?, ?, ?, ? )}";		   
				
				$params = array( 
								 array($lpwdUserID, SQLSRV_PARAM_IN),
								 array($appFullWebLink),
								 array($resetCode),
								 array($resetEmail, SQLSRV_PARAM_OUT)
							   );
										   
				/* Execute the query. */
				$stmt1 = sqlsrv_query( $conn, $tsql_callSP, $params);
				/*
				if( $stmt1 === false )
				{
					 echo "Error in executing stored proc spPWDResetEmailV2.\n";
					 die( print_r( sqlsrv_errors(), true));
				} else { echo '<br>success2 ';}*/
				
				/* Display the value of the output parameter $salesYTD. */
				//echo '<br>resetEmail '.$resetEmail;
				
				/*Free the statement and connection resources. */
				sqlsrv_free_stmt( $stmt1);
				/*--------- END call to the stored procedure. ---------*/
			
				$firstSpace=strpos($resetEmail, ' ');
				if ($firstSpace > 0){
					$resetEmail = substr($resetEmail, 0, $firstSpace);
				}
				
				//echo 'resetEmail3 '.$resetEmail.'/////'; exit;

				//$resetEmail = 'siegcw@yahoo.com';
				$userID = $lpwdUserID; 
				$msg = 9;			
			}

		}	
		
	} else {
		// username and password sent from Form 	
		$userID=addslashes($_POST['userID']); 
		$userPassword=addslashes($_POST['userPassword']); 
		$params = array($userID, $userPassword);
		
		if (!$userID == '') {
			// This query methon is used to prevent injection attacks.
			$query="SELECT userID, EmployeeName, City, TWOSPword, Phone, cellPhone
					FROM UsersV2 WHERE userID=? AND TWOSPword=?";	
			/*echo $query.'<br>';*/
			
			$result = sqlsrv_query($conn,$query,$params);	
			
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);
				
				/*echo 'test 10 '.$test;*/
				
				if ($test) {
					/*echo 'has rows ';*/
					while($row = sqlsrv_fetch_array($result)) {
						$_SESSION['userID']=strtolower($row['userID']);
						$_SESSION['EmployeeName']=$row['EmployeeName'];
						$_SESSION['Phone']=$row['Phone'];
						$_SESSION['cellPhone']=$row['cellPhone'];
                		$_SESSION['TWOSPword']=$row['TWOSPword'];

                		$_SESSION['readBulletinList']='0';
						
						$expire=time()+60*60*24*30;
						setcookie("userID", $row['userID'], $expire);
					}
					
					/* Add Coldfusion session create*/
					//launchCFTWOS(0, 0)
					//echo '<script> setCFSession("'.$_SESSION['userID'].'"); </script>';
					//echo 'stop';exit;

					// if (isset($_GET['destination'])) {
					// 	header("Location: ".$_GET['destination']);
					// } else {
					// 	header("Location: home.php");
					// }

					$destPage = "home.php";

					if (isset($_GET['destination'])) {
						$destPage = $_GET['destination'];
					}

					echo '<script> allSysNavigate("'.$_SESSION['userID'].'","'.$destPage.'"); </script>';

					exit;
				} else {
					$msg = 10;
				}
				
				
			} catch (exception $e) {
				print_r($e);
			}
			
			$msg = 10;
		
			sqlsrv_free_stmt($result);
		
		}	
	}
	
	



} else {
	// form not submitted
	if (isset($_COOKIE["userID"])) {
		$userID = $_COOKIE["userID"];
	}
}

/*******  Get system bulletin *********/
$query="SELECT  BulletinID, PublishDate, SubjectLine, Message, ExpirationDate, DateCreated, CreatedBy
		FROM         SysBulletins
		WHERE     (PublishDate < GETDATE()) AND (ExpirationDate > GETDATE())
		ORDER BY PublishDate";


$result2 = sqlsrv_query($conn,$query);	

if( $result2 === false ) {
	 die( print_r( sqlsrv_errors(), true));
}/**/




?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Tactical Workflow & Operational Support</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
        <style>
     	 body {
		   /* padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;*/
			background-color: transparent !important;
		  }
		  
		  .container {
			padding-top:30px;
			}
	
		  .form-signin {
			max-width: 300px;
			padding: 19px 29px 29px;
			margin: 0 auto 20px;
			/*background-color: #fff;*/
			background-color: #AEB6BF;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			   -moz-border-radius: 5px;
					border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
					box-shadow: 0 1px 2px rgba(0,0,0,.05);
		  }
		  .form-signin .form-signin-heading,
		  .form-signin .checkbox {
			margin-bottom: 10px;
		  }
		  .form-signin input[type="text"],
		  .form-signin input[type="password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
		  }
	
	  	  #homeHeader {
	  		max-width: 95%;
	  		}
		  
		  #loginImage {
			padding-bottom:20px;
			}
			
			#lostPasswordLink, #registerLink {
				-webkit-box-sizing:border-box;
				-moz-box-sizing:border-box;
				box-sizing:border-box;
			}	
			#lostPasswordLink {
				width: 60%;
			}
			#registerLink {
				width: 40%;
			}
			/*
			div#backgroundImageDiv { 
				margin:0 auto; 
				text-align:center; 
			}
			
			img#backgroundImage {
				max-width:1200px;
				z-index:-999;
			}*/	

			div.container {
				margin-right: 0 !important;
				margin-left: 0 !important;
			}
			
			div.container-inner {
				width:500px;
				margin:20px 0 0 20px;
			}
			
			div.login-img-container {
				width:100%;
				margin:10px;
			}
			
			div.login-content {
				margin: 10px 10px 50px 10px;
				font-size: 1.2em !important;
			}

			span.pwdRegLinks {
				vertical-align:bottom;
				padding-left: 5px;
			}

			span.pwdRegLinks a {
				vertical-align:bottom;
			}

			.basicTop {
				width: 100%;
				/*height: 60px;
				background-color: #000;*/
				/*background-image:url(img/dxc_logo_hz_wht_rgb_small.png);
				background-repeat:no-repeat;
				background-position: right top;
				color:#FFF;*/
			}

			.basicHeaderLeft {
				width: 50%;
				/*background-image:url(img/DXCHeaderLogo.jpg);
				background-repeat:no-repeat;
				background-position: right top;
				width: 100px;*/
			}

			.basicHeaderRight {
				width: 50%;
				text-align: right;
				/*background-image:url(img/DXCHeaderName.jpg);
				background-repeat:no-repeat;
				background-position: left top;
				width: 100px;
				float: right;*/
			}

			html { 
			  background: url(img/Bionix_Only_RightBulb.jpg) no-repeat center center fixed; 
			  -webkit-background-size: cover;
			  -moz-background-size: cover;
			  -o-background-size: cover;
			  background-size: cover;
			}
			
			/*override my custom change to bottom margin on this page only*/
			div.alert {
				margin-bottom: 20px;
			}

			
			@media (min-width: 481px) {		/*is_asianmanontablet_c_hi.jpg*/
				/*
				div.container {
					background-image:url(img/is_asianmanontablet_c_hi.jpg);
					background-repeat:no-repeat;
					background-position: right top;
					width:1200px;
					height:800px;
				}
				*/
				body {
					/*background-image:url(img/Bionix_Only_600726800.jpg);
					background-repeat:no-repeat;
					background-position: right top;
					width:1200px;
					height:800px;*/
				}
			
			
			}

			@media (max-width: 767px) {
				div.container {
					padding-top:0px;
				}			
			}

			@media (max-width: 480px) {
				div.container {
					background: none;
					width: 100%;
					height: 100%;
				}
				
				div.container-inner {
					width: 100%;
					margin: 0;
				}
				
				div.login-img-container {
					width: 100%;
					margin: 10px 0;
				}
				
				div.login-content {
					display:none;
					visibility:hidden;
				}
				
				div.login-content {
					margin: 10px 10px 20px 10px;
				}
				
				span.loginGroup .btn {
					width:100%;
					margin-bottom:10px;
				}
				
				span.pwdRegLinks {
					display: inline-block;
					width: 100%;
					text-align: center;
				}
			
			}

        </style>

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body class="loginPage">

        <!--[if lt IE 8]>
			<div class="alert alert-error op-alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				You are either using an older version of IE or have turned on Compatibility Mode.  To proceed, turn off compatibility mode or <a href="http://browsehappy.com/">upgrade your browser</a>.  You may also view this site on your mobile device.				
		  	</div>
        <![endif]-->
        
		<!-- <div class="basicTop ">
			<div class="basicHeaderLeft ">
				<img src="img/DXCHeaderLogo.jpg" id="basicHeaderLeft">
			</div>	
			<div class="basicHeaderRight ">
				<span class=""><img src="img/DXCHeaderName.jpg"  id="basicHeaderRight"></span>
			</div>			
		</div> -->
		<table class="basicTop">
			<tr>
				<td class="basicHeaderLeft ">
					<img src="img/DXCHeaderLogo.jpg">
				</td>
				<td class="basicHeaderRight ">
					<!-- <img src="img/DXCHeaderName.jpg"> -->
				</td>
			</tr>
		</table>
		<div class="container">
		
			<div class="container-inner">
				<div class="login-img-container">
					<!-- <img src="img/TWOSlogin2.png" id="loginHeader">
					<img src="img/DXCTWOSmidV2.jpg" id="homeHeader"> -->
				</div>
				
				<div class="login-content invertedText">
						<h2>XCDE (/ikˈsēd/)</h2>
						<p><b>PDXC Transport Configuration</b></p>
						<p>
							CI and Attribute mapping, mapping templates, CMDB lookups, etc.  
							<i>Managed by the Corellia team (OEE Orchestration Config).</i>
						</p>
						<!--<p><a class="btn" href="#">View details &raquo;</a></p>-->
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 1) {
						echo 'hide';
						}
						?>		
					">
					<strong>Error!</strong> Your user account, while active, does not have the appropriate rights.  Contact the Corellia team technical support.
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 8) {
						echo 'hide';
						}
						?>		
					">
					<strong>Error!</strong> The user ID is incorrect.  Please try again.
				</div>
				<div class="alert alert-success
						<?php
						if ($msg != 9) {
						echo 'hide';
						}
						?>		
					">
					<strong>Your request has been processed!</strong> An email has been sent to <?php echo $resetEmail ?>	with instructions to set your password.<br><strong>May take up to 10 minutes to send. </strong>
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 10) {
						echo 'hide';
						}
						?>		
					">
					<strong>Error!</strong> The user ID or password are incorrect.  Please try again.
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 11) {
						echo 'hide';
						}
						?>		
					">
					<strong>Invalid Security Code. Request Password reset again.</strong> 
					<br>
					<p><b>TIP: </b> Wait a few minutes for the email to arrive before resubmitting. Each time you submit 
					a password reset request, the reset link in any previous request will no longer function.</p>
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 21) {
						echo 'hide';
						}
						?>		
					">
					<strong>Error!</strong> A duplicate record was found.  Please click the Lost Password link or try again.
				</div>
				<div class="alert alert-success
						<?php
						if ($msg != 22) {
						echo 'hide';
						}
						?>		
					">
					<strong>Your request has been processed!</strong> Please check your DXC email for instructions to set your password.<br><strong>May take up to 10 minutes to send. </strong>
				</div>
				<div class="alert alert-error
						<?php
						if ($msg != 23) {
						echo 'hide';
						}
						?>		
					">
					<strong>Error!</strong> There was an issue with your request.  
					Please try again, and ensure that all data fields are properly formatted with no illegal characters.  
					If the issue persists, notify Charles Steele at csteele5@dxc.com
				</div>
				<!-- show bulletins -->
				<?php
					try {	  
						$test = sqlsrv_has_rows($result2);
						
						if ($test) {
							while($row = sqlsrv_fetch_array($result2)) {
								echo '<div class="alert alert-error">';
								echo '<strong>'.$row['SubjectLine'].'</strong> '.$row['Message'];
								echo '</div>';
							}
						}
					} catch (exception $e) {
						print_r($e);
					}	
					sqlsrv_free_stmt($result2);
				/**/
				?>
		
				  <form class="form-signin" method="post">
					<h2 class="form-signin-heading">Please sign in</h2>
                    <!--[if lt IE 10]>
                        <label class="control-label hideOnSuccess">Enter User ID</label>
                    <![endif]-->
					<input id="userID" name="userID" type="text" class="input-block-level" placeholder="User ID"
						value="<?php echo htmlspecialchars($userID); ?>">
                    <!--[if lt IE 10]>
                        <label class="control-label hideOnSuccess">Enter Password</label>
                    <![endif]-->
					<input id="userPassword" name="userPassword" type="password" class="input-block-level" placeholder="Password">
					<span class="loginGroup">
						<button class="btn btn-large btn-primary" type="submit">Sign in</button> 
						<span class="pwdRegLinks"><a id="newPwdLink" href="#resetModal" role="button" data-toggle="modal">Lost Password</a> <a href="#">|</a> 
							<a href="#accessModal" role="button" data-toggle="modal">Need Access?</a>
							<!-- <a href="usr_Registration.php">Need Access?</a> -->
						</span>
					</span>
				  </form>
		
			</div> 

			<div id="resetModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="pwdRstRequest" aria-hidden="true">
				<form id="resetPasswordForm" name="resetPasswordForm" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">Password Reset Request</h3>
				</div>
				<div class="modal-body">
					<div id="UserID_span" class="control-group">
						<label class="control-label">Enter your XCDE user ID and submit</label>
						<div class="controls">
							<input id="lpwdUserID" name="lpwdUserID" type="text" placeholder="User ID"
							class="input-medium " value="">
							<span id="UserID_helper" class="help-inline hide"><i>XCDE user ID is required</i></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
					<button id="submitReset" class="btn btn-primary" type="button">Submit</button>
				</div>
				<input type="hidden" id="resetAction" name="resetAction" value="0">
				</form>
			</div>
			<div id="accessModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="accessRequest" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="accessRequest">Requesting XCDE Access</h3>
				</div>
				<div class="modal-body">
					<p>This application is operated by the OEE Orchestration Config team in support of CI transport and monitoring.<p>

					<p>Provide the following
					information in an email to Charles Steele: csteele5@dxc.com:<br>
					<ul>
						<li>Full User Name</li>
						<li>Email Short Name</li>
						<li>Phone Number</li>
						<li>City, State/Province</li>
						<li>Country</li>
						<li>Reason for Access</li>
					</ul>
					<i>Thank you!</i>
				</p>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				</div>
			</div>


	
		</div> <!-- /container -->

        <script src="js/vendor/jquery-1.9.1.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>

        <script>
			$(document).ready(function(){		

				$('#newPwdLink').click(function(){
					//alert('set dirty');
					var e = document.getElementById("userID").value;
					$('#lpwdUserID').val(e);
				});	
	
				$('#submitReset').click(function(){
					valSuccess = 1;
					//alert('set dirty');
					$('#resetAction').val(1);

					var e = document.getElementById("lpwdUserID").value;

					if (e.length == 0) {
						valSuccess = 0;
						$("#UserID_span").addClass('error');
						$("#UserID_helper").fadeIn();
					} else {
						$("#UserID_span").removeClass('error');
						$("#UserID_helper").hide();
					}

					if (valSuccess == 1) {
						document.resetPasswordForm.submit();
					}

					
				});



				 $('[rel=tooltip]').tooltip(); 
				 
			});
			
        </script>
		
		<?php 
			sqlsrv_close( $conn);
		?>	
		
    </body>
</html>
