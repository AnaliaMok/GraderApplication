<?php
    class SimplePages extends CI_Controller{

        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // All SimplePages use a database connection
            $this->load->database();

        } // End of __construct


        /**
         * view - Function that directs to pages that contain content for
         *      reading only. By default, directs to the dashboard page
         *
         *      Directs to: dashboard, calendar, backup, settings
         *
         * @param  string $page name of page to load
         * @return null
         */
        public function view($page='dashboard'){

            // Loading HTML Tables Library
            $this->load->library('table');

            // Check if a view exists
            if(!file_exists(APPPATH.'views/simplepages/'.$page.'.php')){
                show_404();
            }

            // Holds vars we want to pass to view
            //$data['title'] = ucfirst($page);

            // Loading views
            $this->load->view("templates/header");
            $this->load->view("templates/nav");

            // Directing to proper function
            switch ($page){
                case 'calendar':
                    $this->calendar();
                    break;
                default:
                    // Dashboard is defaulted screen
                    $this->dashboard();
                    break;
            }

            // Load in footer
            $this->load->view("templates/footer");

        } // End of view


        /**
         * dashboard - Generates dashboard which includes:
         *              - Current Week's Timestamps
         *              - Unfinished grading table
         *              - Completed Grading
         *              - Mini-calendar for upcoming assignments
         * @return NULL
         */
        public function dashboard(){

            // TODO: Call on models to get data to send to dashboard

            $this->load->view("simplepages/dashboard");
        }


        public function calendar(){
            // TODO: Generate Full Calendar Page
        }

    } // End of SimplePages class
?>
