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
         * @param $all Boolean - Get all assignments or just incompleted items?
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

            if($all){
                // if all is true, get all assignments from this month, not just
                // incomplete items
                $query = $this->db->get_where("assignments", "started IS NOT NULL");
            }else{
                // Otherwise, get this month's incomplete items
                $query = $this->db->get_where("assignments", "is_completed=$all");
            }

            return $query->result_array();
        } // End of get_assignments


        public function get_all_assignments($section_id){
            // TODO
        } // End of get_all_assignments


        public function create_assignment(){
            // TODO
        }


        public function edit_assignment($assignment_id, $data=NULL, $is_completed=0){

            // $data = array(
            //     "name"          =>
            //     "due_date"      =>
            //     "total_pts"     =>
            //     "breakdown"     =>
            //     "started"       =>
            //     "is_completed"  =>
            // );
            if($data == NULL){
                // If no data given, then this is a simple request to change the
                // is_completed field
                $data = array("is_completed" => $is_completed);
                //$this->db->set("is_completed", $is_completed, FALSE);
            }

            $this->db->where("assignment_id", (int)$assignment_id);
            return $this->db->update("assignments", $data);

        } // End of edit_assignment


        public function delete_assignment(){
            // TODO
        }

    }
?>
