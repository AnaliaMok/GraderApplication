<?php
    class Student_model extends CI_Model{

        public function __construct(){
            // Load Database
            $this->load->database();
        }


        /**
         * get_students - Given a set of criteria, looks for a SET of students
         *      from db.
         *
         * @param Associative Array criteria: Fields to base select on
         * @return Array - Student records
         */
        public function get_students($criteria){

            $this->db->where($criteria);

            // Alphabetize student last names
            $this->db->order_by("last_name", "INC");

            $query = $this->db->get("students");
            return $query->result_array();

        } // End of get_students


        /**
         * create_student - Adds a new student record to students
         *      table
         *
         * @param  int $form_num Form Number to grab fields from
         * @return TRUE on success, FALSE otherwise
         */
        public function create_student($form_num){

            $data = array(
                "first_name"    =>  $this->input->post("first_name_".$form_num),
                "last_name"     =>  $this->input->post("last_name_".$form_num),
                "section_id"    =>  $this->input->post("sections_".$form_num)
            );

            // Check if record already exists first
            $query = $this->db->get_where("students", $data);

            if(empty($query->row_array())){
                $this->db->insert("students", $data);
                return TRUE;
            }

            return FALSE;
        } // End of create_student


        /**
         * edit_student - Updates a student record
         * @return TRUE on success; FALSE otherwise
         */
        public function edit_student($data, $student_id){

            $this->db->where("student_id", $student_id);
            $this->db->update("students", $data);
            return TRUE;

        } // End of edit_student


        public function delete_student(){
            // TODO
        }

    }

?>
