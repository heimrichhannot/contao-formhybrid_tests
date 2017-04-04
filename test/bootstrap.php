<?php

error_reporting(E_ALL);

define('TL_MODE', 'FE');
define('UNIT_TESTING', true);

require __DIR__ . '/../../../../system/initialize.php';

global $objPage;

$objPage = new PageModel();
$objPage->id = 1;