<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('REQUEST_FALSE', '在请求session时出现错误');
/**
 *  微信相关功能API
 * *  请求openid时，进行数据库用户查询，如果存在此ID则进行ID分配，如果不存在，则生成用户信息
 */
class User extends API_Conotroller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        switch (REQUEST_METHOD) {
            case REQUEST_GET :
                $this->ajax_return(400,MESSAGE_ERROR_PARAMETER);
                break;
            case REQUEST_PUT :
                $this->update();
                break;
            case REQUEST_POST :
                $this->insert();
                break;
        }
    }

    private function insert(){
        //无用功能
//        $openID = $this->input->post('openid');
//        $where['wechat_openid'] = $openID;
//        $re = $this->User_model->get($where);
//        if(!$re){
//            $data = array(
//                'user_name' =>  $this->input->post('user_name'),
//                'user_image' =>  $this->input->post('user_image'),
//                'wechat_openid' => $this->input->post('openid'),
//            );
//            $new_id = $this->User_model->add($data);
//            if (! $new_id)
//            {
//                $this->ajax_return(400,MESSAGE_ERROR_DATA_WRITE);
//            }
//            $token = $this->set_token($openID,$new_id);
//            $this->ajax_return(200,MESSAGE_SUCCESS,$token);
//        }
    }

    private function update(){
        $user_id = $this->uri->segment(2, 0);
        $data = array(
            'user_name' =>  $this->input->input_stream('nickName'),
            'user_image' =>  $this->input->input_stream('avatarUrl'),
            'gender' => $this->input->input_stream('gender'),
            'province' =>  $this->input->input_stream('province'),
            'city' =>  $this->input->input_stream('city'),
            'country' => $this->input->input_stream('country'),
        );
        $re = $this->User_model->update($user_id,$data);
        if (! $re) {
            $this->ajax_return(400,MESSAGE_ERROR_DATA_WRITE);
        }else
            $this->ajax_return(200,MESSAGE_SUCCESS);
    }
}