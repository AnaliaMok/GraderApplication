<?php

    class Grade_model extends CI_Model{

        public function __construct(){
            $this->load->database();
        }


        /**
         * get_simple_grade - Simple Method to get a grade record based on simple
         *      criteria
         *
         * @param Associative Array criteria - Search fields to search by
         * @return Associative Array containing all found records
         */
        public function get_simple_grade($criteria){
            $query = $this->db->get_where("grades", $criteria);

            return $query->result_array();
        } // End of get_simple_grade


        /**
         * get_all_grades - Retrieves all grades based on the given section_id
         * @param  String $section_id - Sections id to base search on
         * @return Associative Array containing all found records
         */
        public function get_all_grades($section_id){
            // Current gets all grades

            $this->db->select('grades.score, grades.letter_grade, grades.student_id, assignments.name, assignments.due_date,
                students.last_name, students.first_name');
            $this->db->from('grades');

            $this->db->join("students",
                "grades.student_id = students.student_id", "inner");
            $this->db->join("assignments",
                "grades.assignment_id = assignments.assignment_id", "inner");
            $this->db->where('students.section_id', $section_id);

            $query = $this->db->get();

            return $query->result_array();
        } // End of get_all_grades


        /**
         * get_grades - Retrieves a certain set of grade records based on a given
         * array of search criteria
         * @param  Associative Array $criteria - Fields and equivalencies to search
         *                                      by
         * @return Associative Array - The result array of query
         */
        public function get_grades($criteria){

            $this->db->select('grades.score, grades.letter_grade,
                grades.student_id, grades.breakdown, assignments.assignment_id,
                assignments.name, students.section_id, students.student_id');
            $this->db->from('grades');
            $this->db->join("assignments",
                "grades.assignment_id = assignments.assignment_id", "inner");
            $this->db->join("students",
                "grades.student_id = students.student_id", "inner");

            $this->db->where('students.section_id', $criteria['section_id']);

            if(isset($criteria['student_id'])){
                $this->db->where('grades.student_id', $criteria['student_id']);
            }

            if(isset($criteria['assignment_id'])){
                $this->db->where("assignments.assignment_id", $criteria['assignment_id']);
            }

            $query = $this->db->get();

            return $query->result_array();

        } // End of get_grades


        /**
         * grade_to_letter - Helper method to convert a point value
         * to a letter grade
         *
         * @param int point Grade Point Value
         * @return String letter grade equivalent
         */
        private function grade_to_letter($points){

            // $letters = ['A','A-','B+','B','B-','C+','C','C-','D','F'];

            // NOTE: The grade point to letter conversion is not on nice
            // interval so have to deal with this setup for now.
            if($points < 60){ return 'F'; }
            elseif($points >= 60 && $points < 69){ return 'D'; }
            elseif($points >= 69 && $points < 72){ return 'C-'; }
            elseif($points >= 72 && $points < 75){ return 'C'; }
            elseif($points >= 75 && $points < 79){ return 'C+'; }
            elseif($points >= 79 && $points < 82){ return 'B-'; }
            elseif($points >= 82 && $points < 85){ return 'B'; }
            elseif($points >= 85 && $points < 89){ return 'B+'; }
            elseif($points >= 89 && $points < 92){ return 'A-'; }
            elseif($points >= 92){ return 'A'; }

        } // End of grade_to_letter


        /**
         * add_grade - Creates a grade record for a specific student and an
         *      existing assignment
         *
         * @param Associative Array criteria - Data to insert
         *        NOTE: Keys must be exactly the same as table column names
         */
        public function add_grade($criteria){

            // $data = array(
            //     'student_id'    => $criteria['student_id'],
            //     'assignment_id' => $criteria['assignment_id'],
            //     'breakdown'     => $criteria['breakdown']
            // );

            $this->db->insert('grades', $criteria);

        } // End of add_grade


        /**
         * edit_grade - Given an array of new data and a grade_id, updates a
         *      grade record
         *
         * @param Associative Array data - Holds new data to update array with
         * @return NULL
         */
        public function edit_grade($data){

            $grade_search_data = array(
                "student_id"    => $data['student_id'],
                "assignment_id" => $data['assignment_id']
            );

            $grade_id = $this->get_simple_grade($grade_search_data)[0]['grade_id'];

            $letter_grade = $this->grade_to_letter((int)$data['score']);
            $data['letter_grade'] = $letter_grade;

            $this->db->where('grade_id', $grade_id);
            $this->db->update('grades', $data);

        } // End of edit_grade


        /**
         * does_grade_exist - Based on an $assignment_id and $section_id,
         *
         * @param  int $assignment_id - Assignment record id
         * @param  String $section_id - Section record id
         * @return 1 if grade exists; 0 otherwise
         */
        public function does_grade_exist($assignment_id, $section_id){

            $this->db->select("grades.grade_id");
            $this->db->from("grades");

            $this->db->join("assignments", "assignments.assignment_id = grades.assignment_id");
            $this->db->join("students", "students.student_id = grades.student_id");

            $this->db->where(
                "assignments.assignment_id = ".$assignment_id. " AND ".
                "students.section_id = '". $section_id. "'"
            );

            $query = $this->db->get();

            return (count($query->result_array()) >= 1);

        } // End of does_grade_exist

    } // End of Grade_model

?>
