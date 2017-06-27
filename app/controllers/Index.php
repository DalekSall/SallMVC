<?php

class Index extends Controller {

    public $template;

    public function get_index() {
        $this->template = "index/index.html";

        return $this->data;
    }
}
