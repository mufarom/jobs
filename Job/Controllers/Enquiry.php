<?php
namespace Job\Controllers;
class Enquiry{
    private $EnquiryTable;
    private $CategoryTable;
    private $get;
    private $post;

    public function __construct(\CSY2028\DatabaseTable $EnquiryTable, \CSY2028\DatabaseTable $CategoryTable, array $get, array $post){
        $this->EnquiryTable = $EnquiryTable;
        $this->CategoryTable = $CategoryTable;
        $this->get = $get;
        $this->post = $post;
    }

    //View Contact Form
    public function contactForm($errors = []){
        $records = $this->CategoryTable->findAll();
        return [
            'template' => 'main/contact.html.php',
            'title' => 'Contact Us',
            'class' => 'sidebar',
            'variables' => ['records'=>$records, 'errors'=>$errors]
        ];
    }

    //Submit Contact Form
    public function contactSubmit(){
        $errors = $this->validateContact($this->post['enquiry']);

        if (count($errors) == 0) {
            $this->EnquiryTable->save($this->post['enquiry']);

            return [
                'template' => 'main/contactSuccess.html.php',
                'title' => 'Contact Us Success',
                'class' => 'home',
                'variables' => []
            ];
        }
        else{
            return $this->contactForm($errors);
        }
    }

    //View Enquiries Sent In By Applicants
    public function viewEnquiries(){
        $enquiries = $this->EnquiryTable->findAll();

        return [
            'template' => 'admin/viewEnquiries.html.php',
            'title' => 'Admin|Enquiries',
            'class' => 'sidebar',
            'variables' => ['enquiries'=>$enquiries]
        ];
    }

    //View Form To Reply To Enquiries Sent In By Applicants
    public function replyEnquiry($errors = []){
        $enquiry = $this->EnquiryTable->find('id', $this->get['id'] ?? $this->post['enquiry']['id']);

        return [
            'template' => 'admin/replyEnquiry.html.php',
            'title' => 'Admin|Reply Enquiry',
            'class' => 'sidebar',
            'variables' => ['enquiry'=>$enquiry, 'errors'=>$errors]
        ];
    }

    //Submit Reply Form 
    public function replyEnquirySubmit(){
        $errors = $this->validateEnquiryReply($this->post['enquiry']);

        if (count($errors) == 0) {
            $this->EnquiryTable->save($this->post['enquiry']);

            return [
                'template' => 'admin/replyEnquirySuccess.html.php',
                'title' => 'Admin|Reply Sent',
                'class' => 'sidebar',
                'variables' => []
            ];
        }
        else{
            return $this->replyEnquiry($errors);
        }
    }

    //Contact Form Validation
    public function validateContact($enquiry){
        $errors = [];

        if ($enquiry['firstname'] == ''){
            $errors[] = 'You Must Enter Your Firstame';
        }
        if ($enquiry['surname'] == ''){
            $errors[] = 'You Must Enter Your Surname';
        }
        if ($enquiry['email'] == ''){
            $errors[] = 'You Must Enter Your Email Address';
        }
        if ($enquiry['telephone'] == ''){
            $errors[] = 'You Must Enter Your Telephone Number';
        }
        if ($enquiry['enquiry'] == ''){
            $errors[] = 'You Must Enter Your Enquiry';
        }
        return $errors;
    }

    //Enquiry Reply Form Validation
    public function validateEnquiryReply($enquiry){
        $errors = [];

        if ($enquiry['reply'] == ''){
            $errors[] = 'You Must Enter A Reply';
        }
        return $errors;
    }
}