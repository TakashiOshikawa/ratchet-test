<?php

namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}


//use Ratchet\MessageComponentInterface;
//use Ratchet\ConnectionInterface;
//
//class Chat implements MessageComponentInterface {
//    public $clients;
//    private $logs;
//    private $connectedUsers;
//    private $connectedUsersNames;
//
//    public function __construct() {
//        $this->clients = new \SplObjectStorage;
//        $this->logs = [];
//        $this->connectedUsers = [];
//        $this->connectedUsersNames = [];
//    }
//
//    public function onOpen(ConnectionInterface $conn) {
//        $this->clients->attach($conn);
//        echo "New connection! ({$conn->resourceId})\n";
//        $conn->send(json_encode($this->logs));
//        $this->connectedUsers [$conn->resourceId] = $conn;
//    }
//
//    public function onMessage(ConnectionInterface $from, $msg) {
//        // Do we have a username for this user yet?
//        if (isset($this->connectedUsersNames[$from->resourceId])) {
//            // If we do, append to the chat logs their message
//            $this->logs[] = array(
//                "user" => $this->connectedUsersNames[$from->resourceId],
//                "msg" => $msg,
//                "timestamp" => time()
//            );
//            $this->sendMessage(end($this->logs));
//        } else {
//            // If we don't this message will be their username
//            $this->connectedUsersNames[$from->resourceId] = $msg;
//        }
//    }
//
//    public function onClose(ConnectionInterface $conn) {
//        // Detatch everything from everywhere
//        $this->clients->detach($conn);
//        unset($this->connectedUsersNames[$conn->resourceId]);
//        unset($this->connectedUsers[$conn->resourceId]);
//    }
//
//    public function onError(ConnectionInterface $conn, \Exception $e) {
//        $conn->close();
//    }
//
//    private function sendMessage($message) {
//        foreach ($this->connectedUsers as $user) {
//            $user->send(json_encode($message));
//        }
//    }
//}