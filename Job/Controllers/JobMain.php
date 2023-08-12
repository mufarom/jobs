<?php
namespace Job\Controllers;
class JobMain
{
    private $JobTable;
    private $CategoryTable;
    private $get;

    public function __construct($JobTable, $CategoryTable, array $get){
        $this->JobTable = $JobTable;
        $this->CategoryTable = $CategoryTable;
        $this->get = $get;
    }

    //View Jobs (Limit: 10) That Are Active And Sort By Closing Date In Ascending Order
    //If Location Filter Form Is Submitted Show Jobs With The Location Entered
    public function home(){
        $date = new \DateTime();
        if(isset($this->get['location'])){
            $jobsByLocation = $this->JobTable->findLike('location', $this->get['location']);
            $jobs = [];
            foreach($jobsByLocation as $job){
                if($job->closingDate < $date && $job->statusId == 1){
                    $jobs[] = $job;
                }
            }
        }
        else{
            $activeJobs = $this->JobTable->find('statusId', 1);
            $jobs = [];
            foreach($activeJobs as $job){
                if($job->closingDate < $date){
                    $jobs[] = $job;
                }
            }
            usort($jobs, function($a, $b) {
                return strtotime($a->closingDate) - strtotime($b->closingDate);
            });

            $jobs = array_slice($jobs, 0, 10);
        }

        return [
            'template' => 'main/index.html.php',
            'title' => 'Home',
            'class' => 'home',
            'variables' => ['jobs' => $jobs]
        ];
    }

    //View About Page Showing Available Categories
    public function about(){
        $records = $this->CategoryTable->findAll();

        return [
            'template' => 'main/about.html.php',
            'title' => 'About',
            'class' => 'home',
            'variables' => ['records' => $records]
        ];
    }

    //View FAQs Page
    public function faqs(){
        return [
            'template' => 'main/faqs.html.php',
            'title' => 'FAQs',
            'class' => 'home',
            'variables' => []
        ];
    }
}