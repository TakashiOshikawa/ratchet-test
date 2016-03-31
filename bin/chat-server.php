<?php
use Ratchet\Server\IoServer;
use MyApp\Chat;
//use MyApp\BasicPubSub;

require dirname(__DIR__) . '/vendor/autoload.php';


//$server = IoServer::factory(
//    new Chat(),
//    8080,
//    'localhost'
//);
//
//$server->run();

require 'vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

//require 'src/MyApp/Chat.php';

// Run the server application through the WebSocket protocol on port 8080
$app = new Ratchet\App("localhost", 8080, '0.0.0.0', $loop);
$app->route('/chat', new Chat, array('*'));

$app->run();