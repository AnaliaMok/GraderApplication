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
            // TODO: Create form and when validation runs, execute backup

            // Hidden field giving the ok to create backup
            $this->form_validation->set_rules("check", "check", "required");

            $data['active'] = "backup";

            if($this->form_validation->run() === FALSE){
                // Loading views normally
                $this->load->view("templates/header");
                $this->load->view("templates/nav", $data);
                $this->load->view("simplepages/backup");
                $this->load->view("templates/footer");
            }else{
                // Else, execute backup procedure
                // Using example code in CodeIgniter documentation

                // Loading the DB utility class
                $this->load->dbutil();

                // Backup your entire database and assign it to a variable
                $backup = $this->dbutil->backup();

                // Load the file helper and write the file to your server
                $this->load->helper('file');
                if(write_file('output/backup.zip', $backup, "w+")){
                    echo "File Written";
                }

                // Load the download helper and send the file to your desktop
                $this->load->helper('download');
                ob_end_clean();
                force_download('mybackup.zip', $backup);

                // $this->load->library("zip");
                // $this->zip->read_file('output/backup.zip');
                //
                // $zip_name = "grades_n_comments.zip";
                // $this->zip->archive(FCPATH."/".$zip_name);
                // ob_end_clean();
                // $this->zip->download("backup.zip");

                // TODO: Redirect
            }


        } // End of backup


        public function execute_backup(){
            // Loading the DB utility class
            $this->load->dbutil();

            // Backup your entire database and assign it to a variable
            $backup = $this->dbutil->backup();

            // Load the file helper and write the file to your server
            $this->load->helper('file');
            write_file('/path/to/mybackup.gz', $backup);

            // Load the download helper and send the file to your desktop
            $this->load->helper('download');
            force_download('mybackup.gz', $backup);
        }

    } // End of SimplePages class
?>
