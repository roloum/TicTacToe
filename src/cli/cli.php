<?php

require dirname(__FILE__) . '/../vendor/autoload.php';

use \Game\Controller;

try {
	$controller = new \Game\Controller\CLI();
	
	$data = getopt("", array("channel_id:", "user_name:", "cmd:"));

	printf("%s\n", $controller->processRequest($data)["text"]);
}
catch (Exception $e) {
	printf("%s\n", $e->getMessage());
}
