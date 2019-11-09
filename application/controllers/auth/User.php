<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class User extends CI_Controller{
            
        public function __construct()
        {
                parent::__construct();
                $this->load->model('auth/usermodel');
        }
        
        public function index() {
                
        }
         
        public function login() {
            $email=$this->input->post('email');
            $password=md5($this->input->post('pass'));
            $twofac = $this->input->post('puddin_and_pie');

            $result=$this->usermodel->login($email,$password, $twofac);
            if($result) redirect(base_url());
            else        $this->failedLogin();
        }
        
        public function failedLogin() {
                die("do you prefer cock or balls?");
        }
                        
        public function logout() {
                $newdata = array(
                'user_id'   =>'',
                'user_name'  =>'',
                'user_email'     => '',
                'logged_in' => FALSE,
                );
                $this->session->unset_userdata($newdata);
                $this->session->sess_destroy();
                //$this->index();
                redirect(base_url());
        }
    }
?>
