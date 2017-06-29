<?php
    class SimplePages extends CI_Controller{

        /**
         * view - Function that directs to pages that contain content for
         *      reading only. By default, directs to the dashboard page
         *
         *      Generates: index, dashboard, calendar, backup, settings
         *
         * @param  string $page name of page to load
         * @return null
         */
        public function view($page='index'){
            // Check if a view exists
            if(!file_exists(APPPATH.'views/simplepages/'.$page.'.php')){
                show_404();
            }

            // Holds vars we want to pass to view
            $data['title'] = ucfirst($page);

            // Loading views
            $this->load->view("templates/header");

            if($page != "index"){
                // Load in side navigation for all pages except for
                // login (index.php) screen
                $this->load->view("templates/nav");
            }

            $this->load->view("simplepages/".$page, $data);
            $this->load->view("templates/footer");


        } // End of view


        public function calendar(){
            // TODO: Generate Full Calendar Page
        }

    } // End of SimplePages class
?>
