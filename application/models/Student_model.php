<?php
    class Student_model extends CI_Model{

        public function __construct(){
            // Load Database
            $this->load->database();
        }


        public function get_students(){
            // TODO
        }


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

            echo print_r($data);

            // Check if record already exists first
            $this->db->select("*");
            $this->db->where($data);
            $query = $this->db->get("students");

            if($query != NULL){
                $this->db->insert("students", $data);
                return TRUE;
            }

            return FALSE;
        } // End of create_student


        public function edit_student(){
            // TODO
        }


        public function delete_student(){
            // TODO
        }

    }

?>
