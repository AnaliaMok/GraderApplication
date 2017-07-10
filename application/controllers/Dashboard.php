<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Dashboard - Controller to handle all interactions with the dashboard
     * page.
     */
    class Dashboard extends CI_Controller{

        // Constant Offset Used for Better Code Formatting in Page Source
        const START_TABS = "\t\t\t\t";


        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // All SimplePages use a database connection
            $this->load->database();

            // Loading Libraries
            $this->load->library('table');

            // Loading Calendar Library w/ Preferences
            $prefs = array(
                'day_type'          => 'short',
                'show_other_days'   => TRUE
            );

            $prefs['template'] = '
                {table_open}<table id="mini-calendar">{/table_open}
                {heading_row_start}<thead>{/heading_row_start}
                {heading_row_end}</thead>{/heading_row_end}

                {week_row_start}<tr class="weekdays">{/week_row_start}
                {week_row_end}</tr>{/week_row_end}

                {cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}
                {cal_cell_start_today}<td id="today">{/cal_cell_start_today}

                {cal_cell_content}{day}{content}{/cal_cell_content}

            ';

            $this->load->library('calendar', $prefs);

            // Loading My Models
            $this->load->model('Timestamp_model', 'Timestamp');
            $this->load->model('Assignment_model', 'Assignments');

        } // End of __construct


        /**
         * view - Method to generate the entire dashboard page
         *      Asks Assignment, and Timestamp models for data
         * @return NULL
         */
        public function view(){

            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            // Current year & month variables
            $this_year = date("Y", strtotime("this year"));
            $this_month = date("m", strtotime("this month"));

            // Constructing data
            $data = array();

            // Info Tables
            $data = $this->create_time_table($data);

            // Passing Assignments
            $fields = array("assignment_id", "name", "due_date", "started",
                "completed", "is_completed");
            $assignments = $this->Assignments->get_assignments($this_month, $this_year, $fields, 1);

            $complete = array();
            $incomplete = array();

            // Sorting $assignments
            foreach($assignments as $assign){
                if($assign['is_completed'] == 0){
                    $incomplete[] = $assign;
                }else{
                    $complete[] = $assign;
                }
            }

            // Assembling tables
            $this->create_unfinished_table($data, $incomplete);
            $this->create_finished_table($data, $complete);

            // Calendar
            $data['calendar'] = $this->calendar->generate($this_year, $this_month);

            // Variable for nav
            $data['active'] = "dashboard";

            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);

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
         * generate_heading - Accepts an array of strings and formats the table-head
         *      header to be used.
         *
         * @param  headers - Array of Strings
         * @return String
         */
        private function generate_heading($headers){
            // Formatted table-head String
            $out = "";

            // Creating table-head
            $out .= self::START_TABS.'<div class="table-head">'."\n";
            $out .= self::START_TABS."\t<ul>\n";

            // Adding list items
            foreach($headers as $head){
                $out .= "<li>".$head."</li>";
            }

            // Closing tags
            $out .= self::START_TABS."\t</ul>\n";
            $out .= self::START_TABS."</div>\n";

            return $out;
        } // End of generate_heading


        /**
         * create_time_table - Finds and formats the time table
         *
         * @param  data - Reference to an associative array
         * @return data
         */
        private function create_time_table(&$data){

            // Starting with an empty string
            $data['time_table'] = "";

            // Total Time Logged
            $data['total_time'] = DateTime::createFromFormat('H:i:s', "00:00:00");

            // Header Items
            $heading = array("Date", "Start Time", "End Time", "Assignment", "Section");

            // Creating table opening
            $data['time_table'] .= '<div class="std-table">'."\n";
            $data['time_table'] .= $this->generate_heading($heading);

            // Starting table-body div
            $data['time_table'] .= self::START_TABS.'<div class="table-body">'."\n";

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
                $data['time_table'] .= self::START_TABS."\t<ul>\n";
                $data['time_table'] .= self::START_TABS."\t\t".'<li data-title="Date:">'.$formattedDate."</li>\n";
                $data['time_table'] .= self::START_TABS."\t\t".$empty_cell."\n";
                $data['time_table'] .= self::START_TABS."\t\t".'<li data-title="Time:" class="inline-item">'.$formattedStart."</li>\n";
                $data['time_table'] .= self::START_TABS."\t\t".'<li data-title="-" class="inline-item">'.$formattedEnd."</li>\n";
                $data['time_table'] .= self::START_TABS."\t\t".$empty_cell."\n";
                $data['time_table'] .= self::START_TABS."\t\t".'<li data-title="Assignment:" class="inline-item">'.$time['name']."</li>\n";
                $data['time_table'] .= self::START_TABS."\t\t".'<li data-title="for" class="unbold-title-item inline-item">'.$time['section_id']."</li>\n";
                $data['time_table'] .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $data['time_table'] .= self::START_TABS."</div><!-- End of table-body -->\n";
            $data['time_table'] .= "\t\t\t</div><!-- End of std-table -->\n";

            // Formatting Total Time
            if(count($times) > 0){
                $data['total_time'] = $data['total_time']->format('h'). "hrs ". $data['total_time']->format('i'). "min";
            }else{
                $data['total_time'] = 0;
            }

            return $data;
        } // End of create_time_table


        /**
         * [create_unfinished_table description]
         * @param  Array data - Reference to an associative array
         * @param Array assignments - Array of assignment objects from db
         * @return [type]       [description]
         */
        private function create_unfinished_table(&$data, $assignments){
            $data['unfinished_table'] = '<div class="std-table">'."\n";

            $heading = array("Assignment", "Started", "");
            $data['unfinished_table'] .= $this->generate_heading($heading);

            // Opening table-body
            $data['unfinished_table'] .= self::START_TABS.'<div class="table-body">'."\n";

            foreach($assignments as $assign){

                $formattedDate = nice_date($assign['started'], 'm.d.y');

                $data['unfinished_table'] .= self::START_TABS."\t<ul>\n";
                $data['unfinished_table'] .= self::START_TABS."\t\t".'<li>'.$assign['name']."</li>\n";

                $data['unfinished_table'] .= self::START_TABS."\t\t".'<li class="date">'.$formattedDate."</li>\n";
                $data['unfinished_table'] .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";
                // TODO: Add links to assignments
                $data['unfinished_table'] .= self::START_TABS."\t\t".'<li>'.
                    '<a href="#" class="lnr lnr-printer"></a>'.'<a href="#" class="lnr lnr-pencil"></a>'."</li>\n";
                $data['unfinished_table'] .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $data['unfinished_table'] .= self::START_TABS."</div><!-- End of table-body -->\n";
            $data['unfinished_table'] .= "\t\t\t</div><!-- End of std-table -->\n";

            return $data;
        } // End of create_unfinished_table


        /**
         * [create_finished_table description]
         * @param  &Array data - Reference to an associative array
         * @param Array assignments - Array of assignment objects from db
         * @return [type]       [description]
         */
        private function create_finished_table(&$data, $assignments){
            $data['finished_table'] = '<div class="std-table">'."\n";

            $heading = array("Assignment", "Completed", "");
            $data['finished_table'] .= $this->generate_heading($heading);

            // Opening table-body
            $data['finished_table'] .= self::START_TABS.'<div class="table-body">'."\n";

            foreach($assignments as $assign){

                $formattedDate = nice_date($assign['completed'], 'm.d.y');

                $data['finished_table'] .= self::START_TABS."\t<ul>\n";
                $data['finished_table'] .= self::START_TABS."\t\t".'<li>'.$assign['name']."</li>\n";

                $data['finished_table'] .= self::START_TABS."\t\t".'<li class="date">'.$formattedDate."</li>\n";
                $data['finished_table'] .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";
                // TODO: Add links to assignments
                $data['finished_table'] .= self::START_TABS."\t\t".'<li>'.
                    '<a href="#" class="lnr lnr-printer"></a>'.'<a href="#" class="lnr lnr-pencil"></a>'."</li>\n";
                $data['finished_table'] .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $data['finished_table'] .= self::START_TABS."</div><!-- End of table-body -->\n";
            $data['finished_table'] .= "\t\t\t</div><!-- End of std-table -->\n";
        } // End of create_finished_table

    } // End of Dashboard
?>
