<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($message_id,$where)
    {
        $columns = 'message_id,company_name,company_city,message_content,message_datetime,message_status,count,surname';
        $this->db->select($columns);
        $this->db->from('ili_message');
        $this->db->join('ili_user','ili_user.user_id = ili_message.user_id');
        $this->db->where('message_id',$message_id);
        $this->db->where($where);
        $res = $this->db->get()->row();
        return $res;
    }

    public function get_list($where=array(),$limit = 10, $currentPage, & $total = null)
    {
        $columns = 'message_id,company_name,company_city,message_content,message_datetime,message_status,count,surname';
        $start = ($currentPage - 1) * $limit;
        $keywords = isset($where['keywords']) ? $where['keywords'] : '';

        if (! is_null($total)) {
            $this->db->from('ili_message');
            if (! empty($keywords)) {
                $this->db->like('company_name', $keywords);
            }
            $total = $this->db->count_all_results();
        }
        $this->db->select($columns);
        $this->db->from('ili_message');
        if (! empty($keywords)) {
            $this->db->like('company_name', $keywords);
        }
        $this->db->join('ili_user','ili_user.user_id = ili_message.user_id','left');
        $this->db->where('message_status=1');
        $this->db->order_by('count DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function get_top_n($limit = 10,$order = 'count DESC')
    {
        $columns = 'message_id,company_name,company_city,message_content,message_datetime,message_status,count,surname';
        $start = 0;

        $this->db->select($columns);
        $this->db->from('ili_message');
        $this->db->join('ili_user','ili_user.user_id = ili_message.user_id','left');
        $this->db->where('message_status=1');
        $this->db->order_by($order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function delete($topic_id)
    {
        $this->db->where('message_id', $topic_id);
        $this->db->delete('ili_message');
        return $this->db->affected_rows();
    }

    public function add($data)
    {
        $this->db->insert('ili_message', $data);
        return $this->db->insert_id();
    }

    public function add_unique($company_name){
        $this->db->select('message_id');
        $this->db->from('ili_message');
        $this->db->where($company_name);
        $res = $this->db->get()->row();
        return $res;
    }

    public function put_up($message_id,$set)
    {
        $this->db->where('message_id',$message_id);
        $this->db->set('count',$set,FALSE);
        $this->db->update('ili_message');
        return $this->db->affected_rows();
    }
}