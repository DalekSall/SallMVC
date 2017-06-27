<?php

class ID10TException extends Exception {
  public function __construct($exception) {

		echo "<pre style='text-align:left'>";
		var_dump($exception);
		echo "</pre>";
  }
}
