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
	echo '<br><br>Header Goes here.<br><br>';

	$serverName = "cfg-xcde-ui-dev.c6ohlwqflchy.us-east-1.rds.amazonaws.com,1433";

	$uid = 'admin';
	$pwd = 'BlueIguanaWhitewater1';
	$dbase = 'PDXCTWOS';
	$displayStr = '';


	echo 'Server: '.$serverName.'<br>';
	/*
	$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$pwd,
	                         "Database"=>$dbase,
							 "CharacterSet" => "UTF-8");
	*/
	$connectionInfo = array( "UID"=>$uid,
	                         "PWD"=>$pwd,
							 "CharacterSet" => "UTF-8");

	$displayStr = 'UID: '.$connectionInfo['UID'].'<br>';
	$displayStr .= 'PWD: '.$connectionInfo['PWD'].'<br>';
	$displayStr .= 'Database: '.$connectionInfo['Database'].'<br>';
	$displayStr .= 'CharacterSet: '.$connectionInfo['CharacterSet'].'<br>';
	echo "<br>displayStr: ".$displayStr;

	$conn = sqlsrv_connect( $serverName, $connectionInfo); 
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
		        
			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}
	sqlsrv_free_stmt($result);

	/*
	phpinfo();
	*/

	$appFullWebLink = 'https://xcde-map-ui.platformdxc-sb2.com';
	$appSystemRoot = '/';

?>		
		

		
<?php
	echo '<br><br>Footer Goes here.';
?>			
		