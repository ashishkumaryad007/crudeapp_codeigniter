<?php
    class Auth_model extends CI_Model{

        // this function will save the user record in the databse
        public function create($formArray){
            $this->db->insert('Ashish_User1',$formArray);// Insert into Ashish_User1(name,email) values (?,?);
        }
        // this method will return a row array based on emailed entered.
        public function checkUser($email){

            $this->db->where('email',$email);
            return $row=$this->db->get('Ashish_User1')->row_array();
        }
        ////////////////////////////////////
        function getUser($userId){
            $this->db->where("id",$userId);
            return $user= $this->db->get("training.Ashish_User1")->row_array();
        }
        function updateUser($userId,$formArray){
            $this->db->where('id',$userId);
            $this->db->update("training.Ashish_User1",$formArray); 
        }
        function deleteUser($userId){
            $this->db->where('id',$userId);
            $this->db->delete("training.Ashish_User1"); 
        }/////////////////////////////////
        function all(){
            $query=$this->db->get("training.Ashish_User1"); // this query fetch data from table diectly
            return $query->result();
        }
        // check user authorization
        function authorized(){
            $user=$this->session->userdata('user');
            if(!empty($user)){
                return true;
            }else{
                return false;
            }
        }
    }
?>