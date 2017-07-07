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
                // Get username
                $username = $this->input->post("username");
                // Get and encrypt password
                $enc_password = md5($this->input->post("password"));

                // Login User ID
                $user_id = $this->User_model->login($username, $enc_password);

                if($user_id){
                    // Create session
                    $user_data = array(
                        'user_id'       =>  $user_id,
                        'username'      =>  $username,
                        'logged_in'     =>  true
                    );

                    $this->session->set_userdata($user_data);

                    // Set Message
                    $this->session->set_flashdata("user_loggedin", "You are now logged in");
                    redirect("dashboard");
                }else{
                    // Set error
                    $this->session->set_flashdata("login_failed", "Login is invalid");
                    redirect("users/index");
                }
            }

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

                redirect('index');
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

            return $this->User_model->check_username_exists($username);
        } // End of check_username_exists


        public function logout(){
            // Kill session data
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('username');

            $this->session->set_flashdata('user_loggedout', "Successfully Logged Out");

            redirect("users/index");
        }

    } // End of Users class
?>
