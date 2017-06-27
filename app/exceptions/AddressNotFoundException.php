<?php

class AddressNotFoundException extends Exception {
  public function __construct($uri) {
    $this->uri = $uri;
  }
}
