<?php
/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Entry point for the API, implemented in Slim framework 
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

use \Game\Controller;

$app = new \Slim\App;

//Get application container
$container = $app->getContainer();

//Set logger object
$container['logger'] = function($c) {
    
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../../logs/tictactoe.log");
    $logger->pushHandler($file_handler);
    
    return $logger;
};

//Process the GET request from the index and returns a code 200
//For whenever slack pings the application URL
$app->get('/', function (Request $request, Response $response) {
    $this->logger->addInfo("Tictactoe GET method invoked");
    $response->withJson(array("text"=>"I'm Alive"));
        
    return $response;
});

//Process the POST request with the TicTacToe command
$app->post('/', function (Request $request, Response $response) {
    
	//Retrieve parameters from Request body
    $data = $request->getParsedBody();
    
    //Log request
    $this->logger->addInfo(sprintf("Tictactoe POST method invoked: %s", json_encode($data)));
    
    try {
    	//Instantiate controller
        $controller = new \Game\Controller\REST();
        
        return $response->withJson($controller->processRequest($data));
    }
    catch (Exception $e) {
        $this->logger->critical($e->getMessage());
        
        return $response->withJson(array("text"=>"Server error. We are working on it!"), 500);
    }
    
});

//Run SLIM application
$app->run();
