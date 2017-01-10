<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

use \Game\Controller;

$app = new \Slim\App;

$container = $app->getContainer();

$container['logger'] = function($c) {
    
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../../logs/tictactoe.log");
    $logger->pushHandler($file_handler);
    
    return $logger;
};

$app->get('/', function (Request $request, Response $response) {
    $this->logger->addInfo("Tictactoe GET method invoked");
    $response->getBody()->write(json_encode(array("text"=>"I'm Alive")));
        
    return $response;
});

$app->post('/', function (Request $request, Response $response) {
    
    $data = $request->getParsedBody();
    
    $this->logger->addInfo(sprintf("Tictactoe POST method invoked: %s", json_encode($data)));
    
    
    
    try {
        $controller = new \Game\Controller\REST();
        
        return $response->withJson($controller->processRequest($data));
    }
    catch (Exception $e) {
        $this->logger->critical($e->getMessage());
        
        return $response->withJson(array("text"=>"Server error. We are working on it!"), 500);
    }
    
});
    
$app->run();
