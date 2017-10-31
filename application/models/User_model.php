<?php

class User_model extends CI_model{

    public function __construct()
    {
    parent::__construct();
    }

    public function insert($data)
    {
        $this->db->insert('tt_user', $data);
        return $this->db->insert_id();
    }
    public function delete($student_id)
    {
        $this->db->where('student_id',$student_id);
        $this->db->delete('tt_user');
    }
    public function update($user_id,$data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('tt_user',$data);
        return $this->db->affected_rows();
    }
    public function get($where)
    {
        $cols = 'user_id,user_name,user_image,wechat_openid';
        $this->db->select($cols);
        $this->db->from('tt_user');
        $this->db->where($where);
        $res = $this->db->get()->row_array();
        return $res;
    }
}



