<?php
namespace Job\Entity;

class User{
    public $UserTypeTable;

    public $id;
    public $firstname;
    public $surname;
    public $username;
    public $password;
    public $userTypeId;

    public function __construct(\CSY2028\DatabaseTable $UserTypeTable){
        $this->UserTypeTable = $UserTypeTable;        
    }

    //Getting The User Type Name From The User Type ID In The User Table
    public function getUserTypeName(){
        return $this->UserTypeTable->find('id', $this->userTypeId)[0]->name;
    }
}