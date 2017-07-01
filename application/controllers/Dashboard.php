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

            $data = array();
            $data = $this->create_time_table_two($data);

            // TODO: Create unfinished grading table
            // TODO: Create Finished Grading Table

            // Loading Remaining View & Passing data to dashboard
            $this->load->view('dashboard', $data);
            $this->load->view('templates/footer');
        } // End of view


        /**
         * [create_table_template description]
         * @return [type] [description]
         */
        private function create_table_template(){
            $template = array(
                'table_open'            => '<div class="std-table">',

                'thead_open'            => '<div class="table-head">',
                'thead_close'           => '</div>',

                'heading_row_start'     => '<ul>',
                'heading_row_end'       => '</ul>',
                'heading_cell_start'    => '<li>',
                'heading_cell_end'      => '</li>',

                'tbody_open'            => '<div class="table-body">',
                'tbody_close'           => '</div>',

                'row_start'             => '<ul>',
                'row_end'               => '</ul>',
                'cell_start'            => '<li>',
                'cell_end'              => '</li>',

                'row_alt_start'         => '<ul>',
                'row_alt_end'           => '</ul>',
                'cell_alt_start'        => '<li>',
                'cell_alt_end'          => '</li>',

                'table_close'           => '</div>'
            );

            return $template;
        } // End of create_table_template


        /**
         * create_time_table - Method that asks Timestamps_model for current
         *      week's timestamps, formats data, and places formatted data
         *      in data variable
         *
         * @param data - Associative array holding all data that will be sent to
         *               the dashboard view
         * @return data array
         */
        private function create_time_table($data){

            // Timestamp data
            $times = $this->Timestamp->get_timestamps();
            $this->table->set_heading('Date', 'Start Time', 'End Time', 'Assignment', 'Section');

            // Total Time Logged
            $data['total_time'] = DateTime::createFromFormat('H:i:s', "00:00:00");

            // Empty List Item to place in between inline item groups
            $empty_cell = array('data' => '&nbsp;', 'class' => 'empty-cell');

            // Creating & Setting Table Template
            $template = $this->create_table_template();
            $this->table->set_template($template);

            $table_data = array();

            // Creating table rows & formatting date and times
            foreach ($times as $time){
                $formattedDate = nice_date($time['date'], 'm.d.y');
                $formattedStart = date("g:i A", strtotime($time['start']));
                $formattedEnd = date("g:i A", strtotime($time['end']));

                // TODO: Calculate timespan between start to end & add to total_time var
                $startTime = DateTime::createFromFormat('H:i:s', $time['start']);
                $endTime = DateTime::createFromFormat('H:i:s', $time['end']);

                $interval = new DateInterval('PT'.$startTime->format('h').'H'.$startTime->format('i').'M');
                $endTime->sub($interval);

                // End time as an interval
                $interval = new DateInterval('PT'.$endTime->format('h').'H'.$endTime->format('i').'M');
                $data['total_time']->add($interval);

                // All cells of a given row
                $date_cell = array(
                    'data' => $formattedDate,
                    'data-title' => 'Date:');

                $start_time_cell = array(
                    'data' => $formattedStart,
                    'class' => 'inline-item',
                    'data-title' => 'Time:');

                $end_time_cell = array(
                    'data' => $formattedEnd,
                    'class' => 'inline-item',
                    'data-title' => '-');

                $name_cell = array(
                    'data' => $time['name'],
                    'class' => 'inline-item',
                    'data-title' => 'Assignment');

                $section_cell = array(
                    'data' => $time['section_id'],
                    'class' => 'unbold-title-item inline-item',
                    'data-title' => 'for');

                // Add cells
                // $this->table->add_row($date_cell, $empty_cell, $start_time_cell,
                //     $end_time_cell, $empty_cell, $name_cell, $section_cell);
                $table_data[] = array($date_cell, $empty_cell, $start_time_cell, $end_time_cell,
                    $empty_cell, $name_cell, $section_cell);
            }

            // Formatting Total Time
            $data['total_time'] = $data['total_time']->format('h'). "hrs ". $data['total_time']->format('i'). "min";

            // Placing Generated Table inside data variable
            $data['time_table'] = $this->table->generate($table_data);

            // Clearing table data
            $this->table->clear();

            return $data;
        } // End of create_time_table


        private function create_time_table_two($data){

            // Starting with an empty string
            $data['time_table'] = "";

            // Total Time Logged
            $data['total_time'] = DateTime::createFromFormat('H:i:s', "00:00:00");

            $startTabs = "\t\t\t\t";

            // Creating table opening
            $data['time_table'] .= '<div class="std-table">'."\n";
            $data['time_table'] .= $startTabs.'<div class="table-head">'."\n";

            // Opening ul tag
            $data['time_table'] .= $startTabs."\t".'<ul>'."\n";

            // Adding Header Items
            $heading = array("Date", "Start Time", "End Time", "Assignment", "Section");

            foreach ($heading as $head) {
                $data['time_table'] .= $startTabs."\t\t<li>".$head."</li>\n";
            }

            // Closing ul & table-head div tag
            $data['time_table'] .= $startTabs."\t</ul>\n";
            $data['time_table'] .= $startTabs."</div>\n";

            // Starting table-body div
            $data['time_table'] .= $startTabs.'<div class="table-body">'."\n";

            // Timestamp Data
            $times = $this->Timestamp->get_timestamps();

            // Defining an empty-cell
            $empty_cell = '<li class="empty-cell"></li>'."\n";

            // Creating table rows & formatting date and times
            foreach ($times as $time){
                $formattedDate = nice_date($time['date'], 'm.d.y');
                $formattedStart = date("g:i A", strtotime($time['start']));
                $formattedEnd = date("g:i A", strtotime($time['end']));

                // TODO: Calculate timespan between start to end & add to total_time var
                $startTime = DateTime::createFromFormat('H:i:s', $time['start']);
                $endTime = DateTime::createFromFormat('H:i:s', $time['end']);

                $interval = new DateInterval('PT'.$startTime->format('h').'H'.$startTime->format('i').'M');
                $endTime->sub($interval);

                // End time as an interval
                $interval = new DateInterval('PT'.$endTime->format('h').'H'.$endTime->format('i').'M');
                $data['total_time']->add($interval);

                // Adding New Row
                $data['time_table'] .= $startTabs."\t<ul>\n";
                $data['time_table'] .= $startTabs."\t\t".'<li data-title="Date:">'.$formattedDate."</li>\n";
                $data['time_table'] .= $startTabs."\t\t".$empty_cell."\n";
                $data['time_table'] .= $startTabs."\t\t".'<li data-title="Time:" class="inline-item">'.$formattedStart."</li>\n";
                $data['time_table'] .= $startTabs."\t\t".'<li data-title="-" class="inline-item">'.$formattedEnd."</li>\n";
                $data['time_table'] .= $startTabs."\t\t".$empty_cell."\n";
                $data['time_table'] .= $startTabs."\t\t".'<li data-title="Assignment" class="inline-item">'.$time['name']."</li>\n";
                $data['time_table'] .= $startTabs."\t\t".'<li data-title="for" class="unbold-title-item inline-item">'.$time['section_id']."</li>\n";
                $data['time_table'] .= $startTabs."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $data['time_table'] .= $startTabs."</div><!-- End of table-body -->\n";
            $data['time_table'] .= "\t\t\t</div><!-- End of std-table -->\n";

            // Formatting Total Time
            $data['total_time'] = $data['total_time']->format('h'). "hrs ". $data['total_time']->format('i'). "min";

            return $data;
        } // End of create_time_table_two


        public function test(){
            $this->load->view("templates/header");
            $this->load->view("templates/nav");
            $this->load->view('test');
            $this->load->view("templates/footer");
        }

    } // End of Dashboard
?>
