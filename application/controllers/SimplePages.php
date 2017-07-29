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
         * export - Generates the file export page
         * @return NULL
         */
        public function export(){
            // TODO:

            $data['active'] = "export";

            // Loading views
            $this->load->view("templates/header");
            $this->load->view("templates/nav", $data);
            $this->load->view("simplepages/export");
            $this->load->view("templates/footer");
        } // End of export


        /**
         * settings - Generates settings page
         * @return NULL
         */
        public function settings(){
            // TODO

            $data['active'] = "settings";

            // Loading views
            $this->load->view("templates/header");
            $this->load->view("templates/nav", $data);
            $this->load->view("simplepages/settings");
            $this->load->view("templates/footer");
        } // End of settings


        /**
         * timestamps - Generates time table page
         * @return [type] [description]
         */
        public function timestamps(){
            // TODO: Might be moved to its own controller

            $data['active'] = "timestamps";

            // Loading views
            $this->load->view("templates/header");
            $this->load->view("templates/nav", $data);
            $this->load->view("simplepages/timestamps");
            $this->load->view("templates/footer");
        } // End of timestamps


        /**
         * backup - Generates manual database backup page
         * @return NULL
         */
        public function backup(){
            // TODO: Might be moved to its own controller

            $data['active'] = "backup";

            // Loading views
            $this->load->view("templates/header");
            $this->load->view("templates/nav", $data);
            $this->load->view("simplepages/backup");
            $this->load->view("templates/footer");
        } // End of backup

    } // End of SimplePages class
?>
