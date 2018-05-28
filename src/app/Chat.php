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
}