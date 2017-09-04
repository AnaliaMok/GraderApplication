<?php
    class Export extends CI_Controller{

        // Constant Offset Used for Better Code Formatting in Page Source
        const START_TABS = "\t\t\t\t";

        public function __construct(){
            // CodeIgniter Parent Constructor
            parent::__construct();

            // Loading Models
            $this->load->model('Section_model', 'Sections');
            $this->load->model('Student_model', 'Students');
            $this->load->model('Grade_model', 'Grades');
            $this->load->model('Assignment_model', 'Assignments');
        } // End of __construct


        /**
         * view - Prepares and creates data to be displayed on the export page
         */
        public function view(){

            // Check login
            if(!$this->session->userdata('logged_in')){
                redirect("users/index");
            }

            // Creating section dropdown
            $sections = $this->Sections->get_sections();
            $js = 'class="section-dropdown" id="g-section-dropdown"';

            $options = array();
            $options['all'] = 'All Sections';
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            // Name: sections; Default: 1st Section ID in result array
            // class & id: section-dropdown
            $data['sections'] = form_dropdown("sections", $options, $options['all'], $js);

            // Creating Assignments Dropdown
            $assignments = $this->Assignments->get_all_assignments();
            $js = 'id="g-assignment-dropdown"';

            $a_options = array();
            $a_options['all'] = 'All Assignments';
            foreach($assignments as $assignment){
                $a_options[$assignment['name']] = $assignment['name'];
            }

            // Name: Assignments; Default: 1st Assignment in result array
            // id: assignment-dropdown
            $data['assignments'] = form_dropdown("assignments", $a_options, $a_options['all'], $js);

            // Create Export Table
            $this->generate_table($data, $assignments, $sections);

            // Variable for nav
            $data['active'] = "export";

            // Loading Views to Export Page
            $this->load->view('templates/header');
            $this->load->view('templates/nav', $data);

            // Loading Remaining View & Passing data to dashboard
            $this->load->view('export', $data);
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
         * generate_table - Creates the table that will display all assignments
         *      that can have its comments and/or grades exported.
         *
         * @param  Associative Array $data - Data Array that will pass vars to
         *                           the view.
         * @param Array $assignments - list of all assignments
         * @param Array $sections - List of all sections
         */
        private function generate_table(&$data, $assignments, $sections){

            // Loading Form Helper
            $this->load->helper("form");

            // Defining Heading - Contains empty cell for 'add to queue' btn
            $heading = array("Assignments", "Section", "Grades", "Comments", "");

            // Constructing Table
            $table = '<div class="std-table">'."\n";
            $table .= $this->generate_heading($heading);
            $table .= self::START_TABS.'<div class="table-body">'."\n";

            // For each assignment,
            //      check if a grade records exists with both
            //      its assignment_id and for each section_id
            foreach ($assignments as $assign) {

                $curr_id = $assign['assignment_id'];
                $curr_name = $assign['name'];
                foreach($sections as $sect){

                    if($this->does_grade_exist($curr_id, $sect['section_id'])){
                        // If grade exists, create new row
                        $table .= self::START_TABS."\t<ul>\n";

                        $table .= self::START_TABS."\t\t".'<li data-title="Assignment: ">'.$curr_name."</li>\n";
                        $table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";

                        $table .= self::START_TABS."\t\t".'<li data-title="Section: ">'.$sect['section_id']."</li>\n";
                        $table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";

                        // Checkboxes
                        $table .= self::START_TABS."\t\t".'<li data-title="Grades: ">'
                            .form_checkbox(array('name' => 'grade', 'id' => "grade"))
                            ."</li>\n";
                        $table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";

                        $table .= self::START_TABS."\t\t".'<li data-title="Comments: ">'
                            .form_checkbox(array('name' => 'comments', 'id' => "comments"))
                            ."</li>\n";
                        $table .= self::START_TABS."\t\t".'<li class="empty-cell">'."</li>\n";

                        // Add to Queue Button
                        $table .= self::START_TABS."\t\t".'<li data-title="">'
                            .form_button("add", "Add to Queue",
                                array(
                                    "id" => "add",
                                    "class" => "blue-btn",
                                    "data-assignment" => $curr_name,
                                    "data-section" => $sect['section_id'],
                                    "value" => "[]"
                                ))
                            ."</li>\n";

                        // Closing ul tag
                        $table .= self::START_TABS."\t</ul>"."<!-- End of row -->\n";
                    }

                } // End of inner loop

            } // End of outer loop


            // Closing Tags
            $table .= self::START_TABS."</div><!-- End of table-body -->\n";
            $table .= "\t\t\t</div><!-- End of std-table -->\n";

            $data['table'] = $table;
        } // End of generate_table


        /**
         * does_grade_exist - Calls on correponsing Grade method to query for
         *      grade records with the given assignment id and students with the
         *      given section_id.
         * @param  int $assignment_id
         * @param  String $section_id
         * @return 1 is grade exist; 0 otherwise
         */
        private function does_grade_exist($assignment_id, $section_id){
            return $this->Grades->does_grade_exist($assignment_id, $section_id);
        } // End of does_grade_exist


        /**
         * create_textfile - Generates the text file of a given sections grade
         *      breakdowns
         * @param String section_id - Section for given grades
         * @param Associative Array assignment - Assignment Record
         * @param Array grades - All grades for the given section & assignment
         * @return String - Name of newly created file
         */
        private function create_textfile($section_id, $assignment, $grades){

            $this->load->helper("file");

            // Create file
            $file = "output/assignment-".$section_id.".txt";

            // Create header for file
            $border = str_repeat("=", 50)."\n";
            $content = "";
            $content .= $border;
            $content .= "Assignment:\t".$assignment['name']."\n";
            $content .= "Section:\t".$section_id."\n";
            $content .= $border;

            // Foreach grade, find the student's name, loop through grade breakdown
            // and write to file
            $assignment_breakdown = json_decode($assignment['breakdown']);
            //echo print_r($assignment_breakdown);
            for($i = 0, $length = count($grades); $i < $length; $i++){
                $curr_grade = $grades[$i];
                $graded_breakdown = json_decode($curr_grade['breakdown']);
                //die(print_r($graded_breakdown));
                //Find Student
                $student = $this->Students->get_students(array("student_id" => $curr_grade['student_id']))[0];
                $name = "\n".$student['last_name'].", ".$student['first_name']."\n";

                $content .= $name;
                $content .= "Score:\t".$curr_grade['score']."\n";
                $content .= "Breakdown:\t\n\n";

                // Process Breakdown & Add to Content
                foreach($graded_breakdown as $key => $value){

                    $content .= $key.": ";

                    // Loop through subcategories
                    foreach($value as $sub_key => $sub_value){
                        if($sub_key === "Total"){
                            // Write Total points on current line
                            $content .= $sub_value."/".($assignment_breakdown->$key->Total)."\n";
                        }else{
                            // Otherwise, write newline with beginning tab
                            $content .= "\t".$sub_key.": "
                                .$sub_value."/".($assignment_breakdown->$key->Total)."\n";
                        }
                    }

                } // End of graded_breakdown loop

                // Writing Comments
                $content .= "\nComments:\n";
                // Stripping tags but replacing ending p tags with a newline char first
                $comment = strip_tags(str_replace("</p>", "\n", $curr_grade['comments']))."\n\n";
                // Replacing characters codes inside comment
                $comment = str_replace("&#39;", "'", $comment);
                $comment = str_replace("&nbsp;", " ", $comment);
                $comment = str_replace("&quot;", "\"", $comment);
                $content .= $comment;

            }

            // Write Content to file
            write_file($file, $content, "w+");

            // Returning name of newly created file
            return $file;

        } // End of create_textfile


        /**
         * create_files - Generates all requested files and downloads all in a
         *      zip folder. Redirects back to export page
         * @return [type] [description]
         */
        public function create_files(){
            // Loading helpers and libraries for creating files
            $this->load->library("zip");

            $download_queue = json_decode($this->input->post("download_queue"));
            $files = array();

            for($i = 0, $length = count($download_queue); $i < $length; $i++){
                $curr_request = json_decode($download_queue[$i]);
                $section_id = $curr_request->section;

                // Getting assignment record
                $assignment = $this->Assignments->get_simple_assignment(array("name" => $curr_request->assignment))[0];

                // Getting Grades
                $grades = $this->Grades->get_grades(array(
                    "section_id"    => $section_id,
                    'assignment_id' => $assignment['assignment_id']
                ));

                // TODO: Create Files based on type
                if($curr_request->type === "grade"){
                    // TODO: Create spreadsheet
                }elseif($curr_request->type === "comments"){
                    $new_file = $this->create_textfile($section_id, $assignment, $grades);
                    array_push($files, $new_file);
                }else{
                    // TODO: Error
                }
            }

            // Create zip folder
            foreach($files as $file){
                // NOTE: Can't do echos because of call to download
                $this->zip->read_file($file);
            }

            $zip_name = "grades_n_comments.zip";
            $this->zip->archive(FCPATH."/".$zip_name);
            $this->zip->download("grades_n_comments.zip");

            // TODO: Set Flashdata

        } // End of generate_files

    } // End of class
?>
