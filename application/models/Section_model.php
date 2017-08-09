<?php

    class Section_model extends CI_Model{

        public function __construct(){
            // Load Database
            $this->load->database();
        }


        /**
         * get_section: Retrieves all section ids available
         * @return Array - All section ids
         */
        public function get_sections(){
            // Getting all sections ORDERED BY date created
            $this->db->select("section_id");
            $this->db->order_by("created", "DESC");
            $query = $this->db->get("sections");

            return $query->result_array();
        } // End of get_sections


        public function create_section(){
            // TODO
        }


        public function edit_section(){
            // TODO
        }


        public function delete_section(){
            // TODO
        }

    }

?>
