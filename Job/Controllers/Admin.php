<?php
namespace Job\Controllers;
class Admin{
    private $UserTable;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $UserTable, array $post){
        $this->UserTable = $UserTable;
        $this->post = $post;
    }

    //View Admin Login Form
    public function adminForm(){
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
            $template = 'admin/adminIndex.html.php';
            $title = 'Admin Login';
            $class = 'sidebar';
            $variables = [];
        }
        else{
            $template = 'admin/adminHome.html.php';
            $title = 'Admin Home';
            $class = 'sidebar';
            $variables = [];
        }
    
        return [
            'template' => $template,
            'title' => $title,
            'class' => $class,
            'variables' => $variables
        ];
    } 

    //Submit Admin Login Form And Validate Entered Credentials
    public function adminFormSubmit(){
        $user = $this->UserTable->find('username', $this->post['username']);
        if (!empty($user) && password_verify($this->post['password'], $user[0]->password_hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['userId'] = $user[0]->id;
            if($user[0]->userTypeId == 1){
                $_SESSION['admin'] = true;
                $title = 'Admin Home | Admin';
            }elseif($user[0]->userTypeId == 2){
                $_SESSION['client'] = true;
                $title = 'Admin Home | Client';
            }else{
                return $this->adminForm();
            }
        }
        else{
            return $this->adminForm();
        }
        return [
            'template' => 'admin/adminHome.html.php',
            'title' => $title,
            'class' => 'sidebar',
            'variables' => []
        ];
    }   
}