<?php

class User_model extends CI_model{

    public function __construct()
    {
    parent::__construct();
    }

    public function insert($data)
    {
        $this->db->insert('ili_user', $data);
        return $this->db->insert_id();
    }
    public function delete($student_id)
    {
        $this->db->where('student_id',$student_id);
        $this->db->delete('ili_user');
    }
    public function update($user_id,$data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('ili_user',$data);
        return $this->db->affected_rows();
    }
    public function get($where)
    {
        $cols = 'user_id,user_account,surname,name,industry,post,phone,sex';
        $this->db->select($cols);
        $this->db->from('ili_user');
        $this->db->where($where);
        $res = $this->db->get()->row_array();
        return $res;
    }
}



