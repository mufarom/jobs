<?php
require_once 'Job/Controllers/User.php';
require_once 'CSY2028/DatabaseTable.php';

class UserTest extends \PHPUnit\Framework\TestCase{
    private $mockUserTable;
    private $mockUserTypeTable;

    public function setUp(){
        $this->mockUserTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
        $this->mockUserTypeTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }


    public function testViewUsers(){
        $this->mockUserTable->expects($this->once())
                            ->method('findAll')
                            ->willReturn([
                                ['id' => 1, 'firstname' => 'John', 'surname' => 'Doe'],
                                ['id' => 2, 'firstname' => 'Jane', 'surname' => 'Doe'],
                            ]);
        
        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], []);

        $result = $UserController->viewUsers();

        $this->assertEquals('admin/viewUsers.html.php', $result['template']);
        $this->assertEquals('Admin Users', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertNotEmpty($result['variables']['users']);
    }

    public function testEditUserSetId() {
        $mockUserTypes = ['Admin', 'User'];
        $mockUser = ['id'=>1, 'name'=> 'John Doe', 'email'=>'johndoe@example.com'];

        $this->mockUserTypeTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockUserTypes);

        $this->mockUserTable->expects($this->once())
                            ->method('find')
                            ->willReturn($mockUser);

        $getTestData = ['id' => 1];

        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, $getTestData, []);

        $result = $UserController->editUser();
        $this->assertEquals('Edit User', $result['title']);
        $this->assertEquals($mockUser, $result['variables']['user']);
        $this->assertEquals($mockUserTypes, $result['variables']['userTypes']);
    }
    

    public function testEditUserNullId(){
        $mockUserTypes = ['Admin', 'User'];

        $this->mockUserTypeTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockUserTypes);

        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], []);

        $result = $UserController->editUser();
        $this->assertEquals('Add User', $result['title']);
        $this->assertEquals(null, $result['variables']['user']);
        $this->assertEquals($mockUserTypes, $result['variables']['userTypes']);
    }

    public function testValidEditUserSubmit() {
        $testPostData = ['user' => [
            'firstname'=> 'test',
            'surname' => 'user',
            'username' => 'testuser',
            'password_hash' => 'password',
            'userTypeId' => 1
        ]];        
    
        $this->mockUserTable->expects($this->once())
                            ->method('save')
                            ->with($testPostData['user']);
    
        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], $testPostData);

        $result = $UserController->editUserSubmit();
        $this->assertEquals('admin/editUserSuccess.html.php', $result['template']);
        $this->assertEquals('Edit User Success', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testInvalidEditUserSubmit() {
        $testPostData = ['user' => [
            'firstname'=> '',
            'surname' => '',
            'username' => '',
            'password_hash' => '',
            'userTypeId' => ''
        ]];        
    
        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], $testPostData);

        $errors = $UserController->editUserSubmit($testPostData['user']);
        $this->assertEquals(count($errors), 4);
    }

    public function testAdminRestriction(){

        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], []);

        $result = $UserController->adminRestriction();
        $this->assertEquals('admin/adminRestriction.html.php', $result['template']);
        $this->assertEquals('Restricted Access', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }
    
    public function testUserDelete(){ 
        $testPostData = ['id'=>1];
               
        $this->mockUserTable->expects($this->once())
                                ->method('genericDelete')
                                ->with('id', $testPostData['id']);

        $UserController = new \Job\Controllers\User($this->mockUserTable, $this->mockUserTypeTable, [], $testPostData);

        $expected = $UserController->viewUsers();
        $result = $UserController->deleteUserSubmit();
        
        $this->assertEquals($expected, $result);
    }
}