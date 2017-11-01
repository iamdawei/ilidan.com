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

    public function get_top_n($limit = 10)
    {
        $columns = 'message_id,company_name,company_city,message_content,message_datetime,message_status,count,surname';
        $start = 0;

        $this->db->select($columns);
        $this->db->from('ili_message');
        $this->db->join('ili_user','ili_user.user_id = ili_message.user_id','left');
        $this->db->where('message_status=1');
        $this->db->order_by('count DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function delete($topic_id)
    {
        $this->db->where('topic_id', $topic_id);
        $this->db->delete('tt_topic');
        return $this->db->affected_rows();
    }

    public  function add($topic_array)
    {
        $this->db->insert('tt_topic', $topic_array);
        return $this->db->insert_id();
    }

    public function put_up($message_id,$set)
    {
        $this->db->where('message_id',$message_id);
        $this->db->set('count',$set,FALSE);
        $this->db->update('ili_message');
        return $this->db->affected_rows();
    }

    public function get_topic_up($topic_id,$user_id)
    {
        $where['topic_id'] = $topic_id;
        $where['user_id'] = $user_id;
        $this->db->select('topic_id,user_id');
        $this->db->from('tt_topic_up');
        $this->db->where($where);
        return $this->db->get()->row();
    }

    public function add_topic_up($topic_id,$user_id)
    {
        $data['topic_id'] = $topic_id;
        $data['user_id'] = $user_id;
        $this->db->insert('tt_topic_up', $data);
        return $this->db->insert_id();
    }

    public function delete_topic_up($topic_id,$user_id)
    {
        $where['topic_id'] = $topic_id;
        $where['user_id'] = $user_id;
        $this->db->where($where);
        $this->db->delete('tt_topic_up');
        return $this->db->affected_rows();
    }
}