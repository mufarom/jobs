<?php

namespace Job\Controllers;

class User
{   
    private $UserTable;
    private $UserTypeTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $UserTable, \CSY2028\DatabaseTable $UserTypeTable, array $get, array $post){
        $this->UserTable = $UserTable;
        $this->UserTypeTable = $UserTypeTable;
        $this->get = $get;
        $this->post = $post;
    }

    //View Users With Admin Access In The Database
    public function viewUsers(){
        $users = $this->UserTable->findAll();

        return [
            'template' => 'admin/viewUsers.html.php',
            'title' => 'Admin Users',
            'class' => 'sidebar',
            'variables' => ['users' => $users]
        ];
    }

    //View Form To Edit Or Add A User
    public function editUser($errors = []){
        if(isset($this->get['id']) || isset($this->post['user']['id'])) {
            $user = $this->UserTable->find('id', $this->get['id'] ?? $this->post['user']['id']);
        } else {
            $user = null;
        }

        $userTypes = $this->UserTypeTable->findAll();
        $title = $this->get['id'] ?? null ? 'Edit User' : 'Add User';

        return [
            'template' => 'admin/editUser.html.php',
            'title' => $title,
            'class' => 'sidebar',
            'variables' => ['user' => $user, 'userTypes' => $userTypes, 'title'=>$title, 'errors'=>$errors]
        ];
    }

    //Submit Form To Edit Or Add A User
    public function editUserSubmit(){
        $errors = $this->validateEditUser($this->post['user']);
        if (count($errors) == 0) {
            $user = $this->post['user'];
            $user['password_hash'] = password_hash($user['password_hash'], PASSWORD_DEFAULT);
            $this->UserTable->save($this->post['user']);
            return [
                'template' => 'admin/editUserSuccess.html.php',
                'title' => 'Edit User Success',
                'class' => 'sidebar',
                'variables' => []
            ];
        }else{
            return $this->editUser($errors);
        }
    }
    
    //Delete A User From The Database
    public function deleteUserSubmit(){
        $this->UserTable->genericDelete('id', $this->post['id']);

        return $this->viewUsers();
    }

    //Unset And Destroy User Sessions On Logout
    public function logout(){
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            unset($_SESSION['loggedin']);
            unset($_SESSION['client']);
            unset($_SESSION['admin']);
            session_destroy();
        }
    
        header('location: /jobMain/home');
    }

    //Display Restriction Message If Access Is Not Permitted
    public function adminRestriction(){
        return [
            'template' => 'admin/adminRestriction.html.php',
            'title' => 'Restricted Access',
            'class' => 'sidebar',
            'variables' => []
        ];
    }

    //Edit Or Add User Form Validation
    public function validateEditUser($user){
        $errors = [];
    
        if ($user['firstname'] == ''){
            $errors[] = 'You Must Enter A Firstname';
        }
        if ($user['surname'] == ''){
            $errors[] = 'You Must Enter A Surname';
        }
        if ($user['username'] == ''){
            $errors[] = 'You Must Enter A Username';
        }
        if ($user['password_hash'] == ''){
            $errors[] = 'You Must Enter A Password';
        }
        if ($user['userTypeId'] == ''){
            $errors[] = 'You Must Select A User Type';
        }
        return $errors;
    }
}