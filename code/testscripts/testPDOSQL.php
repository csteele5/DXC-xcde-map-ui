<?php
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
//echo '<br>aws_region: '.$aws_region;
//echo '<br>db_hostname: '.$db_hostname;
echo '<br>Testing of database connection to manually created AWS DB with PDO drivers: 1';
echo '<br><br>';
//$server   = $db_hostname.',1433';
//$server   = '127.0.0.1,1433';
$server   = 'cfg-xcde-ui-dev.c6ohlwqflchy.us-east-1.rds.amazonaws.com,1433';
$database = 'PDXCTWOS';
$username = 'admin';
$password = 'BlueIguanaWhitewater1';

# Connect
try {
    $conn = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error connecting to SQL Server".$e->getMessage());
}

# End
echo 'Connected to '.$database.'<br>';
echo 'Query databases 1:<br>';

$query = "SELECT dbid, [name] 
				FROM master.dbo.sysdatabases  ";


$stmt = $conn->query( $query );  
while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){  
   print_r( $row['dbid'].': '.$row['name'] ."<br>" );  
}  


$conn = null;

//exit;


try {
    $conn = new PDO("sqlsrv:server=$server", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error connecting to SQL Server".$e->getMessage());
}

# End
echo '<br>Connected to '.$server.'<br>';
echo 'Query databases 2:<br>';

$query = "SELECT dbid, [name] 
				FROM master.dbo.sysdatabases  ";


$stmt = $conn->query( $query );  
while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ){  
   print_r( $row['dbid'].': '.$row['name'] ."<br>" );  
}  



$conn = null;

?>