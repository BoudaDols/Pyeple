<?php


namespace App\Server;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{

    private $clients;

    public function __construct(){
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn){
        $this->clients->attach($conn);
        echo sprintf('Connection from #%d started \n', $conn->resourceId);
        $conn->send(sprintf('New connection: Hello #%d', $conn->resourceId));
    }

    public function onClose(ConnectionInterface $closedConnection){
        $this->clients->detach($closedConnection);
        echo sprintf('Connection #%d has disconnected \n', $closedConnection->resourceId);
    }

    public function onError(ConnectionInterface $conn, \Exception $e){
        $conn->send('An error has occurred: '.$e->getMessage());
        $conn->close();
    }


    function onMessage(ConnectionInterface $from, $msg){
        // TODO: Implement onMessage() method.
    }

    function onConnect(ConnectionInterface $from, $msg){

    }
}