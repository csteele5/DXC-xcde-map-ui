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
| 	 Date Created: 1/8/2020 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	1/8/2020    - SQL Test page
---------------------------------------------------------------------------->		
<?php
	//session_start();
	echo '<br><br>Header Goes here.<br>Test1<br>';

	require '../vendor/aws-php-sdk-3.132.1/aws-autoloader.php';

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

	// $secret will contain the DB admin password
	echo '<br>aws_region: '.$aws_region;
	echo '<br>db_hostname: '.$db_hostname;
	
	//$serverName = "cfg-xcde-ui-dev.c6ohlwqflchy.us-east-1.rds.amazonaws.com,1433";

	$uid = 'admin';
	//$pwd = 'BlueIguanaWhitewater1';
	$dbase = 'XCDEUI';
	$dbaseFound = 0;
	$displayStr = '';


	//echo '<br>Server: '.$serverName.'<br>';
	/*
	$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$pwd,
	                         "Database"=>$dbase,
							 "CharacterSet" => "UTF-8");
	$displayStr .= 'Database: '.$connectionInfo['Database'].'<br>';
	*/
	$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$secret,
							 "CharacterSet" => "UTF-8");

	$displayStr = 'UID: '.$connectionInfo['UID'].'<br>';
	$displayStr .= 'PWD: '.$connectionInfo['PWD'].'<br>';
	$displayStr .= 'CharacterSet: '.$connectionInfo['CharacterSet'].'<br>';
	echo "<br>displayStr: ".$displayStr;

	$conn = sqlsrv_connect( $db_hostname, $connectionInfo); 
	if( $conn )
	{
	     echo "<br>Connection established.<br>";
	}
	else
	{
	     echo "<br>Connection could not be established.<br>";
	     die( print_r( sqlsrv_errors(), true));
	}


	$query = "SELECT dbid, [name] 
				FROM master.dbo.sysdatabases  ";

	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			echo 'Server Databases:<br>';
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
		$query = "SELECT TABLE_NAME 
					FROM ".$dbase.".INFORMATION_SCHEMA.TABLES  ";

		$result = sqlsrv_query($conn,$query);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				echo ' Tables:<br>';
				while($row = sqlsrv_fetch_array($result)) {

			       echo $row['TABLE_NAME'].'<br>' ;
			        
				}			
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
	echo '<br><br>Footer Goes here.';
?>			
		