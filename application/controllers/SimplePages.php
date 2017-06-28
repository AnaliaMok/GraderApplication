<?php
    class SimplePages extends CI_Controller{

        /**
         * view - Function that directs to pages that contain content for
         *      reading only. By default, directs to the dashboard page
         *
         * @param  string $page [description]
         * @return [type]       [description]
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
    }


?>
