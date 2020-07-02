<!-- user param page -->
<!-- 7/11/14 - add WorkDataRestrictionLevelID -->

<?php



	if (isset($_GET['RefreshParams'])) {
		$RefreshParams = $_GET['RefreshParams'];
	}

	$TestUserID = (empty($TestUserID)) ? 'empty!' : $TestUserID;
	$RefreshParams = (empty($RefreshParams)) ? 0 : $RefreshParams;
	$paramsdebug = (empty($paramsdebug)) ? 0 : $paramsdebug;
	
	if ($RefreshParams == 1) {
		$_SESSION['defDateFormat'] = 'yyyy-mm-dd';

		$_SESSION['UserParams.prefDateFormat'] = '';

		$RefreshParams = 1;
	}

	if ($RefreshParams == 1) {
		$_SESSION['defDateFormat'] = 'yyyy-mm-dd';

		$_SESSION['UserParams.prefDateFormat'] = '';

		// check for user preferences
		$query="SELECT     UsersV2.TZID, UsersV2.prefDateFormat
				FROM         UsersV2 
				WHERE     (UsersV2.UserID = '$TestUserID')";
	

		$result = sqlsrv_query($conn,$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$_SESSION['UserParams.prefDateFormat'] = $row['prefDateFormat'];
				}				
			}			
			
		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		if ($_SESSION['UserParams.prefDateFormat'] == 0) {
			$_SESSION['UserParams.prefDateFormat'] = $_SESSION['defDateFormat'];
		}

	}



	if ($paramsdebug == 1) {
		// get database time
		$DBNow = '';
		$query="SELECT CONVERT(varchar(20), GetUTCDate(), 126) AS DBNow";	

		$result = sqlsrv_query($conn,$query);	
		
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$DBNow = $row['DBNow'];
				}				
			}			
			
		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);		

		echo "
		<b>Input Vars</b><br />
		TestUserID = ".$TestUserID.",
		RefreshParams = ".$RefreshParams.",
		WebNow = ".date('Y-m-dTH:i:s').",
		DBNow = ";
		echo $DBNow;
		//DBNow = ".$DBNow;
		
		echo "<br><br>";
		
	}


?>