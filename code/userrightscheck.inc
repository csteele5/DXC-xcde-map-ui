<!-- security page -->
<!--	
2/4/20 - adapt rights check for map UI
-->
<?php

	// if session doesn't exist... go to logout
 	if (!isset($_SESSION['userID']) || $_SESSION['userID'] == '') {
 		echo 'userID is BLANK.  Check userrightscheck.inc line 9';exit;
		header("Location: login.php");
		exit;
	}

	// if session has expired, then logout
	$_SESSION['session_time'] = time(); //got the login time for user in second 
     $session_logout = 900; //it means 15 minutes. 
     //and then check the time session 
    if($session_logout >= $_SESSION['session_time']){ 
	        //user session time is up 
	       //destroy the session 
	      session_destroy(); 
	     //redirect to login page 
	     header("Location:login.php"); 
	 }

	// if(1 ==1) {
	//        echo "Security Page.\n";
	// } else {
	//         echo "Security Page Failed.\n";
	// }

	if (isset($_GET['refreshrights'])) {
		$refreshrights = $_GET['refreshrights'];
		//echo 'REFRESH RIGHTS '.$refreshrights.'<br>';
	}
	
	if (isset($_GET['rightsdebug'])) {
		$rightsdebug = $_GET['rightsdebug'];
		//echo 'DEBUG RIGHTS '.$rightsdebug.'<br>';
	}

	$TestUserID = (empty($TestUserID)) ? 'empty!' : $TestUserID;
	$refreshrights = (empty($refreshrights)) ? 0 : $refreshrights;
	$rightsdebug = (empty($rightsdebug)) ? 0 : $rightsdebug;

	if (!isset($_SESSION['TWOSAppRights.DisableCommRepeat']) || $refreshrights == 1) {
		
		$_SESSION['TWOSAppRights.DisableCommRepeat'] = 0;

		$_SESSION['TWOSAppRights.AnyAssignedMgrrights'] = 0;

		$_SESSION['XCDEAppRights.AccessRights'] = 0;
		$_SESSION['XCDEAppRights.UserRights'] = 0;	
		$_SESSION['XCDEAppRights.AdminRights'] = 0;

		$refreshrights = 1;
	}



	if ($refreshrights == 1) {
		
		//echo 'Refresh Rights!<br>';

		$_SESSION['TWOSAppRights.DisableCommRepeat'] = 0;

		$_SESSION['TWOSAppRights.AnyAssignedMgrrights'] = 0;

		$_SESSION['XCDEAppRights.AccessRights'] = 0;
		$_SESSION['XCDEAppRights.UserRights'] = 0;	
		$_SESSION['XCDEAppRights.AdminRights'] = 0;


		// check for application admin rights
		// and check for control file manager rights
		$query="SELECT     UserID, DisableCommRepeat
				FROM       UsersV2         
				WHERE     (UserID = '$TestUserID')";		

		$result = sqlsrv_query($conn,$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$_SESSION['TWOSAppRights.DisableCommRepeat'] = $row['DisableCommRepeat'];
		
				}				
			}	
			
		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		// check for assigned manager rights
		$query="SELECT  TOP 1   UserID
				FROM       UsersV2         
				WHERE     (MgrSName = '$TestUserID')";		

		$result = sqlsrv_query($conn,$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$_SESSION['TWOSAppRights.AnyAssignedMgrrights'] = 1;
		
				}				
			}	
			
		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		// XCDE Rights 
		$query = "SELECT        UserID, UserRights, AdminRights
					FROM            XCDE_User
					WHERE        (UserID = N'$TestUserID')";

		$result = sqlsrv_query($conn,$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
			
			$rowCount = 0;			
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {	
					$rowCount++;
					$_SESSION['XCDEAppRights.AccessRights'] = 1;
					$_SESSION['XCDEAppRights.UserRights'] = $row['UserRights'];	
					$_SESSION['XCDEAppRights.AdminRights'] = $row['AdminRights'];	
					if ($row['AdminRights'] == 1) {
						$_SESSION['XCDEAppRights.UserRights'] = 1;
					}
				}				
			}			
			
		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		$refreshrights = 0;

	}


	/////////////////// BEGIN Array Based Rights Check /////////////////////

	// over write if these are supplied in a url 
	//$refreshrights = (empty($GET["refreshrights"] )) ? $refreshrights  : $GET["refreshrights"];
	//$rightsdebug = (empty($GET["rightsdebug"])) ? $rightsdebug : $GET["rightsdebug"];


	$ReturnDisableCommRepeat = (empty($_SESSION['TWOSAppRights.DisableCommRepeat'])) ? 0 : $_SESSION['TWOSAppRights.DisableCommRepeat'];
	
	$ReturnAnyAssignedMgrrights = (empty($_SESSION['TWOSAppRights.AnyAssignedMgrrights'])) ? 0 : $_SESSION['TWOSAppRights.AnyAssignedMgrrights'];

	// determine XCDE rights
	$ReturnXCDEAccessRights = (empty($_SESSION['XCDEAppRights.AccessRights'])) ? 0 : $_SESSION['XCDEAppRights.AccessRights'];
	$ReturnXCDEUserRights = (empty($_SESSION['XCDEAppRights.UserRights'])) ? 0 : $_SESSION['XCDEAppRights.UserRights'];
	$ReturnXCDEAdminRights = (empty($_SESSION['XCDEAppRights.AdminRights'])) ? 0 : $_SESSION['XCDEAppRights.AdminRights'];


	$AllAppRights = 0;

	// return the user's system rights
	if (
		1==2
		) {
		$AllAppRights = 1;

		$ReturnXCDEAccessRights = 1;	
		$ReturnXCDEUserRights = 1;  
		$ReturnXCDEAdminRights = 1;

	}	

	//DisableCommRepeat is intentionally left out of this automatic setting
	
	// for each of the Test values, loop through rights arrays to determine return value
	//echo "<br><br>test1 <br><br>";
	


	if ($rightsdebug == 1) {
		echo "
		<b>Input Vars</b><br />
		TestUserID = ".$TestUserID."
		<br /><br />
		ReturnDisableCommRepeat = ".$ReturnDisableCommRepeat.",
		ReturnAnyAssignedMgrrights = ".$ReturnAnyAssignedMgrrights."

		<br />
		ReturnXCDEAccessRights = ".$ReturnXCDEAccessRights.",
		ReturnXCDEUserRights = ".$ReturnXCDEUserRights.",
		ReturnXCDEAdminRights = ".$ReturnXCDEAdminRights."
		<br />";
	}


?>