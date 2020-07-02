<?php session_start(); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@dxc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 1/23/2020 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	1/23/2020    - SQL Test page, post formation
---------------------------------------------------------------------------->		
<?php
	//session_start();
	$baseDirPrefix = '../';
	include '../dbconn.inc';
	echo '<H4>System Startup Status</H4>';
	echo "displayStr: ".$displayStr;
/*
	require 'vendor/aws-php-sdk-3.132.1/aws-autoloader.php';

	use Aws\SecretsManager\SecretsManagerClient; 
	use Aws\Exception\AwsException;

	$aws_region  = getenv("AWS_REGION");
	$db_hostname = getenv("DB_HOSTNAME");
	$secret_id   = getenv("DB_SECRET_ARN");

	$secrets_client = new SecretsManagerClient([
	    'version' => '2017-10-17',
	    'region' => $aws_region
	]);

	try {
	    $result = $secrets_client->getSecretValue([
	        'SecretId' => $secret_id
	    ]);
	} catch(AwsException $e) {
	    $error = $e->getAwsErrorCode();
	    throw $e;
	}

	if (isset($result['SecretString'])) {
	    $secret = $result['SecretString'];
	} else {
	    $secret = base64_decode($result['SecretBinary']);
	}
	*/

	// $secret will contain the DB admin password
	// Local settings
	/*
	$aws_region  = "Local";
	$db_hostname = "127.0.0.1,1433";
	$dbase = 'XCDEUI';
	$uid = 'phpUser';
	$secret = 'phpUser';
	echo '<b>Database Connection Information</b><br>';
	echo '<br>aws_region: '.$aws_region;
	echo '<br>db_hostname: '.$db_hostname;
	echo '<br>dbase: '.$dbase;
	echo '<br>uid: '.$uid;
	*/
	
	//$serverName = "cfg-xcde-ui-dev.c6ohlwqflchy.us-east-1.rds.amazonaws.com,1433";

	$dbaseFound = 0;
	/*
	$displayStr = '';

	$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$secret,
							 "CharacterSet" => "UTF-8");

	$displayStr = 'UID: '.$connectionInfo['UID'].'<br>';
	$displayStr .= 'PWD: '.$connectionInfo['PWD'].'<br>';
	$displayStr .= 'CharacterSet: '.$connectionInfo['CharacterSet'].'<br>';
	//echo "<br>displayStr: ".$displayStr;

	$conn = sqlsrv_connect( $db_hostname, $connectionInfo); 
	if( $conn )
	{
	     echo "<br><b>Connection to server established!</b><br>";
	}
	else
	{
	     echo "<br>Connection to server could not be established.<br>";
	     die( print_r( sqlsrv_errors(), true));
	}
	*/


	$query = "SELECT dbid, [name] 
				FROM master.dbo.sysdatabases  ";

	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			echo '<br><b>Server Databases:</b><br>';
			while($row = sqlsrv_fetch_array($result)) {

		       echo $row['dbid'].': '.$row['name'].'<br>' ;
		       if ($row['name'] == $dbase) {
		       		$dbaseFound = 1;
		       }
		        
			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}
	sqlsrv_free_stmt($result);


	if ($dbaseFound == 1) {
		echo "<b>Target Database Found!</b><br>";

		/*
		$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$secret,
	                         "Database"=>$dbase,
							 "CharacterSet" => "UTF-8");

		$connDB = sqlsrv_connect( $db_hostname, $connectionInfo); 
		if ($connDB){
		     echo "<br><b>Connection to ".$dbase." established!</b><br>";
		} else {
		     echo "<br>Connection to ".$dbase." could not be established.<br>";
		     die( print_r( sqlsrv_errors(), true));
		}
		*/

// ".$dbase.".
		$query = "SELECT TABLE_NAME 
					FROM INFORMATION_SCHEMA.TABLES
					WHERE TABLE_TYPE = 'BASE TABLE'  ";
		//echo $query;
		$result = sqlsrv_query($conn,$query);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
			echo ' <br><br><b>Tables:</b><br>';
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

			       echo $row['TABLE_NAME'].'<br>' ;
			        
				}			
			} else {
				echo ' No tables returned.<br>';
			}			

		} catch (exception $e) {
			print_r($e);
		}
		sqlsrv_free_stmt($result);


		$query = "SELECT TABLE_NAME 
					FROM INFORMATION_SCHEMA.VIEWS  ";
		//echo $query;
		$result = sqlsrv_query($conn,$query);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
			echo ' <br><br><b>Views:</b><br>';
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

			       echo $row['TABLE_NAME'].'<br>' ;
			        
				}			
			} else {
				echo ' No views returned.<br>';
			}			

		} catch (exception $e) {
			print_r($e);
		}
		sqlsrv_free_stmt($result);


		$query = "SELECT UserID, EmployeeName
					FROM UsersV2  ";
		//echo $query;
		$result = sqlsrv_query($conn,$query);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
			echo ' <br><br><b>Users:</b><br>';
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

			       echo $row['UserID'].' | '.$row['EmployeeName'].'<br>' ;
			        
				}			
			} else {
				echo ' No Users returned.<br>';
			}			

		} catch (exception $e) {
			print_r($e);
		}
		sqlsrv_free_stmt($result);



	} else {
		echo '<br><b>ERROR:  Target Database NOT found!</b><br>';
	}


	/*
	phpinfo();
	*/



	$appFullWebLink = 'https://xcde-map-ui.platformdxc-sb2.com';
	$appSystemRoot = '/';

?>		
		

		
<?php
	echo '<br><br><i>End of Test.</i>';
	sqlsrv_close($conn);
	//sqlsrv_close($connDB);
?>			
		