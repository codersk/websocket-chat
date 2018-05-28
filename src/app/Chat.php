<?php

namespace app;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

  protected $clients;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
    if($this->clients)
      echo "Congratulations! the server is now running\n";
    else
      echo "Server is not running\n";
  }

  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    foreach ($this->clients as $client) {
      // Send msg to every connected client except to the sender
      if ($from !== $client) {
        $client->send($msg);
      }
    }
  }

  public function onClose(ConnectionInterface $conn) {
    // Remove closed connections
    $this->clients->detach($conn);
    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }

}