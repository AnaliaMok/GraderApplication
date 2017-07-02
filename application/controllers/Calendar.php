<?php

    class Calendar extends CI_Controller{

        public function __construct(){
            parent::__construct();
            //Codeigniter : Write Less Do More

            // Loading Calendar Library w/ Preferences
            $prefs = array(
                'day_type'          => 'short',
                'show_other_days'   => TRUE
            );

            $prefs['template'] = '
                {table_open}<table class="calendar">{/table_open}
                {heading_row_start}<thead>{/heading_row_start}
                {heading_row_end}</thead>{/heading_row_end}

                {week_row_start}<tr class="weekdays">{/week_row_start}
                {week_row_end}</tr>{/week_row_end}

                {cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}
                {cal_cell_start_today}<td id="today">{/cal_cell_start_today}

            ';
            // TODO: Turn templates back on after styling
            $this->load->library('calendar', $prefs);
        }


        /**
         * Main View Generator for Calendar
         * @return [type] [description]
         */
        public function view(){
            // TODO

            // $events = array(
            //     3 => "Hello World"
            // );

            $this_year = date("Y", strtotime("this year"));
            $this_month = date("m", strtotime("this month"));
            $data['calendar'] = $this->calendar->generate($this_year, $this_month);
            $data['active'] = "calendar";

            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);
            $this->load->view('calendar', $data);
            $this->load->view('templates/footer');
        }

  } // End of Calendar Class

?>
