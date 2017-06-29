<?php
    /**
     * Dashboard - Controller to handle all interactions with the dashboard
     * page.
     */
    class Dashboard extends CI_Controller{

        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // All SimplePages use a database connection
            $this->load->database();

            // HTML Tables library
            $this->load->library('table');
        } // End of __construct


        /**
         * view - Method to generate the entire dashboard page
         *      Asks Assignment, and Timestamp models for data
         * @return NULL
         */
        public function view(){
            $this->load->view('templates/header');
            $this->load->view('templates/nav');
            // TODO: Ask models for data to display
            $this->load->view('dashboard');
            $this->load->view('templates/footer');
        } // End of view

    } // End of Dashboard
?>
