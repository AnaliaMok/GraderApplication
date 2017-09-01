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
            $js = 'class="section-dropdown" id="section-dropdown"';

            $options = array();
            foreach($sections as $sect){
                $options[$sect['section_id']] = $sect['section_id'];
            }

            // Name: sections; Default: 1st Section ID in result array
            // class & id: section-dropdown
            $data['sections'] = form_dropdown("sections", $options, $sections[0]['section_id'], $js);

            // Creating Assignments Dropdown
            $assignments = $this->Assignments->get_all_assignments();
            $js = 'id="assignment-dropdown"';

            $a_options = array();
            foreach($assignments as $assignment){
                $a_options[$assignment['name']] = $assignment['name'];
            }

            // Name: Assignments; Default: 1st Assignment in result array
            // id: assignment-dropdown
            $data['assignments'] = form_dropdown("assignments", $a_options, $assignments[0]['name'], $js);

            // Create Export Table
            $this->generate_table($data);

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
         */
        private function generate_table(&$data){

            // Defining Heading - Contains empty cell for 'add to queue' btn
            $heading = array("Assignment", "Section", "Grades", "Comments", "Both", "");

            // Constructing Table
            $table = '<div class="std-table">'."\n";
            $table .= $this->generate_heading($heading);
            $table .= self::START_TABS.'<div class="table-body">'."\n";

            // Closing Tags
            $table .= self::START_TABS."</div><!-- End of table-body -->\n";
            $table .= "\t\t\t</div><!-- End of std-table -->\n";

            $data['table'] = $table;
        } // End of generate_table


        /**
         * create_files - Generates all requested files and downloads all in a
         *      zip folder. Redirects back to export page
         * @return [type] [description]
         */
        public function create_files(){
            // TODO
        } // End of generate_files

    } // End of class
?>
