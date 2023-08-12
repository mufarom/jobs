<?php
require_once 'Job/Controllers/Job.php';
require_once 'CSY2028/DatabaseTable.php';

class JobTest extends \PHPUnit\Framework\TestCase{
    private $mockJobTable;
    private $mockCategoryTable;
    private $mockApplicantsTable;
    private $mockStatusTable;
    
    public function setUp(){
        $this->mockJobTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockCategoryTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockApplicantsTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockStatusTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }

    public function testEditJobSetId(){
        $mockStati = ['Active', 'Archived'];
        $mockCategories = ['Mock Category 1','Mock Category 2','Mock Category 3'];
        $mockJob = ['id'=>1, 'title'=>'Mock Job', 'description'=>'Mock Description', 'salary'=>'00,000-00,000', 'closingDate'=>'1111-11-11', 'Mock CategoryId'=>1, 'location'=>'Mock Location', 'StatusId'=>1, 'userId'=>1 ];

        $this->mockStatusTable->expects($this->once())
                            ->method('findAll')
                            ->willReturn($mockStati);

        $this->mockCategoryTable->expects($this->once())
                            ->method('findAll')
                            ->willReturn($mockCategories);

        $this->mockJobTable->expects($this->once())
                            ->method('find')
                            ->willReturn($mockJob); 
                          
        $getTestData = ['id'=>1];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [],$getTestData);
        $result = $JobController->editJob();
        $this->assertEquals('admin/editJob.html.php', $result['template']);
        $this->assertEquals('Edit Job', $result['title']);
        $this->assertEquals($mockStati, $result['variables']['jobStatus']);
        $this->assertEquals($mockCategories, $result['variables']['stmt']);
        $this->assertEquals($mockJob, $result['variables']['job']);
    }

    public function testEditJobNullId(){
        $mockStati = ['Active', 'Archived'];
        $mockCategories = ['Mock Category 1','Mock Category 2','Mock Category 3'];
        
        $this->mockStatusTable->expects($this->once())
                            ->method('findAll')
                            ->willReturn($mockStati);

        $this->mockCategoryTable->expects($this->once())
                            ->method('findAll')
                            ->willReturn($mockCategories);

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [],[]);
        $result = $JobController->editJob();
        $this->assertEquals('admin/editJob.html.php', $result['template']);
        $this->assertEquals('Add Job', $result['title']);
        $this->assertEquals($mockStati, $result['variables']['jobStatus']);
        $this->assertEquals($mockCategories, $result['variables']['stmt']);
        $this->assertEquals(null, $result['variables']['job']);
    }

    public function testEditJobSubmitWithId(){
        $testPostData = ['job' =>['id'=>1, 'title'=>'Mock Job', 'description'=>'Mock Description', 'salary'=>'00,000-00,000', 'closingDate'=>'1111-11-11', 'categoryId'=>1, 'location'=>'Mock Location', 'statusId'=>1, 'userId'=>1 ]];        
    
        $this->mockJobTable->expects($this->once())
                            ->method('save')
                            ->with($testPostData['job']);
    
        $getTestData = ['id'=>1];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, $testPostData, $getTestData);

        $result = $JobController->editJobSubmit();
        $this->assertEquals('admin/editJobSuccess.html.php', $result['template']);
        $this->assertEquals('Edit Job Successful', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testEditJobSubmitWithNullId(){
        $testPostData = ['job' =>['id'=>1, 'title'=>'Mock Job', 'description'=>'Mock Description', 'salary'=>'00,000-00,000', 'closingDate'=>'1111-11-11', 'categoryId'=>1, 'location'=>'Mock Location', 'statusId'=>1, 'userId'=>1 ]];        
    
        $this->mockJobTable->expects($this->once())
                            ->method('save')
                            ->with($testPostData['job']);
    
        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, $testPostData, []);

        $result = $JobController->editJobSubmit();
        
        $this->assertEquals('admin/addJobSuccess.html.php', $result['template']);
        $this->assertEquals('Add Job Successful', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testEditJobSubmitErrors(){
        $testPostData = ['job' =>['id'=>'', 'title'=>'', 'description'=>'', 'salary'=>'', 'closingDate'=>'', 'categoryId'=>'', 'location'=>'', 'statusId'=>'', 'userId'=>'' ]];        
    
        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable,$testPostData, []);

        $errors = $JobController->editJobSubmit();
        $this->assertEquals(count($errors), 4);
    }

    public function testValidApply(){
        $testPostData = ['applicants'=>[
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'details' => 'Mock Details',
            'cv' => 'Mock CV'
        ]];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, $testPostData, []);
        $errors = $JobController->validateApply($testPostData['applicants']);
        $this->assertEquals(count($errors), 0);
    }

    public function testInvalidName(){
        $testPostData = ['applicants'=>[
            'name' => '',
            'email' => 'johndoe@example.com',
            'details' => 'Mock Details',
            'cv' => 'Mock CV'
        ]];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $testPostData);
        $errors = $JobController->validateApply($testPostData['applicants']);
        $this->assertEquals(count($errors), 1);
    }

    public function testInvalidNameAndEmail(){
        $testPostData = ['applicants'=>[
            'name' => '',
            'email' => '',
            'details' => 'Mock Details',
            'cv' => 'Mock CV'
        ]];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $testPostData);
        $errors = $JobController->validateApply($testPostData['applicants']);
        $this->assertEquals(count($errors), 2);
    }

    public function testInvalidNameAndEmailAndDetails(){
        $testPostData = ['applicants'=>[
            'name' => '',
            'email' => '',
            'details' => '',
            'cv' => 'Mock CV'
        ]];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $testPostData);
        $errors = $JobController->validateApply($testPostData['applicants']);
        $this->assertEquals(count($errors), 3);
    }
    
    public function testApplyForm(){
        $mockJob = (object)['id'=>1, 'title'=>'Mock Job', 'description'=>'Mock Description', 'salary'=>'00,000-00,000', 'closingDate'=>'1111-11-11', 'Mock CategoryId'=>1, 'location'=>'Mock Location', 'StatusId'=>1, 'userId'=>1];
        
        $this->mockJobTable->expects($this->once())
                           ->method('find')
                           ->willReturn($mockJob);

        $getTestData = ['id'=>1];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $getTestData);
        $result = $JobController->applyForm();
        $this->assertEquals('main/apply.html.php', $result['template']);
        $this->assertEquals('Apply', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockJob, $result['variables']['job']);
    }

    public function testViewJobs(){
        $mockJobs = [
            (object)['id' => 1, 'title' => 'Mock Job 1', 'description' => 'Mock Description', 'salary' => '00,000-00,000', 'closingDate' => '1111-11-11', 'categoryId' => 1, 'location' => 'Mock Location', 'statusId' => 1, 'userId' => 1],
            (object)['id' => 2, 'title' => 'Mock Job 2', 'description' => 'Mock Description', 'salary' => '00,000-00,000', 'closingDate' => '1111-11-11', 'categoryId' => 1, 'location' => 'Mock Location', 'statusId' => 1, 'userId' => 1],
            (object)['id' => 3, 'title' => 'Mock Job 3', 'description' => 'Mock Description', 'salary' => '00,000-00,000', 'closingDate' => '1111-11-11', 'categoryId' => 2, 'location' => 'Mock Location', 'statusId' => 1, 'userId' => 1],
        ];

        $mockCategories = [
            (object)['id' => 1, 'name' => 'Mock Category 1'],
            (object)['id' => 2, 'name' => 'Mock Category 2'],
        ];

        $this->mockJobTable->expects($this->once())
                           ->method('find')
                           ->willReturn($mockJobs);

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);

        $this->mockCategoryTable->expects($this->once())
                                ->method('find')
                                ->willReturn($mockCategories[0]->name);

        $getTestData = ['categoryId' => 1];

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $getTestData);

        $result = $JobController->viewJobs();
        $this->assertEquals('main/viewJobCategory.html.php', $result['template']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockCategories, $result['variables']['records']);
        $this->assertEquals([$mockJobs[0], $mockJobs[1]], $result['variables']['jobs']);
    }

    public function testViewJobsAdmin(){
        $mockUserJobs = [
            (object)['id' => 1, 'title' => 'Mock Job 1', 'categoryId' => 1, 'userId' => 1],
            (object)['id' => 2, 'title' => 'Mock Job 2', 'categoryId' => 2, 'userId' => 1],
            (object)['id' => 3, 'title' => 'Mock Job 3', 'categoryId' => 1, 'userId' => 1],
        ];
        $this->mockJobTable->expects($this->once())
                           ->method('find')
                           ->with('userId', $_SESSION['userId'])
                           ->willReturn($mockUserJobs);

        $mockCategories = [
            (object)['id' => 1, 'name' => 'Mock Category 1'],
            (object)['id' => 2, 'name' => 'Mock Category 2'],
            (object)['id' => 3, 'name' => 'Mock Category 3'],
        ];

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);

        $_SESSION['userId'] = 1;

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], []);
        $result = $JobController->viewJobsAdmin();

        $this->assertEquals('admin/jobs.html.php', $result['template']);
        $this->assertEquals('Job List', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockCategories, $result['variables']['stmt']);
    }

    public function testViewJobsAdminFiltered(){
        $mockUserJobs = [
            (object)['id' => 1, 'title' => 'Mock Job 1', 'categoryId' => 1, 'userId' => 1],
            (object)['id' => 2, 'title' => 'Mock Job 2', 'categoryId' => 2, 'userId' => 1],
            (object)['id' => 3, 'title' => 'Mock Job 3', 'categoryId' => 1, 'userId' => 1],
        ];
        
        $this->mockJobTable->expects($this->once())
                           ->method('find')
                           ->with('userId', $_SESSION['userId'])
                           ->willReturn($mockUserJobs);

        $mockCategories = [
            (object)['id' => 1, 'name' => 'Mock Category 1'],
            (object)['id' => 2, 'name' => 'Mock Category 2'],
            (object)['id' => 3, 'name' => 'Mock Category 3'],
        ];

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);

        $getTestData = ['categoryId' => 1];
        $_SESSION['userId'] = 1;

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, [], $getTestData);
        
        $result = $JobController->viewJobsAdmin();
        $this->assertEquals('admin/jobs.html.php', $result['template']);
        $this->assertEquals('Job List', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertArrayHasKey('jobs', $result['variables']);
        $this->assertCount(2, $result['variables']['jobs']);
        $this->assertArrayHasKey('stmt', $result['variables']);
        $this->assertEquals($mockCategories, $result['variables']['stmt']);
    }

    public function testCategoryDelete(){ 
        $testPostData = ['id'=>1];
           
        $this->mockJobTable->expects($this->once())
                           ->method('genericDelete')
                           ->with('id', $testPostData['id']);

        $JobController = new \Job\Controllers\Job($this->mockJobTable, $this->mockCategoryTable, $this->mockApplicantsTable, $this->mockStatusTable, $testPostData, []);

        $expected = $JobController->viewJobsAdmin();
        $result = $JobController->deleteJobSubmit();
    
        $this->assertEquals($expected, $result);
    }
}