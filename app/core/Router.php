<?php


class Router {

    private $parts; // he he he

    public function __construct() {
        $this->routes = Config::get('routes');
    }

    public function parse($uri) {
        $this->uri = $uri;
        $this->parts = $this->parse_parts($uri);
    }

    private function parse_parts($uri) {
        $parts = $this->filter_parts(explode('/', $uri));
        return $parts;
    }

    private function filter_parts($parts) {
        return array_filter($parts,function($part){
            if($part != "") {
                return True;
            }
            return False;
        });
    }

    public function run() {
        if(!is_null($this->uri)) {

            $controllerMethod = array_slice($this->parts, 0, 2);
            $controllerMethodUri = "/";
            foreach($controllerMethod as $part){
                $controllerMethodUri .= $part."/";
            }

            foreach($this->routes as $address => $internalUri) {
                if($controllerMethodUri === $address) {
                    $this->uri = NULL;

                    unset($this->parts[1]);
                    unset($this->parts[2]);

                    foreach($this->parts as $part){
                        $internalUri .= $part."/";
                    }

                    $this->parts = $this->parse_parts($internalUri);
                    $this->run();
                    return;
                }
            }
        }

        if(count($this->parts) == 1){
            $this->parts[] = "index";
        }
        if(count($this->parts) < 2) {
            throw new AddressNotFoundException($this->uri);
        }

        $controller = ucfirst(array_shift($this->parts));

        $method = array_shift($this->parts);

        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        $method = $request_method . "_" . $method;
        $params = $this->parts;
        if(is_subclass_of($controller, 'Controller') && method_exists($controller, $method)) {
            $controllerInstance = new $controller();
        } else {
            throw new AddressNotFoundException($this->uri);
        }
        $data = call_user_func_array(array(
            $controllerInstance,
            $method
        ),$params);

        if(isset($controllerInstance->template)){
            $view = new View($controllerInstance->template);
            if(!empty($data)){
                $view->set_data($data);
                $view->render();
            } else{
                $view->render();
            }
        }
    }

}
