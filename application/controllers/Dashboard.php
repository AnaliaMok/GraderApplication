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

            // Loading My Models
            $this->load->model('Timestamp_model', 'Timestamp');

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

            // Timestamp data
            $times = $this->Timestamp->get_timestamps();
            $this->table->set_heading('Date', 'Start Time', 'End Time', 'Assignment', 'Section');

            // Total Time Logged
            $data['total_time'] = time();

            // Creating table rows & formatting date and times
            foreach ($times as $time){
                $formattedDate = nice_date($time['date'], 'm.d.y');
                $formattedStart = date("g:i A", strtotime($time['start']));
                $formattedEnd = date("g:i A", strtotime($time['end']));

                // TODO: Calculate timespan between start to end & add to total_time var

                $this->table->add_row($formattedDate, $formattedStart,
                    $formattedEnd, $time['name'], $time['section_id']);
            }

            // Changing the opening table tag for this table
            $template = array('table_open' => '<table class="std_table">');
            $this->table->set_template($template);

            $data['time_table'] = $this->table->generate();

            // Loading Remaining View & Passing data to dashboard
            $this->load->view('dashboard', $data);
            $this->load->view('templates/footer');
        } // End of view

    } // End of Dashboard
?>
