<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$container = $app->getContainer();

$container['logger'] = function($c) {
    
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../../logs/tictactoe.log");
    $logger->pushHandler($file_handler);
    
    return $logger;
};

$app->get('/tictactoe', function (Request $request, Response $response) {
    //$this->logger->addInfo("Tictactoe invoked");
    $response->getBody()->write(json_encode(array("text"=>"I'm Alive")));
        
    return $response;
});

$app->post('/tictactoe', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $this->logger->addInfo(serialize($data));
    
    $response->getBody()->write(json_encode($data));

    return $response;
});
    
$app->run();
