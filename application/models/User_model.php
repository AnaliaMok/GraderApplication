<?php

    class User_model extends CI_Model{

        public function __construct(){

            parent::__construct();

            // Load Database
            $this->load->database();
        }


        public function register($enc_password){
            // Data Array
            $data = array(
                'name'          =>  $this->input->post('name'),
                'username'      =>  $this->input->post('username'),
                'password'      =>  $enc_password
            );

            // Insert user
            return $this->db->insert('logins', $data);
        }


        public function check_username_exists($username){
            $query = $this->db->get_where('logins', array("username" => $username));
            if(empty($query->row_array())){
                return true;
            }else{
                return false;
            }
        } // End of check_username_exists

    } // End of User_model


?>
