<?php

class Contact extends Fucoso\ORM\Model
{
    protected static $_meta = array(
        'database' => 'exampledb',
        'table' => 'contact',
        'pk' => 'id'
    );

    public $id;
    public $person_id;
    public $value;

    public function person()
    {
        return $this->hasParent("Person");
    }
}
