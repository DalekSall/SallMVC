<?php
class FieldChecker {

    private $input;
    private $fillables;
    private $empties = array();

    public function __construct($input, $fillables){
        $this->input = $input;
        $this->fillables = $fillables;
    }

    public function checkFillables($checkNumeric = null){
        if(isset($checkNumeric)){
            $this->checkNumeric($checkNumeric);
        }

        $this->checkForEmtpy();

        foreach($this->fillables as $fillable => $fillableAlias){
            if(isset($this->empties[$fillable])){
                $this->empties[$fillable] = $fillableAlias;
            }
        }

        Session::set_flash("errors", $this->empties);
        if(count($this->empties) < 1){
            return true;
        }
        return false;
    }

    private function checkForEmtpy(){
        foreach($this->fillables as $fillable => $fillableAlias){
            $inputSet = $this->input->get($fillable);
            if(!$inputSet || $inputSet = ""){
                $this->empties[$fillable] = false;
            }
        }
    }

    private function checkNumeric($numericFields){
        foreach($numericFields as $inputName){
            $checkNumeric = preg_replace("/[^0-9]/","",$this->input->get($inputName));
            if(!is_numeric($checkNumeric)){
                $this->empties[$inputName] = false;
            }
        }
    }

}
