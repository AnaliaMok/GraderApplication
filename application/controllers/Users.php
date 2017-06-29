<?php
    class Users extends CI_Controller{

        public function register(){
            // TODO
        }


        public function login(){
            // Check if a view exists - Should never occur
            if(!file_exists(APPPATH.'views/users/login.php')){
                show_404();
            }
            
            // TODO: Implement Login System

            $this->load->view('templates/header');
            $this->load->view('users/login');
            $this->load->view('templates/footer');

        } // End of login


        public function logout(){
            // TODO
        }

    } // End of Users class
?>
