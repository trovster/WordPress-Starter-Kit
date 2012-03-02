<?php

// include all the function components
$functions = glob(dirname(__FILE__) . '/functions.*.php');
foreach($functions as $function) {
	if(!in_array(basename($function), array('function.php'))) {
		require_once $function;
	}
}