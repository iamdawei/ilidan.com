<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($message_id)
    {
        $cols = 'comment_id,user_surname,user_sex,comment_content';

        $this->db->select($cols);
        $this->db->from('ili_comments');
        $this->db->where('message_id', $message_id);
        $this->db->order_by('comment_id ASC');
        return $this->db->get()->result();
    }

    public function get_list($limit = 10, $page, & $total = null)
    {
        $cols = 'tt_topic.topic_id,topic_content,topic_image_1 as topic_image,topic_up,IFNULL(tempFrom.comment_count,0) as comment_count,tt_comments_up.user_id as up_user_id';
        $start = ($page - 1) * $limit;

        if (! is_null($total)) {
            $this->db->from('tt_topic');
            $total = $this->db->count_all_results();
        }

        $this->db->select($cols);
        $this->db->from('tt_topic');
        $this->db->join('( select topic_id,count(*) as comment_count FROM tt_comments GROUP BY topic_id ) as tempFrom','tt_topic.topic_id = tempFrom.topic_id','left');
        $this->db->join('tt_comments_up','tt_topic.topic_id = tt_comments_up.topic_id','left');
        $this->db->order_by('topic_id DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function delete($topic_id)
    {
    }

    public  function add($data)
    {
        $this->db->insert('ili_comments', $data);
        return $this->db->insert_id();
    }

    public function put_up($topic_id,$set)
    {
        $this->db->where('comment_id',$topic_id);
        $this->db->set('comment_up',$set,FALSE);
        $this->db->update('ili_comments');
        return $this->db->affected_rows();
    }

    public function add_comment_up($comment_id,$user_id)
    {
        $data['comment_id'] = $comment_id;
        $data['user_id'] = $user_id;
        $this->db->insert('tt_comments_up', $data);
        return $this->db->insert_id();
    }

    public function delete_comment_up($comment_id,$user_id)
    {
        $where['comment_id'] = $comment_id;
        $where['user_id'] = $user_id;
        $this->db->where($where);
        $this->db->delete('tt_comments_up');
        return $this->db->affected_rows();
    }
}