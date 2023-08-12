<?php
require_once 'Job/Controllers/Category.php';
require_once 'CSY2028/DatabaseTable.php';

class CategoryTest extends \PHPUnit\Framework\TestCase{
    private $mockCategoryTable;

    public function setUp(){
        $this->mockCategoryTable = $this->getMockBuilder('\CSY2028\DatabaseTable')->disableOriginalConstructor()->getMock();
    }

    public function testViewCategoriesAdmin(){
        $mockCategories = ['Mock Category 1','Mock Category 2','Mock Category 3'];

        $this->mockCategoryTable->expects($this->once())
                                ->method('findAll')
                                ->willReturn($mockCategories);
    
        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, [], []);

        $result = $CategoryController->viewCategoriesAdmin();
        $this->assertEquals('admin/categories.html.php', $result['template']);
        $this->assertEquals('Categories', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertNotEmpty($result['variables']['categories']);                              
    }

    public function testEditCategorySetId() {
        $mockCategory = ['id'=>1, 'name'=> 'Mock Category'];

        $this->mockCategoryTable->expects($this->once())
                            ->method('find')
                            ->willReturn($mockCategory);

        $getTestData = ['id' => 1];

        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, [], $getTestData);

        $result = $CategoryController->editCategory();
        $this->assertEquals('Edit Category', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals($mockCategory, $result['variables']['currentCategory']);
    }

    public function testCategoryNullId() {
        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, [], []);

        $result = $CategoryController->editCategory();
        $this->assertEquals('Add Category', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals(null, $result['variables']['currentCategory']);
    }

    public function testEditCategorySubmitWithId(){
        $testPostData = ['category'=>['id'=>1, 'name'=> 'Mock Category']];

        $this->mockCategoryTable->expects($this->once())
                            ->method('save')
                            ->with($testPostData['category']);

        $getTestData = ['id'=>1];

        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, $testPostData, $getTestData);

        $result = $CategoryController->editCategorySubmit();
        $this->assertEquals('admin/editCategorySuccess.html.php', $result['template']);
        $this->assertEquals('Edit Category Successful', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testEditCategorySubmitWithNullId(){
        $testPostData = ['category'=>['id'=>1, 'name'=> 'Mock Category']];

        $this->mockCategoryTable->expects($this->once())
                            ->method('save')
                            ->with($testPostData['category']);
        
        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, $testPostData, []);

        $result = $CategoryController->editCategorySubmit();
        $this->assertEquals('admin/addCategorySuccess.html.php', $result['template']);
        $this->assertEquals('Add Category Successful', $result['title']);
        $this->assertEquals('sidebar', $result['class']);
        $this->assertEquals([], $result['variables']);
    }

    public function testEditCategorySubmitErrors(){
        $testPostData = ['category'=>['id'=>'', 'name'=> '']];
        
        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, $testPostData, []);

        $errors = $CategoryController->editCategorySubmit();
        $this->assertEquals(count($errors), 4);
    }

    public function testCategoryDelete(){ 
        $testPostData = ['id'=>1];
               
        $this->mockCategoryTable->expects($this->once())
                                ->method('genericDelete')
                                ->with('id', $testPostData['id']);

        $CategoryController = new \Job\Controllers\Category($this->mockCategoryTable, $testPostData, []);

        $expected = $CategoryController->viewCategoriesAdmin();
        $result = $CategoryController->deleteCategorySubmit();
        
        $this->assertEquals($expected, $result);
    }
}