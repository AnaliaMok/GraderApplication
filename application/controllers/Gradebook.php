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

        } // End of __construct

    } // End of Gradebook Class
?>
