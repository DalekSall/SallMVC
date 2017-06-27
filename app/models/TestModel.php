<?php

class Attribute extends Model {

    //table name
    protected $table = 'te_test';

    // db fields
    protected $properties = array(
        'test',
    );

    public function __construct(){
        parent::__construct();
    }

}
