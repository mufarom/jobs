<?php
namespace Job;
class Routes implements \CSY2028\Routes
{
    public function getController($name){
        require '../database.php';

        //Database Tables Definition & Construction
        $CategoryTable = new \CSY2028\DatabaseTable($pdo, 'category', 'id', '\Job\Entity\Category' );
        $ApplicantsTable = new \CSY2028\DatabaseTable($pdo, 'applicants', 'id');
        $StatusTable = new \CSY2028\DatabaseTable($pdo, 'status', 'id');
        $JobTable = new \CSY2028\DatabaseTable($pdo, 'job', 'id', '\Job\Entity\Job', [ $CategoryTable, $ApplicantsTable, $StatusTable]);
        $UserTypeTable = new \CSY2028\DatabaseTable($pdo, 'userType', 'id', '\Job\Entity\UserType');
        $UserTable = new \CSY2028\DatabaseTable($pdo, 'user', 'id', '\Job\Entity\User', [$UserTypeTable]);
        $EnquiryTable = new \CSY2028\DatabaseTable($pdo, 'enquiry', 'id', '\Job\Entity\Enquiry', [$UserTable]);

        //Controller Definition & Construction
        $controllers = [];
        $controllers['jobMain'] = new \Job\Controllers\JobMain($JobTable, $CategoryTable, $_GET);
        $controllers['job'] = new \Job\Controllers\Job($JobTable, $CategoryTable, $ApplicantsTable, $StatusTable, $_POST, $_GET);
        $controllers['admin'] = new \Job\Controllers\Admin($UserTable, $_POST);
        $controllers['user'] = new \Job\Controllers\User($UserTable,$UserTypeTable, $_GET, $_POST);
        $controllers['category'] = new \Job\Controllers\Category($CategoryTable, $_POST, $_GET);
        $controllers['enquiry'] = new \Job\Controllers\Enquiry($EnquiryTable, $CategoryTable, $_GET, $_POST);

        return $controllers[$name];
    }

    //Definition Of Default Route
    public function getDefaultRoute(){
        return 'jobMain/home';
    }

    //Access Verification Function
    public function loginCheck($route){
        $loginRoutes = [];
        //job
        $loginRoutes['job/edit'] = true;
        $loginRoutes['job/viewJobsAdmin'] = true;
        //applicants
        $loginRoutes['job/listApplicants'] = true;
        //category
        $loginRoutes['category/edit'] = true;
        $loginRoutes['category/viewCategoriesAdmin'] = true;

        $adminLoginRoutes = [];
        //user
        $adminLoginRoutes['user/viewUsers'] = true;
        $adminLoginRoutes['user/editUser'] = true;
        $adminLoginRoutes['user/deleteUserSubmit'] = true;
        //enquiry
        $adminLoginRoutes['enquiry/viewEnquiries'] = true;
        //category
        $adminLoginRoutes['category/deleteCategorySubmit'] = true;

        $requiresAdminLogin = $adminLoginRoutes[$route] ?? false;
        $requiresLogin = $loginRoutes[$route] ?? false;

        if (!isset($_SESSION['loggedin']) && $requiresLogin) {
            header('location: /user/adminForm');
            return;
        }

        if (isset($_SESSION['client']) && !isset($_SESSION['admin']) && $requiresAdminLogin) {
            header('location: /user/adminRestriction');
            return;
        }
    }
}