<?php

namespace Job\Controllers;

class Category{
    private $CategoryTable;
    private $post;
    private $get;

    public function __construct(\CSY2028\DatabaseTable $CategoryTable, array $post, array $get){
        $this->CategoryTable = $CategoryTable;
        $this->post = $post;
        $this->get = $get;
    }

    //View Categories In The Database
    public function viewCategoriesAdmin(){
        $categories = $this->CategoryTable->findAll();

        return [
            'template' => 'admin/categories.html.php',
            'title' => 'Categories',
            'class' => 'sidebar',
            'variables' => ['categories' => $categories]
        ];
    }

    //View Form To Edit Or Add A Category In The Database
    public function editCategory($errors = []){
        $currentCategory = (isset($this->get['id']) || isset($this->post['catgeory']['id'])) ? $this->CategoryTable->find('id', $this->get['id'] ?? $this->post['catgeory']['id']) : null;

        return [
            'template' => 'admin/editCategory.html.php',
            'title' => $this->get['id'] ?? null ? 'Edit Category' : 'Add Category',
            'class' => 'sidebar',
            'variables' => ['currentCategory' => $currentCategory, 'errors'=>$errors]
        ];
    }

    //Submit Form To Edit Or Add A Category In The Database
    public function editCategorySubmit(){
        $errors = $this->validateEditCategory($this->post['category']);

        if (count($errors) == 0) {
            $this->CategoryTable->save($this->post['category']);

            return [
                'template' => $this->get['id'] ?? null ? 'admin/editCategorySuccess.html.php' : 'admin/addCategorySuccess.html.php',
                'title' => $this->get['id'] ?? null ? 'Edit Category Successful' : 'Add Category Successful',
                'class' => 'sidebar',
                'variables' => []
            ];
        }
        else{
            return $this->editCategory($errors);
        }
    }

    //Delete Category From The Database
    public function deleteCategorySubmit(){
        $this->CategoryTable->genericDelete('id', $this->post['id']);
        return $this->viewCategoriesAdmin();
    }

    //Edit Or Add Category Form Validation
    public function validateEditCategory($category){
        $errors = [];

        if ($category['name'] == ''){
            $errors[] = 'You Must Enter A Category Name';
        }
        return $errors;
    }
}