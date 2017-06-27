<?php
abstract class Model
{

    protected $db;

    public function __construct()
    {
        $this->db = Config::db();
    }

    public function prepare()
    {
        $updateArray = array();
        foreach($this->properties as $property)
        {
            if($property == "created_at"){
                $updateArray[':' . $property] = date("Y-m-d H:i:s");
            } else{
                if(!isset($this->{$property})){
                    $this->{$property} = NULL;
                }
                $updateArray[':' . $property] = $this->{$property};
            }
        }

        return array('array' => $updateArray, 'string' => implode($this->format_update_properties(), ','));
    }

    public function insert()
    {
        $prepare = self::prepare();

        $create = $this->db->prepare("INSERT INTO `{$this->table}` SET " . $prepare['string']);
        $success = $create->execute($prepare['array']);
        $this->id = $this->db->lastInsertid();

        return $success;
    }

    public function update()
    {
        $prepare = self::prepare();

        $prepare['array']['id'] = $this->id;
        $update = $this->db->prepare("UPDATE `{$this->table}` SET " . $prepare['string'] . " WHERE id = :id");
        $success = $update->execute($prepare['array']);

        return $success;
    }

    public function save()
    {
        if(isset($this->id) && !is_null($this->id))
        {
            return self::update();
        } else {
            return self::insert();
        }
    }

    public static function get($id, $column = NULL)
    {
        if(!isset($column))
        {
            $column = "id";
        }

        $model = new static;

        $columnStr = implode($model->format_select_properties(), ',');

        $selectQuery = $model->db->prepare("SELECT id, $columnStr FROM `{$model->table}` WHERE $column = :id");
        $selectQuery->execute(array(":id" => $id));

        $queryResult = $selectQuery->fetch(PDO::FETCH_ASSOC);
        if($queryResult !== FALSE)
        {
            foreach($queryResult as $column => $value)
            {
                $model->{$column} = $value;
            }

            return $model;
        } else { // Nothing found
            return NULL;
        }
    }

    public static function all($postStatement = NULL, $wheres = array())
    {
        if(!isset($postStatement))
        {
            $postStatement = "";
        } else {
            if(stristr($postStatement, "WHERE") && !stristr($postStatement, ":"))
            {
                throw new ID10TException("Remember to use prepared Statements");
            }
        }

        $model = new static;

        $wrappedProperties = $model->format_select_properties();
        $wrappedProperties[] = '`id`';
        $columnStr = implode($wrappedProperties, ',');

        $selectQuery = $model->db->prepare("SELECT id, $columnStr FROM `{$model->table}` ".$postStatement);
        $success = $selectQuery->execute($wheres);

        if(!$success)
        {
            throw new Exception(var_export($selectQuery->errorInfo(), TRUE));
        }

        $modelArray = array();
        while($queryResult = $selectQuery->fetch(PDO::FETCH_ASSOC))
        {
            $model = new static;

            foreach($queryResult as $column => $value)
            {
                $model->{$column} = $value;
            }

            $modelArray[] = $model;
        }
        return $modelArray;
    }

    public function update_from_input($input, $update = null)
    {
        foreach($this->properties as $column)
        {
            $columnValue = $input->get($column);

            if($input->get($column) && !empty($update))
            {
                $this->{$column} = $input->get($column);
            } elseif(isset($columnValue)){
                $this->{$column} = $input->get($column);
            }
        }
    }

    public function to_array()
    {
        $out = array();
        $out['id'] = $this->id;

        foreach($this->properties as $column)
        {
            $out[$column] = $this->{$column};
        }

        return $out;
    }

    private function format_update_properties()
    {
        $wrappedProperties = array();

        foreach($this->properties as $property)
        {
            $wrappedProperties[] = "`$property` = :$property";
        }

        return $wrappedProperties;
    }

    private function format_select_properties()
    {
        $wrappedProperties = array();

        foreach($this->properties as $property)
        {
            $wrappedProperties[] = "`$property`";
        }

        return $wrappedProperties;
    }

}
