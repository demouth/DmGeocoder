<?php
ini_set('display_errors', 'ON');
error_reporting(E_ALL);

// UTF-8設定下でのみ動作します
mb_internal_encoding('UTF-8');

//composerを使ってinstallした場合はautoload.phpをrequireしてください
require_once realpath(__DIR__.'/../vendor/autoload.php');

//逆ジオコーディング
$addresses = Dm_Geocoder::reverseGeocode(35.6882074,139.7001416);
var_dump($result);
