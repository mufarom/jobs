<?php
namespace Job\Entity;
class Job{
    public $CategoryTable;
    public $ApplicantsTable;
    public $JobTable;
    public $StatusTable;

    public $id;
    public $title;
    public $description;
    public $salary;
    public $closingDate;
    public $categoryId;
    public $location;
    public $statusId;
    public $userId;

    public function __construct(\CSY2028\DatabaseTable $CategoryTable, \CSY2028\DatabaseTable $ApplicantsTable, \CSY2028\DatabaseTable $StatusTable){
        $this->CategoryTable = $CategoryTable;
        $this->ApplicantsTable = $ApplicantsTable;
        $this->StatusTable = $StatusTable;
    }

    //Getting The Category Name Of The Job From The Category ID In The Job Table
    public function getCategoryName(){
        return $this->CategoryTable->find('id', $this->categoryId)[0]->name;
    }

    //Getting Applicant Details Using The Job ID In The Job Table
    public function getApplicants() {
        return $this->ApplicantsTable->find('jobId', $this->id);
    }

    //Getting A Count Of The Number Of Applicants Per Job Posting
    public function getApplicantsCount() {
        return count($this->getApplicants());
    }

    //Getting The Status Name Of The Job From The Status ID In The Job Table
    public function getStatusName(){
        return $this->StatusTable->find('id', $this->statusId)[0]->name;
    }
}