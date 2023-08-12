<?php
require_once 'Job/Controllers/Enquiry.php';
require_once 'CSY2028/DatabaseTable.php';

class EnquiryTest extends \PHPUnit\Framework\TestCase{
    private $mockEnquiryTable;
    private $mockCategoryTable;
    
    public function setUp(){
        $this->mockEnquiryTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockCategoryTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }

    public function testContactForm(){
        $mockCategories = ['Mock Category 1','Mock Category 2','Mock Category 3'];

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);
        
        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], []);

        $result = $EnquiryController->contactForm();
        $this->assertEquals('main/contact.html.php', $result['template']);
        $this->assertEquals('Contact Us', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockCategories, $result['variables']['records']);
    }

    public function testContactSubmit(){
        $testPostData = ['enquiry'=>[
            'id'=>1,
            'firstname'=>'John',
            'surname'=>'Doe',
            'email'=>'johndoe@example.com',
            'telephone'=>'000000000',
            'enquiry'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse tincidunt euismod leo sed rutrum. Aliquam commodo eleifend massa, sed dapibus eros pretium sit amet. Cras eu dui at nisl consequat ullamcorper. Aliquam vulputate vel ante et elementum. Maecenas sagittis dignissim eros ut ornare. Pellentesque quis aliquam elit, eu volutpat lacus. Proin sollicitudin dui eu mi auctor volutpat. Aliquam ut nulla et ante tincidunt posuere. Vestibulum suscipit lorem eu dolor consectetur facilisis. Curabitur aliquet sed lorem ut volutpat. Ut interdum pellentesque nisi non pretium.'
        ]];

        $this->mockEnquiryTable->expects($this->once())
                               ->method('save')
                               ->with($testPostData['enquiry']);

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], $testPostData);

        $result = $EnquiryController->contactSubmit();
        $this->assertEquals('main/contactSuccess.html.php', $result['template']);
        $this->assertEquals('Contact Us Success', $result['title']);
        $this->assertEquals('home', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testContactSubmitErrors(){
        $testPostData = ['enquiry'=>[
            'id'=>1,
            'firstname'=>'',
            'surname'=>'',
            'email'=>'',
            'telephone'=>'',
            'enquiry'=>''
        ]];

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], $testPostData);

        $errors = $EnquiryController->contactSubmit();
        $this->assertEquals(count($errors), 4);
    }

    public function testViewEnquiries(){
        $mockEnquiries = [['id'=>1,'firstname'=>'John','surname'=>'Doe','email'=>'johndoe@example.com','telephone'=>'000000000','enquiry'=>'Mock Enquiry 1'],
                          ['id'=>2,'firstname'=>'Mock2','surname'=>'Mock2','email'=>'mock2@example.com','telephone'=>'111111111','enquiry'=>'Mock Enquiry 2'],
                          ['id'=>3,'firstname'=>'Mock3','surname'=>'Mock3','email'=>'mock3@example.com','telephone'=>'222222222','enquiry'=>'Mock Enquiry 3']
        ];

        $this->mockEnquiryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockEnquiries);

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], []);

        $result = $EnquiryController->viewEnquiries();
        $this->assertEquals('admin/viewEnquiries.html.php', $result['template']);
        $this->assertEquals('Admin|Enquiries', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertNotEmpty($result['variables']['enquiries']);
    }

    public function testReplyEnquiry(){
        $mockEnquiry = ['id'=>1,'firstname'=>'John','surname'=>'Doe','email'=>'johndoe@example.com','telephone'=>'000000000','enquiry'=>'Mock Enquiry 1'];

        $this->mockEnquiryTable->expects($this->once())
                               ->method('find')
                               ->willReturn($mockEnquiry);
        $getTestData = ['id'=>1];

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, $getTestData, []);

        $result = $EnquiryController->replyEnquiry();
        $this->assertEquals('admin/replyEnquiry.html.php', $result['template']);
        $this->assertEquals('Admin|Reply Enquiry', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockEnquiry, $result['variables']['enquiry']);
    }

    public function testReplyEnquirySubmit(){
        $testPostData = ['enquiry'=>[
            'reply'=>'Mock Reply'
        ]];

        $this->mockEnquiryTable->expects($this->once())
                           ->method('save')
                           ->with($testPostData['enquiry']);

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], $testPostData);

        $result = $EnquiryController->replyEnquirySubmit();
        $this->assertEquals('admin/replyEnquirySuccess.html.php', $result['template']);
        $this->assertEquals('Admin|Reply Sent', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testReplyEnquirySubmitErrors(){
        $testPostData = ['enquiry'=>[
            'id'=>1,
            'reply'=>''
        ]];

        $EnquiryController = new \Job\Controllers\Enquiry($this->mockEnquiryTable, $this->mockCategoryTable, [], $testPostData);

        $errors = $EnquiryController->replyEnquirySubmit();
        $this->assertEquals(count($errors), 4);
    }
}