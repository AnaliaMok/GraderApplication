<?php
    /**
     * Gradebook - Controller for All Functionalities in the Gradebook view
     */
    class Gradebook extends CI_Controller{

        // Constant Offset Used for Better Code Formatting in Page Source
        const START_TABS = "\t\t\t\t";

        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // All SimplePages use a database connection
            $this->load->database();

            // Loading Models
            $this->load->model('Section_model', 'Sections');
            $this->load->model('Student_model', 'Students');
            $this->load->model('Grade_model', 'Grades');
            $this->load->model('Assignment_model', 'Assignments');

        } // End of __construct


        /**
         * view - Setup for main/default view for the gradebooks
         *
         * @return null
         */
        public function view(){
            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            // Gathering Data

            // Creating section dropdown
            $data['sections'] = "<select>\n";

            $sections = $this->Sections->get_sections();

            foreach($sections as $sect){
                $data['sections'] .=
                    '<option value="'.$sect['section_id'].'">'.$sect['section_id']."</option>\n";
            }

            $data['sections'] .= "</select>\n";

            // Create main grade tables
            $this->generate_grade_table($data, $sections[0]['section_id']);

            $data['active'] = "gradebook";

            // Loading Views
            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);
            $this->load->view('gradebook/index', $data);
            $this->load->view('templates/footer');
        } // End of view


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
         * generate_grade_table - Creates and formats gradebook data
         *
         * @return [type] [description]
         */
        public function generate_grade_table(&$data, $section_id){

            // Grabbing Records
            $grades = $this->Grades->get_grades($section_id);

            $heading = array("Last, First");
            $students = array();

            // Organizing grades to students
            foreach ($grades as $grade) {

                $assignment = "".$grade['name'];

                if(in_array($assignment, $heading) === FALSE){
                    // If grade name has not been added yet, add to heading
                    array_push($heading, $assignment);
                }

                $student_name = $grade['last_name'] . ", " . $grade['first_name'];
                $score = $grade['score']."&nbsp;(".$grade['letter_grade'].")";

                // Adding assignment-score pair to student's values
                $students[$student_name][$assignment] = $score;
            }

            // Constructing Table
            $grade_table = '<div class="std-table">'."\n";
            $grade_table .= $this->generate_heading($heading);
            $grade_table .= self::START_TABS.'<div class="table-body">'."\n";

            // Forming Rows
            foreach ($students as $student => $assigns) {
                $grade_table .= self::START_TABS."\t<ul>\n";
                $grade_table .= self::START_TABS."\t\t".'<li>'.$student."</li>\n";

                foreach($assigns as $name => $score){
                    // TODO: wrap in anchor tag to open edit grade modal
                    $grade_table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";
                    $grade_table .= self::START_TABS."\t\t".'<li data-title="'.$name.':">'.$score."</li>\n";
                }

                $grade_table .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $grade_table .= self::START_TABS."</div><!-- End of table-body -->\n";
            $grade_table .= "\t\t\t</div><!-- End of std-table -->\n";

            $data['grade_table'] = $grade_table;

        } // End of generate_grade_table


        public function add_student(){
            // TODO


            $data['active'] = "gradebook";

            // Loading Views
            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);
            $this->load->view('gradebook/new_student', $data);
            $this->load->view('templates/footer');
        }


        public function add_grade(){
            // TODO
        }

    } // End of Gradebook Class
?>
