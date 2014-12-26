<?php

class Person extends Fucoso\ORM\Model
{
    protected static $_meta = array(
        'database' => 'exampledb',
        'table' => 'person',
        'pk' => 'id'
    );

    public $id;
    public $name;
    public $birthday;
    public $salary;

    public function contacts()
    {
        return $this->hasChildren("Contact");
    }
}
