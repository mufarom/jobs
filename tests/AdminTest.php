<?php
require_once 'Job/Controllers/Admin.php';
require_once 'CSY2028/DatabaseTable.php';

class AdminTest extends \PHPUnit\Framework\TestCase{
    private $mockUserTable;

    public function setUp(){
        $this->mockUserTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }

    public function testAdminFormNotLoggedIn() {
        // unset session variable
        $_SESSION['loggedin'] = false;

        $AdminController = new \Job\Controllers\Admin($this->mockUserTable, []);

        $result = $AdminController->adminForm();
        $this->assertEquals('admin/adminIndex.html.php', $result['template']);
        $this->assertEquals('Admin Login', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testAdminFormLoggedIn() {
        // set session variable to true
        $_SESSION['loggedin'] = true;

        $AdminController = new \Job\Controllers\Admin($this->mockUserTable, []);

        $result = $AdminController->adminForm();
        $this->assertEquals('admin/adminHome.html.php', $result['template']);
        $this->assertEquals('Admin Home', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testAdminFormSubmitAdmin(){        
        $mockUser = (object) ['id' => 1, 'firstname'=> 'test', 'surname' => 'user', 'username' => 'testuser', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'userTypeId' => 1];

        $testPostData = ['username'=>'testuser', 'password'=>'password'];

        $this->mockUserTable->expects($this->once())
                            ->method('find')
                            ->with('username', $testPostData['username'])
                            ->willReturn([$mockUser]);

        $AdminController = new \Job\Controllers\Admin($this->mockUserTable, $testPostData);

        $result = $AdminController->adminFormSubmit();
        $this->assertTrue($_SESSION['loggedin']);
        $this->assertEquals(1, $_SESSION['userId']);
        $this->assertTrue($_SESSION['admin']);
        $this->assertEquals('Admin Home | Admin', $result['title']);
        $this->assertEquals('admin/adminHome.html.php', $result['template']);
        $this->assertEquals('sidebar', $result['class']);
    }

    public function testAdminFormSubmitClient(){        
        $mockUser = (object) ['id' => 1, 'firstname'=> 'test', 'surname' => 'user', 'username' => 'testuser', 'password_hash' => password_hash('password', PASSWORD_DEFAULT), 'userTypeId' => 2];

        $testPostData = ['username'=>'testuser', 'password'=>'password'];

        $this->mockUserTable->expects($this->once())
                            ->method('find')
                            ->with('username', $testPostData['username'])
                            ->willReturn([$mockUser]);

        $AdminController = new \Job\Controllers\Admin($this->mockUserTable, $testPostData);

        $result = $AdminController->adminFormSubmit();
        $this->assertTrue($_SESSION['loggedin']);
        $this->assertEquals(1, $_SESSION['userId']);
        $this->assertTrue($_SESSION['client']);
        $this->assertEquals('Admin Home | Client', $result['title']);
        $this->assertEquals('admin/adminHome.html.php', $result['template']);
        $this->assertEquals('sidebar', $result['class']);
    }
    
}