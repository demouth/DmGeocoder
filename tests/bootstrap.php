<?php

ini_set('display_errors', 'ON');
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');


$LIB_DIR = realpath(dirname(__FILE__).'/../src/').'/';
require_once $LIB_DIR.'Dm/Geocoder.php';
require_once $LIB_DIR.'Dm/Geocoder/Address.php';
require_once $LIB_DIR.'Dm/Geocoder/Prefecture.php';
require_once $LIB_DIR.'Dm/Geocoder/Query.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Finder.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Reader.php';


require_once realpath(__DIR__.'/../vendor/autoload.php');
