<?php
require_once 'Job/Controllers/JobMain.php';
require_once 'CSY2028/DatabaseTable.php';

class JobMainTest extends \PHPUnit\Framework\TestCase{
    private $mockJobTable;
    private $mockCategoryTable;
    
    public function setUp(){
        $this->mockJobTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockCategoryTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }

    public function testHomeWithLocation(){
        $mockJobs = [(object)['id' => 1, 'location' => 'New York', 'closingDate' => '2022-01-01', 'statusId' => 1],
                     (object)['id' => 2, 'location' => 'Los Angeles', 'closingDate' => '2022-01-01', 'statusId' => 1],
                     (object)['id' => 3, 'location' => 'New York', 'closingDate' => '2021-01-01', 'statusId' => 1]
        ]; 

        $getTestData = ['location' => 'New York'];

        $this->mockJobTable->expects($this->once())
                           ->method('findLike')
                           ->with('location', 'New York')
                           ->willReturn($mockJobs);

        $JobMainController = new \Job\Controllers\JobMain($this->mockJobTable, $this->mockCategoryTable, $getTestData);

        $result = $JobMainController->home();
        $this->assertEquals('main/index.html.php', $result['template']);
        $this->assertEquals('Home', $result['title']);
        $this->assertEquals('home', $result['class']);
        $this->assertEquals($mockJobs, $result['variables']['jobs']);
    }

    public function testHomeWithoutLocation(){
        $mockJobs = [(object)['id' => 1, 'location' => 'New York', 'closingDate' => '2022-01-01', 'statusId' => 1],
                     (object)['id' => 2, 'location' => 'Los Angeles', 'closingDate' => '2022-01-01', 'statusId' => 1],
                     (object)['id' => 3, 'location' => 'New York', 'closingDate' => '2021-01-01', 'statusId' => 1]
        ]; 

        $this->mockJobTable->expects($this->once())
                           ->method('find')
                           ->with('statusId', 1)
                           ->willReturn($mockJobs);

        $JobMainController = new \Job\Controllers\JobMain($this->mockJobTable, $this->mockCategoryTable, []);

        $result = $JobMainController->home();
        $this->assertEquals('main/index.html.php', $result['template']);
        $this->assertEquals('Home', $result['title']);
        $this->assertEquals('home', $result['class']);
    }
    
    public function testAbout(){
        $mockCategories = ['MockCategory1','MockCategory2','MockCategory3','MockCategory4'];

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);
        
        $JobMainController = new \Job\Controllers\JobMain($this->mockJobTable, $this->mockCategoryTable, []);

        $result = $JobMainController->About();
        $this->assertEquals('main/about.html.php', $result['template']);
        $this->assertEquals('About', $result['title']);
        $this->assertEquals('home', $result['class']);
        $this->assertEquals($mockCategories, $result['variables']['records']);
    }

    public function testFAQs(){
        $JobMainController = new \Job\Controllers\JobMain($this->mockJobTable, $this->mockCategoryTable, []);

        $result = $JobMainController->FAQs();
        $this->assertEquals('main/faqs.html.php', $result['template']);
        $this->assertEquals('FAQs', $result['title']);
        $this->assertEquals('home', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

}