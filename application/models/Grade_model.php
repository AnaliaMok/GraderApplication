<?php

    class Grade_model extends CI_Model{

        public function __construct(){
            $this->load->database();
        }


        public function get_grades($section_id){
            // Current gets all grades

            $this->db->select('grades.score, grades.letter_grade, assignments.name,
                students.last_name, students.first_name');
            $this->db->from('grades');

            $this->db->join("students",
                "grades.student_id = students.student_id", "inner");
            $this->db->join("assignments",
                "grades.assignment_id = assignments.assignment_id", "inner");
            $this->db->where('students.section_id', $section_id);

            $query = $this->db->get();

            return $query->result_array();
        } // End of get_grades


        /**
         * [add_grade description]
         */
        public function add_grade(){
            // TODO
        }


        /**
         * [edit_grade description]
         * @return [type] [description]
         */
        public function edit_grade(){
            // TODO
        } // End of edit_grade

    } // End of Grade_model

?>
