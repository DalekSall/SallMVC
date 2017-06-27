<?php

class Input {

    const JSON = "JSON";
    const GET = "GET";
    const POST = "POST";

    // $type "JSON", "POST", "GET"
    function __construct($type) {
        switch($type) {
        case self::JSON:
            $this->input = json_decode(file_get_contents('php://input'), true);
            break;
        case self::POST:
            $this->input = $_POST;
            break;
        case self::GET:
            $this->input = $_GET;
            break;
        }
        $this->sanitizeInput();
    }

    public function get($name, $default = NULL) {
        if(isset($this->input[$name])) {
            return $this->input[$name];
        }
        return $default;
    }

    public function set($name, $value){
        $this->input[$name] = $value;
    }

    public function un_set($name){
        unset($this->input[$name]);
    }

    public function sanitizeInput(){
        foreach($this->input as $column => $input){
            if($column == "email"){
                $this->input['email'] = Sanitize::email_sanitize($input);
                $this->input['email'] = Sanitize::email_validate($this->input['email']);
            } elseif($column == "password"){
                continue;
            } else{
                $this->input[$column] = Sanitize::sanitize_string($input);
            }
        }
    }

    public function get_url_clean($string) {
        $string = strToLower($string);
        $delimiter = "-";

        $clean = preg_replace(array('/æ/', '/ø/', '/å/'), array('ae', 'oe', 'aa'), $string);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

}
