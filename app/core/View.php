<?php
class View
{

    private $_template;
    private $_data;
    private $viewDirectory = 'views/';

    function __construct($template, $dir = null)
    {
        $this->viewDirectory .= $dir;
        $this->_template = $template;
        $this->_data = array();
    }

    public function set_data(array $data)
    {
        foreach($data as $key => $value)
            $this->set_value($key, $value);
    }

    public function set_value($key, $value)
    {
        $this->_data[$key] = $value;
    }

    // If return is true render will return rendered page in a variable
    public function render($return = false)
    {
        if(isset($this->_data))
        {
            extract($this->_data, EXTR_SKIP);
        }

        if($return)
        {
            ob_start();
        }

        require $this->viewDirectory . 'header.html';
        require $this->viewDirectory . $this->_template;
        require $this->viewDirectory . 'footer.html';

        if($return)
        {
            return ob_get_clean();
        }
    }

}
