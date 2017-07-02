<?php

    class Timestamp_model extends CI_Model{

        public function __construct(){
            // Load Database
            $this->load->database();
        }


        /**
         * get_timestamps - Queries for all timestamps within a given range
         * TODO: Add start & end parameters
         * @return Array of time stamps
         */
        public function get_timestamps($start = NULL, $end = NULL){

            // Grabbing all columns in timestamps except for id
            // Order by decreasing date then by decreasing end time
            // Inner join by assignment_id

            $this->db->select('date, start, end, assignments.name, section_id');
            $this->db->join('assignments', 'assignments.assignment_id=timestamps.assignment_id');

            // Date Range: This Sunday to This Saturday
            if($start == NULL && $end == NULL){
                $start = date("Y-m-d", strtotime("This Sunday"));
                $end = date("Y-m-d", strtotime("This Saturday"));
            }

            // WHERE Clause
            $this->db->group_start()
                        ->where("date >=", $start)
                        ->group_start()
                            ->where("date <=", $end)
                        ->group_end()
                    ->group_end();

            $this->db->order_by('timestamps.date', 'DESC');
            $this->db->order_by('timestamps.end', 'DESC');
            $query = $this->db->get("timestamps");
            
            return $query->result_array();
        }


        public function create_timestamp(){
            // TODO
        }


        public function edit_timestamp(){
            // TODO
        }

        public function delete_timestamp(){
            // TODO
        }

    }

?>
