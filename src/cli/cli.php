<?php

require dirname(__FILE__) . '/../vendor/autoload.php';

use \Game\Controller;

try {
    $controller = new \Game\Controller\CLI();
    
    $data = getopt("", array("channel_id:", "user_name:", "cmd:", "force::"));

    printf("%s\n", $controller->processRequest($data)["text"]);
}
catch (Exception $e) {
    printf("Exception thrown: %s\n", $e->getMessage());
}
