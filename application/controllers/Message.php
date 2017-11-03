<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 该类下包含三个暴漏的接口：话题，图片上传，留言
 * 入口函数为 index，topic_image_upload，comment
 */

class Message extends API_Conotroller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        switch (REQUEST_METHOD) {
            case REQUEST_GET :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_DELETE :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_PUT :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_POST :
                $this->message_add();
                break;
        }
    }

    public function message_add(){
        $data=[];
        $data['company_name'] = $this->input->post('company_name');

        //不能重复公司名
        $this->load->model('Message_model');
        $res = $this->Message_model->add_unique($data);
        if($res){
            $this->ajax_return(300, MESSAGE_ERROR_COMPANY_UNIQUE);
        }

        $data['user_id'] = $this->USER_ID;
        $data['company_city'] = $this->input->post('company_city');
        $data['message_content'] = $this->input->post('message_content');
        $data['message_status'] = 1;
        $data['message_datetime'] = date('Y-m-d H:i:s');

        $res = $this->Message_model->add($data);
        if($res){
            $this->ajax_return(200, MESSAGE_SUCCESS);
        }
        else{
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE,$this->Message_model->last_sql());
        }
    }

    public function comment()
    {

        switch (REQUEST_METHOD) {
            case REQUEST_GET :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_DELETE :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_PUT :
                $this->ajax_return(400, MESSAGE_ERROR_REQUEST_TYPE);
                break;
            case REQUEST_POST :
                $message_id = $this->uri->segment(2, 0);
                $this->comment_add($message_id);
                break;
        }
    }

    private  function comment_add($message_id){
        //用户只能留言一条
        $this->load->model('Comment_model');
        $whereMessage['message_id'] = $message_id;
        $whereMessage['user_id'] = $this->USER_ID;
        $res = $this->Comment_model->get($whereMessage);
        if($res) $this->ajax_return(400, MESSAGE_ERROR_COMMENT_UNQIUE);

        $this->load->model('User_model');
        $where['user_id'] = $this->USER_ID;
        $resU = $this->User_model->get($where);
        if(!$resU) $this->ajax_return(400, MESSAGE_ERROR_WARNING_AUTH);

        $data=[];
        $data['message_id'] = $message_id;
        $data['user_id'] = $this->USER_ID;
        $data['user_surname'] = $resU['surname'];
        $data['user_sex'] = $resU['sex'];
        $data['comment_content'] = $this->input->post('comment_content');
        $data['comment_datetime'] = date('Y-m-d H:i:s');

        $res = $this->Comment_model->add($data);

        $responseData['user_surname'] = $resU['surname'];
        $responseData['user_sex'] = $resU['sex'];
        if($res){
            $this->load->model('Message_model');
            $comSql = 'count + 1';
            $res = $this->Message_model->put_up($message_id,$comSql);
            $this->ajax_return(200, MESSAGE_SUCCESS,$responseData);
        }
        else{
            $this->ajax_return(400, MESSAGE_ERROR_DATA_WRITE);
        }
    }
}
