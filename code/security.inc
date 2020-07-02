<!-- security page -->

<?php
	// check for submitted header variable from cfm pages
	if (isset($_POST['HTTP_SMUSER'])) {
		$_SESSION['userID'] = strtolower($_POST['HTTP_SMUSER']);

		$params = array($_SESSION['userID']);
		
		// This query method is used to prevent injection attacks.
		$query="SELECT userID, EmployeeName, City, TWOSPword, Phone, cellPhone  FROM UsersV2 WHERE userID=?";	
		/*echo $query.'<br>';*/
		
		$result = sqlsrv_query($GLOBALS['conn'],$query,$params);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
	
		try {	  
			$test = sqlsrv_has_rows($result);
			
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
			} 
			
			
		} catch (exception $e) {
			print_r($e);
		}
	
		sqlsrv_free_stmt($result);
	}
	// END check for submitted header variable from cfm pages
	$userID = '';
	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
	}

	// $TestUserID = (empty($TestUserID)) ? 'empty!' : $TestUserID;
	function authLogUser($chkUserID) {
		$chkUserID = trim($chkUserID);
		if ($chkUserID == '') {
			//echo 'Line 55 of security.inc.  chkUserID';exit;
			$loginURL = 'login.php';
			if ($_SERVER['SCRIPT_NAME'] <> '') {
				$loginURL.="?destination=".$_SERVER['SCRIPT_NAME'];
				if ($_SERVER['QUERY_STRING'] <> '') {
					$loginURL.="?".$_SERVER['QUERY_STRING'];
				}
			}
			header("Location: ".$loginURL);
			exit;
			//echo "<br>loginURL ".$loginURL;
		}

		
		if (strlen(trim($_SERVER['QUERY_STRING'])) > 255) {
			$URLString = left(trim($_SERVER['QUERY_STRING']),255);
		} else {
			$URLString = trim($_SERVER['QUERY_STRING']);
		}

		// insert page 
		$insScript_Name=addslashes($_SERVER['SCRIPT_NAME']); 
		$insURLString=addslashes($URLString); 
		/*
		$query = "INSERT INTO PageVisits
				  (Script_Name, Query_String, UserID, DateVisited)
				  VALUES     ('$insScript_Name', '$insURLString', '$chkUserID', GETUTCDATE())";
	
		$result = sqlsrv_query($GLOBALS['conn'],$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
		*/

	}  

	authLogUser($userID);

?>