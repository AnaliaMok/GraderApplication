<?php
    class Assignment_model extends CI_Model{

        public function __construct(){
            // Load Database
            $this->load->database();
        }


        /**
         * get_assignments - Returns a month's worth of assignments
         *
         * @param curr_month Date
         * @param curr_year Date
         * @param fields Array of Strings representing fields/columns in
         *                     Assignment table
         * @param $all Boolean - Get all assignments are just incomplete?
         * @return Result Array
         */
        public function get_assignments($curr_month, $curr_year, $fields, $all=0){
            $first_day_of_mth = date("Y-m-d", strtotime($curr_year."-".$curr_month."-01"));
            $date = new DateTime();
            $last_day_of_mth = date("Y-m-d", strtotime($curr_year."-".$curr_month."-".$date->format("t")));

            $this->db->select($fields);
            $this->db->group_start()
                     ->where("due_date >=", $first_day_of_mth)
                     ->group_start()
                        ->where("due_date <=", $last_day_of_mth)
                     ->group_end()
                    ->group_end();

            $query = $this->db->get_where("assignments", "is_completed=$all");
            return $query->result_array();
        } // End of get_assignments


        public function create_assignment(){
            // TODO
        }


        public function edit_assignment(){
            // TODO
        }


        public function delete_assignment(){
            // TODO
        }

    }
?>
