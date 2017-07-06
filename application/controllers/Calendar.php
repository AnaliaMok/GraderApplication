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

                {cal_cell_content}{day}{content}{/cal_cell_content}

            ';
            // TODO: Turn templates back on after styling
            $this->load->library('calendar', $prefs);

            // Loading Models
            $this->load->model('Assignment_model', 'Assignment');
        }


        /**
         * Main View Generator for Calendar
         * @return [type] [description]
         */
        public function view(){
            // Current year & month variables
            $this_year = date("Y", strtotime("this year"));
            $this_month = date("m", strtotime("this month"));

            // Getting Upcoming Assignments for this Month
            $fields = array('assignment_id', 'name', 'due_date');
            $assignments = $this->Assignment->get_assignments($this_month, $this_year, $fields);
            $data['upcoming'] = $assignments;

            // Generating Calendar
            $events = array();
            for($i = 0, $length = count($assignments); $i < $length; $i++){
                // Current Assignment
                $curr = $assignments[$i];

                // Getting day of month from assignment due date
                $due_date = date("j", strtotime($curr['due_date']));
                if(!isset($events[$due_date])){
                    // Initialize day as a new array containing the assignment
                    // name
                    $events[$due_date] = '<span class="events" id="event'
                        .$curr['assignment_id'].'">'.$curr['name']."</span>";
                }else{
                    // If already set, just push new name
                    $events[$due_date] .= '<span class="events" id="event'
                        .$curr['assignment_id'].'">'.$curr['name']."</span>";
                }
            }

            $data['calendar'] = $this->calendar->generate($this_year, $this_month, $events);
            $data['active'] = "calendar";

            // Loading Views
            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);
            $this->load->view('calendar', $data);
            $this->load->view('templates/footer');
        }


        public function find_upcoming(){
            // Current year & month variables
            $this_year = date("Y", strtotime("this year"));
            $this_month = date("m", strtotime("this month"));

            // Getting Upcoming Assignments for this Month
            $fields = array('assignment_id', 'name', 'due_date');
            $assignments = $this->Assignment->get_assignments($this_month, $this_year, $fields);
            // $data['upcoming'] = $assignments;
            // echo json_encode($assignments);

            for($i = 0, $length=count($assignments); $i < $length; $i++){
                $curr_assignment = $assignments[$i];
                echo '<div class="upcoming-list-item">'."\n";
                // TODO: Calculate time between now and due_date assign class
                // to indicator based on difference
                echo '<div class="info-holder">'."\n";
                echo '<span class="date">Due&nbsp;'.nice_date($curr_assignment['due_date'], 'm.d')."</span>";
                echo "<span>".$curr_assignment['name']."</span>\n";
                echo "</div>\n";
                echo '<input type="checkbox" onclick="return completedAssignment('.$curr_assignment['assignment_id'].')"/>';
                echo "</div>\n";
            }

        } // End of find_upcoming


        /**
         * completeAssignment - Given an assignmentID, accept the sent AJAX
         * request and change the is_completed state of the Assignment
         *
         * @return [type]               [description]
         */
        public function complete_assignment(){
            // Sending assignmentID to model method
            $this->Assignment->edit_assignment($_POST['assignmentID'], NULL, 1);

            // Re-generating upcoming-list-items
            $this->find_upcoming();

        } // End of completeAssignment

  } // End of Calendar Class

?>
