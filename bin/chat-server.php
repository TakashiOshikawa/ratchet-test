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

// 実行時に引数がない場合chatというチャンネル作成
if (empty($args)) $app->route('chat', new Chat(), ['*']);

// 引数を受け取っている場合引数分の引数名のチャンネル作成
foreach ($argv as $s) {
    $app->route($s, new Chat(), ['*']);
}

$app->run();