<?php
    /**
     * Gradebook - Controller for All Functionalities in the Gradebook view
     */
    class Gradebook extends CI_Controller{

        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // All SimplePages use a database connection
            $this->load->database();

            // Loading Models
            $this->load->model('Section_model', 'Sections');
            $this->load->model('Student_model', 'Students');
            $this->load->model('Assignment_model', 'Assignments');

        } // End of __construct


        /**
         * view - Setup for main/default view for the gradebooks
         *
         * @return null
         */
        public function view(){
            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            $data['active'] = "gradebook";

            // Loading Views
            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);
            $this->load->view('gradebook/index', $data);
            $this->load->view('templates/footer');
        } // End of view


        public function add_student(){

        }


        public function add_grade(){
            // TODO
        }

    } // End of Gradebook Class
?>
