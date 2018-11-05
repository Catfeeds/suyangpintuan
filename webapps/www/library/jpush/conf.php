<?php
require __DIR__ . '/autoload.php';

use JPush\Client as JPush;

//$app_key = getenv('app_key');
//$master_secret = getenv('master_secret');
//$registration_id = getenv('registration_id');

$app_key = '31cc1c808b500b3e2171fdb0';
$master_secret = '7f6e3ac31bdee649b7b573ca';
$client = new JPush($app_key, $master_secret);
return $client;