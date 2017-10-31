<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 该类下包含三个暴漏的接口：话题，图片上传，留言
 * 入口函数为 index，topic_image_upload，comment
 */

class Topic extends API_Conotroller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('Topic_model');
        switch (REQUEST_METHOD) {
            case REQUEST_GET :
                $topic_id = $this->uri->segment(2, 0);
                if (is_numeric($topic_id)) {
                    $this->topic_info($topic_id);
                    break;
                } else {
                    $this->topic_list();
                    break;
                }
            case REQUEST_DELETE :
                $topic_id = $this->uri->segment(2, 0);
                $this->topic_delete($topic_id);
                break;
            case REQUEST_PUT :
                $topic_id = $this->uri->segment(2, 0);
                $type = $this->uri->segment(3, 0);
                $this->topic_update($topic_id,$type);
                break;
            case REQUEST_POST :
                $this->topic_add();
                break;
        }
    }

    protected function topic_list()
    {
        $page = intval($this->uri->segment(3, 0));
        $limit = 5;
        $total = 0;

        if (empty($page)) {
            $page = 1;
        }
        $list = array();
        $list['data'] = $this->Topic_model->get_list($limit, $page, $total);
        $list['total'] = $total;
        $list['current_page'] = $page;
        $list['total_page'] = ceil($total / $limit);
        $this->ajax_return(200, MESSAGE_SUCCESS, $list);
    }

    protected function topic_info($topic_id)
    {
        $data = $this->Topic_model->get_topic($topic_id);
        $this->ajax_return(200, MESSAGE_SUCCESS, $data);
    }


    protected function topic_delete($topic_id)
    {
        $res = $this->Topic_model->delete($topic_id);
        if($res < 0) {
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE);
        }
        $this->ajax_return(200, MESSAGE_SUCCESS);
    }

    protected function topic_update($topic_id,$type)
    {
        $check = $this->Topic_model->get_topic_up($topic_id,$this->USER_ID);
        $up = 'up';
        $res = 0;
        if($type == $up){
            //点赞行为,必须是不存在点赞记录
            if(!$check){
                $data = 'topic_up + 1';
                $this->Topic_model->add_topic_up($topic_id,$this->USER_ID);
                $res = $this->Topic_model->put_up($topic_id,$data);
            }else $res = 1;
        }else{
            //取消赞行为,必须是存在点赞记录
            if($check){
                $data = 'topic_up - 1';
                $this->Topic_model->delete_topic_up($topic_id,$this->USER_ID);
                $res = $this->Topic_model->put_up($topic_id,$data);
            }else $res = 1;
        }

        if($res > 0){
            $this->ajax_return(200, MESSAGE_SUCCESS);
        }else
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE);
    }

    protected function topic_add(){
        $str_images = $this->input->input_stream('topic_images');
        $images = explode(',',$str_images);
        $data=[];
        foreach($images as $key => $img){
            $tempPar = 'topic_image_'.($key+1);
            $data[$tempPar] = $img;
        }
        $data['user_id'] = $this->USER_ID;
        $data['topic_content'] = $this->input->input_stream('topic_content');
        $data['topic_date'] = date('Y-m-d H:i:s');
        $res = $this->Topic_model->add($data);
        if($res > 0){
            $this->ajax_return(200, MESSAGE_SUCCESS,$res);
        }else
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE);
    }

    //话题图片上传
    public function topic_image_upload()
    {
        if(REQUEST_METHOD != REQUEST_POST)  $this->ajax_return(400,MESSAGE_ERROR_REQUEST_TYPE);

        $file_dir_f = '/upload/topic/';
        $file_dir = '.'.$file_dir_f;
        if(! file_exists($file_dir))
        {
            mkdir($file_dir,0777,true);
        }

        $config['upload_path'] = $file_dir;
        $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
        $config['file_name'] = uniqid();
        $config['max_size'] = 2048;
        $config['overwrite'] = true;
        $this->load->library('upload',$config);
        $res = $this->upload->do_upload('imageData');

        if(! $res)
        {
            $this->ajax_return(400,$this->upload->error_msg[0]);
        }
        $data = $this->upload->data();
        $photo = KKD_DOMAIN.$file_dir_f.$data['file_name'];
        $this->ajax_return(200, MESSAGE_SUCCESS,$photo);
    }

    public function comment()
    {
        $this->load->model('Comment_model');
        switch (REQUEST_METHOD) {
            case REQUEST_GET :
                $topic_id = $this->uri->segment(2, 0);
                $this->comment_list($topic_id);
                break;
            case REQUEST_DELETE :
                break;
            case REQUEST_PUT :
                $comment_id = $this->uri->segment(2, 0);
                $type = $this->uri->segment(3, 0);
                $this->comment_update($comment_id,$type);
                break;
            case REQUEST_POST :
                $topic_id = $this->uri->segment(2, 0);
                $this->comment_add($topic_id);
                break;
        }
    }

    private function comment_list($topic_id){
        $list = $this->Comment_model->get_all($topic_id);
        $this->ajax_return(200, MESSAGE_SUCCESS, $list);
    }

    private function comment_update($comment_id,$type)
    {
        $up = 'up';
        $res = 0;
        if($type == $up){
            $data = 'comment_up + 1';
            $res = $this->Comment_model->put_up($comment_id,$data);
            $this->Comment_model->add_comment_up($comment_id,$this->USER_ID);
        }else{
            $data = 'comment_up - 1';
            $res = $this->Comment_model->put_up($comment_id,$data);
            $this->Comment_model->delete_comment_up($comment_id,$this->USER_ID);
        }
        if($res > 0){
            $this->ajax_return(200, MESSAGE_SUCCESS);
        }else
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE);
    }

    private  function comment_add($topic_id){
        $data=[];
        $data['topic_id'] = $topic_id;
        $data['user_id'] = $this->USER_ID;
        $data['comment_content'] = $this->input->input_stream('comment_content');
        $data['comment_up'] = 0;
        $data['comment_datetime'] = date('Y-m-d H:i:s');
        $res = $this->Comment_model->add($data);

        //获取留言用户名称
        $this->load->model('User_model');
        $where['user_id'] = $this->USER_ID;
        $resU = $this->User_model->get($where);
        $user_name = $resU['user_name'];

        $returnData['comment_id'] = $res;
        $returnData['user_name'] = $user_name;
        $this->ajax_return(200, MESSAGE_SUCCESS,$returnData);
    }
}
