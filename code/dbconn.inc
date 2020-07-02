<?php
//phpinfo();


	if (!isset($baseDirPrefix)) {
	    $baseDirPrefix = '';
	} 

/* BEGIN primary database connection */

	require $baseDirPrefix.'vendor/aws-php-sdk-3.132.1/aws-autoloader.php';

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



$uid = 'admin';
$dbase = 'XCDEUI';
$displayStr = '';

$connectionInfo = array( "UID"=>$uid,
                         "PWD"=>$secret,
                         "Database"=>$dbase,
						 "CharacterSet" => "UTF-8");

$displayStr = 'UID: '.$connectionInfo['UID'].'<br>';
$displayStr .= 'PWD: '.$connectionInfo['PWD'].'<br>';
$displayStr .= 'Database: '.$connectionInfo['Database'].'<br>';
$displayStr .= 'CharacterSet: '.$connectionInfo['CharacterSet'].'<br>';


$conn = sqlsrv_connect( $db_hostname, $connectionInfo); 

if( $conn )
{
     /*echo "Connection established.<br>";*/
}
else
{
     echo "Connection could not be established.<br>";
     die( print_r( sqlsrv_errors(), true));
}
/* END primary database connection */


/*
$appFullWebLink = 'http://127.0.0.1/XCDEUI/';
$appSystemRoot = '/XCDEUI/';
$appAttachmentDestination = 'Attachment';
*/

define ('SITE_ROOT', realpath(dirname(__FILE__)));


?>