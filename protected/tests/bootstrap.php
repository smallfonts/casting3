<?php
ob_start();
// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../yii-1.1.10.r3566/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

//command that replicates the structure of db of developement env to test env
$mainConfig = require dirname(__FILE__) . '/../config/main.php';
$testConfig = require dirname(__FILE__) . '/../config/test.php';

$mainDbConfig = $mainConfig['components']['db'];
$testDbConfig = $testConfig['components']['db'];

$m = array();
preg_match('~mysql:host=(.+);dbname=(.+)~is', $testDbConfig['connectionString'], $m);
$testDbConfig['host'] = $m[1];
$testDbConfig['dbname'] = $m[2];

if ($testDbConfig['password'] != "") $testDbConfig['password'] = "-p".$testDbConfig['password']; 

$m = array();
preg_match('~mysql:host=(.+);dbname=(.+)~is', $mainDbConfig['connectionString'], $m);
$mainDbConfig['host'] = $m[1];
$mainDbConfig['dbname'] = $m[2];
// Uncomment line 29 and comment line 27 when doing Selenium testing. Revert to original when testing is done.
//$mainDbConfig['dbname'] = 'casting3';  
if ($mainDbConfig['password'] != "")  $mainDbConfig['password'] = "-p".$mainDbConfig['password'];

$command = "mysqldump -h ".$mainDbConfig['host']." -u ".$mainDbConfig['username']." ".$mainDbConfig['password']." ".$mainDbConfig['dbname']." | mysql -h ".$testDbConfig['host']." -u ".$testDbConfig['username']." ".$testDbConfig['password']." ".$testDbConfig['dbname'];
exec($command);

echo "Bootstrap: Replicating development database structure to testing database";
echo $command."\r\n";

Yii::createWebApplication($config);

?>