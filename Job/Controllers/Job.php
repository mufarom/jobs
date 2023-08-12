<?php

namespace Job\Controllers;

class Job{
    private $JobTable;
    private $CategoryTable;
    private $ApplicantsTable;
    private $StatusTable;
    private $post;
    private $get;

    public function __construct($JobTable, $CategoryTable, $ApplicantsTable, $StatusTable, array $post, array $get){
        $this->JobTable = $JobTable;
        $this->CategoryTable = $CategoryTable;
        $this->ApplicantsTable = $ApplicantsTable;
        $this->StatusTable = $StatusTable;
        $this->post = $post;
        $this->get = $get;
    }

    //View Job Application Form
    public function applyForm($errors = []){
        $job = $this->JobTable->find('id', $this->get['id'] ?? $this->post['applicants']['jobId']);
                
        return [
            'template' => 'main/apply.html.php',
            'title' => 'Apply',
            'class' => 'sidebar',
            'variables' => ['job' => $job,'errors' => $errors]
        ];
    }   

    //Submit Job Application Form
    public function applySubmit(){
        $errors = $this->validateApply($this->post['applicants']);
        
        if(count($errors) == 0){
            if ($_FILES['cv']['error'] == 0) {
                $parts = explode('.', $_FILES['cv']['name']);
                $extension = end($parts);
                $fileName = uniqid() . '.' . $extension;
                move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);

                $applicant = $this->post['applicants'];
                $applicant['cv'] = $fileName;
                $this->ApplicantsTable->save($applicant);

                $template = 'main/applySuccess.html.php';
                $title = 'Application Successful';
                $class = 'sidebar';
                $variables = [];
            }
        } 
        else{
            return $this->applyForm($errors);
        } 
        
        return [
            'template' => $template,
            'title' => $title,
            'class' => $class,
            'variables' => $variables
        ];
    }

    //View Jobs By Category If Active
    public function viewJobs(){
        if(isset($this->get['categoryId'])){
            $activeJobs = $this->JobTable->find('statusId', 1);
            $jobs = [];
            foreach($activeJobs as $job){
                if($job->categoryId == $this->get['categoryId']){
                    $jobs[] = $job;
                }
            }
            $title = ($this->CategoryTable->find('id', $this->get['categoryId']))[0]->name ?? null;
        }
        $records = $this->CategoryTable->findAll();

        $variables = ['records' => $records, 'jobs' => $jobs];

        return [
            'template' => 'main/viewJobCategory.html.php',
            'title' => $title,
            'class' => 'sidebar',
            'variables' => $variables
        ];
    }

    //View Logged In User Posted Jobs 
    public function viewJobsAdmin(){
        if(isset($this->get['categoryId'])){
            $userJobs = $this->JobTable->find('userId', $_SESSION['userId']);
            $jobs = [];
            foreach($userJobs as $job){
                if($job->categoryId == $this->get['categoryId']){
                    $jobs[] = $job;
                }
            }
        }else{
            $jobs = $this->JobTable->find('userId', $_SESSION['userId']);
        }

        $stmt = $this->CategoryTable->findAll();

        return [
            'template' => 'admin/jobs.html.php',
            'title' => 'Job List',
            'class' => 'sidebar',
            'variables' => ['jobs' => $jobs, 'stmt'=>$stmt]
        ];
    }

    //View Edit Or Add Job Form
    public function editJob($errors = []){
        $job = (isset($this->get['id']) || isset($this->post['job']['id'])) ? $this->JobTable->find('id', $this->get['id'] ?? $this->post['job']['id'] ) : null;
        $stmt = $this->CategoryTable->findAll();
        $jobStatus = $this->StatusTable->findAll();

        $variables = ['job' => $job, 'stmt' => $stmt, 'errors' => $errors, 'jobStatus'=> $jobStatus];

        return [
            'template' => 'admin/editJob.html.php',
            'title' => $this->get['id'] ?? null ? 'Edit Job' : 'Add Job',
            'class' => 'sidebar',
            'variables' => $variables
        ];
    }

    //Submit Edit Or Add Job Form
    public function editJobSubmit(){
        $errors = $this->validateEditJob($this->post['job']);

        if (count($errors) == 0){
            $this->JobTable->save($this->post['job']);

            return [
                'template' => $this->get['id'] ?? null ? 'admin/editJobSuccess.html.php' : 'admin/addJobSuccess.html.php',
                'title' => $this->get['id'] ?? null ? 'Edit Job Successful' : 'Add Job Successful',
                'class' => 'sidebar',
                'variables' => []
            ];
        }
        else{
            return $this->editJob($errors);
        }  
    }

    //Delete A Job From The Database
    public function deleteJobSubmit(){
        $this->JobTable->genericDelete('id', $this->post['id']);
        
        return $this->viewJobsAdmin();
    }

    //View Applicants That Applied For A Certain Job
    public function listApplicants(){
        $jobs = $this->JobTable->find('id', $this->get['id'])[0]->getApplicants();
        $job = $this->JobTable->find('id', $this->get['id']);

        $variables = ['jobs' => $jobs, 'job'=>$job];

        return [
            'template' => 'admin/applicants.html.php',
            'title' => $job[0]->title . ' Applicants',
            'class' => 'sidebar',
            'variables' => $variables
        ];
    }

    //Edit Or Add Job Form Validation
    public function validateEditJob($job){
        $errors = [];

        if ($job['title'] == ''){
            $errors[] = 'You Must Enter A Job Title';
        }
        if ($job['description'] == ''){
            $errors[] = 'You Must Enter A Job Description';
        }
        if ($job['location'] == ''){
            $errors[] = 'You Must Enter A Job Location';
        }
        if ($job['salary'] == ''){
            $errors[] = 'You Must Enter A Job Salary';
        }
        if ($job['categoryId'] == ''){
            $errors[] = 'You Must Select A Job Category';
        }
        if ($job['closingDate'] == ''){
            $errors[] = 'You Must Enter A Job Application Closing Date';
        }
        if ($job['statusId'] == ''){
            $errors[] = 'You Must Select A Job Status';
        }

        return $errors;
    }

    //Job Application Form Validation
    public function validateApply($applicants){
        $errors = [];

        if ($applicants['name'] == ''){
            $errors[] = 'You Must Enter A Name';
        }
        if ($applicants['email'] == ''){
            $errors[] = 'You Must Enter An Email Address';
        }
        if ($applicants['details'] == ''){
            $errors[] = 'You Must Enter Your Cover Letter';
        }
        return $errors;
    }
}