<?php
    class Users extends CI_Controller{

        public function __construct(){
            parent::__construct();

            // Loading Models
            $this->load->model("User_model");

        } // End of __construct


        /**
         * Default View & Very First Page to Land On
         * @return [type] [description]
         */
        public function index(){
            // Check if a view exists - Should never occur
            if(!file_exists(APPPATH.'views/users/index.php')){
                show_404();
            }

            // Form Rules
            $this->form_validation->set_rules('username', "Username", 'required');
            $this->form_validation->set_rules('password', "Password", 'required');

            // Check if form has been submited yet
            if($this->form_validation->run() === FALSE){
                // If not, just display form
                $this->load->view('templates/header');
                $this->load->view('users/index');
                $this->load->view('templates/footer');

            }else{
                // Otherwise, login to dashboard
                die("continue");
            }

            // TODO: Implement Login System

        } // End of login


        /**
         * register
         * @return [type] [description]
         */
        public function register(){

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('password_two', 'Confirmed Password', 'matches[password]');

            if($this->form_validation->run() === FALSE){
                // If form has not been submitted, reload View
                $this->load->view("templates/header");
                $this->load->view("users/registration");
                $this->load->view("templates/footer");
            }else{

                // md5 encrypt password
                $enc_password = md5($this->input->post('password'));

                // Send to User Model
                $this->User_model->register($enc_password);

                // Set Message
                $this->session->set_flashdata('user_registered', "You are now registered and can login");

                redirect('dashboard');
            }

        } // End of register


        /**
         * check_username_exists - Helper Method to determine uniqueness of a
         *      username
         * @param  String $username
         * @return Boolean true if username is in db; otherwise, false
         */
        public function check_username_exists($username){
            // Set Flash Message
            $this->form_validation->set_message('check_username_exists',
            'That username is taken. Please choose another one.');
            if($this->User_model->check_username_exists($username)){


                return true;
            }else{
                return false;
            }

        } // End of check_username_exists


        public function logout(){
            // TODO
        }

    } // End of Users class
?>
