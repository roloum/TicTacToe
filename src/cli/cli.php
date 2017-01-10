<?php

/**
 * @author Rolando Umana<rolando.umana@gmail.com>
 *  
 *  Command line interface for the TicTacToe game
 */
require dirname(__FILE__) . '/../vendor/autoload.php';


try {
	//Instantiate Game CLI controller
    $controller = new \Game\Controller\CLI();
    
    //Retrieve command parameters
    $data = getopt("", array("channel_id:", "user_name:", "cmd:", "force::"));

    //Process the request and send the result to output
    printf("%s\n", $controller->processRequest($data)["text"]);
}
catch (Exception $e) {
	//Display message in case of error
    printf("Exception thrown: %s\n", $e->getMessage());
}
