<?php
namespace Job\Entity;

class Enquiry{
    public $UserTable;
    public $id;
    public $firstname;
    public $surname;
    public $email;
    public $telephone;
    public $enquiry;
    public $userId;
    public function __construct(\CSY2028\DatabaseTable $UserTable){
        $this->UserTable = $UserTable;
    }
    
    //Getting The Enquiry Status Of An Enquiry Whether It Is Replied To Or Not 
    public function getEnquiryStatus(){
        if (empty($this->userId)) {
            return 'Not Completed';
        } 
        else {
            $name = $this->UserTable->find('id', $this->userId)[0]->username;
            return 'Enquiry Completed By ' . $name;
        }
    }    
}