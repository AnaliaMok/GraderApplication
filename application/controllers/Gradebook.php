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

            // Creating section dropdown
            $sections = $this->Sections->get_sections();
            $js = 'class="section-dropdown" id="section-dropdown"';

            $options = array();
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            $data['sections'] = form_dropdown("sections_0", $options, $sections[0]['section_id'], $js);
            $data['modal_sections'] = form_dropdown("sections", $options, $sections[0]['section_id'], $js);
            $data['selected'] = $sections[0]['section_id'];

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
         * @param data - Data Array that will be passed to the view
         * @param section_id - String, Represents currently selected section id
         * @return [type] [description]
         */
        public function generate_grade_table(&$data, $section_id){

            // Grabbing Students & Grades
            $student_records = $this->Students->get_students(array("section_id" => $section_id));
            //$grades = $this->Grades->get_all_grades($section_id);

            $heading = array("Last, First");
            $assignments = array();
            $students = array();

            // Organizing Grades
            foreach($student_records as $student){

                // Format Student Name
                $student_name = $student['last_name'].", ".$student['first_name'];
                // Place student's student id with associated array
                $students[$student_name]['student_id'] = $student['student_id'];

                // SQL Select Criteria
                $criteria = array(
                    'student_id'    => $student['student_id'],
                    'section_id'    => $section_id
                );

                // Find grade(s)
                $grades = $this->Grades->get_grades($criteria);
                foreach($grades as $grade){
                    $assign_name = $grade['name'];

                    if(in_array($assign_name, $heading) === FALSE){
                        // If grade name has not been added yet, add to heading
                        // and assignments array
                        array_push($heading, $assign_name);
                        // array_push($assignments, $assign_name);
                        $assignments[$assign_name]['breakdown'] = $grade['breakdown'];
                        $assignments[$assign_name]['assignment_id'] = $grade['assignment_id'];
                    }

                    // TODO: Wrap in anchor tag or add js event
                    // If score is not set, just use a dash
                    $score = ($grade['score'] == NULL) ? "-" : $grade['score']."&nbsp;(".$grade['letter_grade'].")";
                    $students[$student_name][$assign_name] = $score;

                }

            }

            // Constructing Table
            $grade_table = '<div class="std-table">'."\n";
            $grade_table .= $this->generate_heading($heading);
            $grade_table .= self::START_TABS.'<div class="table-body">'."\n";


            // Forming Rows
            foreach ($students as $name => $student) {
                $grade_table .= self::START_TABS."\t<ul>\n";
                $grade_table .= self::START_TABS."\t\t".'<li onclick="openStudentModal(this);">'.$name."</li>\n";

                // Iterate through array of found
                // assignments; if present in students array, add; otherwise
                // create a new grade and then create new "cells"
                foreach($assignments as $name => $assign){

                    // TODO: wrap in anchor tag to open edit grade modal
                    $grade_table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";

                    if($student[$name] === NULL){
                        // If student doesn't have an entry for current
                        // assignment, create new grade entry for this student
                        $new_grade = array(
                            'student_id'    => $student['student_id'],
                            'assignment_id' => $assignments[$name]['assignment_id'],
                            'breakdown'     => $assignments[$name]['breakdown']
                        );

                        $this->Grades->add_grade($new_grade);

                        // Then assign empty grade holder
                        $student[$name] = "-";

                    }

                    // Create score cell with data-title = assignment name &
                    // onclick event to open grade modal
                    $grade_table .= self::START_TABS."\t\t".
                        '<li data-title="'.$name.':" onclick="openGradeModal(this);">'
                        .$student[$name]."</li>\n";
                }

                $grade_table .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
            }

            // Closing Tags
            $grade_table .= self::START_TABS."</div><!-- End of table-body -->\n";
            $grade_table .= "\t\t\t</div><!-- End of std-table -->\n";

            $data['grade_table'] = $grade_table;

        } // End of generate_grade_table


        /**
         * change_grade_table - Target of AJAX request made when user changes
         *      the selected class section. Uses generate_grade_table to generate
         *      grade table and echos the newly formed table
         *
         * @return null
         */
        public function change_grade_table(){

            // Grabbing data
            $section_id = $_POST['section_id'];
            $data = array();

            $this->generate_grade_table($data, $section_id);

            // Send back grade table only
            echo $data['grade_table'];

        } // End of change_grade_table

        /**
         * add_student - Given a set of student forms, asks student model to
         *      add new students to database
         */
        public function add_student(){
            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            $sections = $this->Sections->get_sections();
            $js = 'class="section-dropdown"';

            $options = array();
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            $data['sections'] = form_dropdown("sections_0", $options, $sections[0]['section_id'], $js);

            $data['active'] = "gradebook";

            $total_forms = intval($this->input->post("total_forms"));

            // Form Rules
            if($total_forms != null && $total_forms > 1){
                // Set rules for total_forms forms if there is more than 1 form

                for($i = 0; $i < $total_forms; $i++){
                    $this->form_validation->set_rules('first_name_'.$i, 'First Name', 'required');
                    $this->form_validation->set_rules('last_name_'.$i, 'Last Name', 'required');
                    $this->form_validation->set_rules('sections_'.$i, 'Section ID', 'required');
                }

            }else{
                // If first time visiting this page, just set rules for 1 form
                $this->form_validation->set_rules('first_name_0', 'First Name', 'required');
                $this->form_validation->set_rules('last_name_0', 'Last Name', 'required');
                $this->form_validation->set_rules('sections_0', 'Section ID', 'required');
            }

            if($this->form_validation->run() === FALSE){
                // Loading Views
                $this->load->view('templates/header');
                $this->load->view('templates/nav', $data);
                $this->load->view('gradebook/new_student', $data);
                $this->load->view('templates/footer');
            }else{
                // Submit student data
                $students_added = "";
                $students_existed = "";

                for($i = 0; $i < $total_forms; $i++){

                    $student = "<li>";
                    $student .= $this->input->post("first_name_".$i);
                    $student .= "&nbsp;";
                    $student .= $this->input->post("last_name_".$i);
                    $student .= "&nbsp;";
                    $student .= $this->input->post("sections_".$i);
                    $student .= "</li>";

                    if($this->Students->create_student($i) === FALSE){
                        $students_existed .= $student . "\n";
                    }else{
                        $students_added .= $student . "\n";
                    }

                }

                // Flash data to list all students that were added or existed
                if($students_existed != ""){
                    $students_existed = "<p>One or more student(s) already exist</p>\n<ul>\n".$students_existed;
                    $students_existed .= "</ul>\n";
                    $this->session->set_flashdata("student_exists", $students_existed);
                }

                if($students_added != ""){
                    $students_added = "<p>Students successfully added:</p>\n<ul>\n".$students_added;
                    $students_added .= "</ul>\n";
                    $this->session->set_flashdata("students_added", $students_added);
                }


                // Loading Views
                $this->load->view('templates/header');
                $this->load->view('templates/nav', $data);
                $this->load->view('gradebook/new_student', $data);
                $this->load->view('templates/footer');
            }

        } // End of add_student


        /**
         * set_more_rules - Target of add_student AJAX request. Sets the
         *      validation rules for a new set of form inputs.
         */
        public function set_more_rules(){

            // Check login state
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            // Pre-Incremented total form value
            // NOTE: Forms are 0-based indexed
            $total_forms = $_POST['total_forms'];

            // Create sections dropdown
            $sections = $this->Sections->get_sections();
            $js = 'class="section-dropdown"';

            $options = array();
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            $dropdown_name = "sections_".$total_forms;
            $section_dropdown = form_dropdown($dropdown_name, $options, $sections[0]['section_id'], $js);

            // Set new validation rules
            $this->form_validation->set_rules("first_name_".$total_forms, "First Name", "required");
            $this->form_validation->set_rules('last_name_'.$total_forms, 'Last Name', 'required');
            $this->form_validation->set_rules('sections_'.$total_forms, 'Section ID', 'required');

            // Create new form group
            $new_form = '<div class="student-info-group" id="form_'.$total_forms.'">'."\n";

            $first_name = "first_name_".$total_forms;
            $new_form .= "<div>\n<label>First Name</label>\n";
            $new_form .= '<input type="text" name="'.$first_name.'" id="'.$first_name.'"/>'."\n";
            $new_form .= "</div>\n";

            $last_name = "last_name_".$total_forms;
            $new_form .= "<div>\n<label>Last Name</label>\n";
            $new_form .= '<input type="text" name="'.$last_name.'" id="'.$last_name.'" />'."\n";
            $new_form .= "</div>\n";

            $new_form .= "<div>\n<label>Section ID</label>\n";
            $new_form .= $section_dropdown;
            $new_form .= "</div>\n";

            // Closing tag for student-info-group
            $new_form .= "</div>\n";

            echo $new_form;
        } // End of set_more_rules


        /**
         * add_assignment - Directs to New Assignment form, sets up
         *      validation rules and if form is valid, attempts to create a
         *      new assignment record. Assignment records will also create new
         *      Grade records that will have a NULL score.
         */
        public function add_assignment(){
            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            // Active Nav Item
            $data['active'] = "gradebook";

            // Creating section dropdown
            $sections = $this->Sections->get_sections();
            $js = 'class="section-dropdown"';

            $options = array();
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            $data['section_dropdown'] = form_dropdown("section_dropdown", $options, $sections[0]['section_id'], $js);

            // Form Validation Rules
            $this->form_validation->set_rules("assign_name", "Name", "required");
            $this->form_validation->set_rules("due_date", "Due Date", "required|callback_is_date_format");
            $this->form_validation->set_message("is_date_format", "Please Enter a Date With the Proper Format: mm/dd/yyyy");
            $this->form_validation->set_rules("total_points", "Total Pts.", "required");
            $this->form_validation->set_rules("sections", "Section(s)", "required");
            $this->form_validation->set_rules("category", "Categories", "required");

            if($this->form_validation->run() === FALSE){
                // Loading Views
                $this->load->view('templates/header');
                $this->load->view('templates/nav', $data);
                $this->load->view('gradebook/new_assignment', $data);
                $this->load->view('templates/footer');
            }else{
                // Create new assignment record
                $assign_id = $this->Assignments->create_assignment();

                // Convert Section String to Array
                $sections = json_decode($this->input->post("sections"), true);

                foreach($sections as $curr){
                    $criteria = array('section_id' => $curr );
                    $students = $this->Students->get_students($criteria);

                    // Create grade record for each student in each
                    // selected section. Keep breakdown as empty string
                    foreach($students as $student){

                        $criteria = array(
                            'student_id'    => $student['student_id'],
                            'assignment_id' => $assign_id,
                            'breakdown'     => $this->input->post("category")
                        );

                        $this->Grades->add_grade($criteria);
                    }
                }


                // Create flashdata message
                $this->session->set_flashdata("assignment_created",
                    "Assignment Successfully Created");

                redirect("gradebook");
            }

        } // End of add_assignment


        /**
         * is_date_format - Checks to see if given date is in the proper date
         * format
         *
         * @param String due_date
         * @return boolean true if valid; false otherwise
         */
        public function is_date_format($due_date){

            // Attempty to create a date
            $date = DateTime::createFromFormat("m/d/Y", $due_date);

            return $date != FALSE;

        } // End of due_date


        /**
         * get_grade_breakdown - Target of AJAX request to retrieve grade
         *          breakdown
         *
         * @return JSON Object
         */
        public function get_grade_breakdown(){
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $section_id = $_POST['section_id'];
            $assignment_name = $_POST['assignment_name'];

            $assignment = $this->Assignments->get_simple_assignment(array("name" => $assignment_name));
            $student = $this->Students->get_students(array(
                "first_name"    => $first_name,
                "last_name"     => $last_name,
                "section_id"    => $section_id
            ));

            $grade = $this->Grades->get_simple_grade(array(
                "student_id"        => $student[0]['student_id'],
                "assignment_id"     => $assignment[0]['assignment_id']
            ));

            if(count($grade) > 0){
                echo json_encode($grade);
            }else{
                echo "Nothing found";
            }

        } // End of get_grade_breakdown


        /**
         * add_grade: TODO
         */
        public function add_grade(){
            // TODO

            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

        } // End of add_grade


        /**
         * find_student_id - Given JSON data past through POST variable,
         *     searches for student record and returns the id associated with
         *     that record.
         *
         * @return int student_id
         */
        public function find_student_id(){
            $criteria = array(
                'first_name'    => $_POST['first_name'],
                'last_name'     => $_POST['last_name'],
                'section_id'    => $_POST['section_id']
            );

            $student = $this->Students->get_students($criteria);
            if(count($student) === 1){
                echo $student[0]['student_id'];
            }else{
                return NULL;
            }

        } // End of find_student_id


        /**
         * edit_student - Updates a given student's information
         */
        public function edit_student(){

            // Set Rules
            $this->form_validation->set_rules("first_name", "First Name", "required");
            $this->form_validation->set_rules("last_name", "Last Name", "required");
            $this->form_validation->set_rules("sections", "Sections", "required");
            $this->form_validation->set_rules("student_id", "Student ID", "required");

            // Set Messages
            // $this->form_validation->set_message("first_name", "First Name Field is Required");
            // $this->form_validation->set_message("last_name", "Last Name Field is Required");
            // $this->form_validation->set_message("sections", "Section Field is Required");
            // $this->form_validation->set_message("student_id", "Student ID Field is Required");

            if($this->form_validation->run() !== FALSE){

                // Format associative array for edit_student model method
                $data = array(
                    "first_name"    => $this->input->post("first_name"),
                    "last_name"     => $this->input->post("last_name"),
                    "section_id"    => $this->input->post("sections")
                );

                $this->Students->edit_student($data, $this->input->post("student_id"));
            }

            // Redirect back to gradebook regardless
            redirect("gradebook");

        } // End of edit_student

    } // End of Gradebook Class
?>
