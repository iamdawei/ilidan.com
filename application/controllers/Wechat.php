<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('REQUEST_FALSE', '在请求session时出现错误');
/**
 *  微信相关功能API
 * *  请求openid时，进行数据库用户查询，如果存在此ID则进行ID分配，如果不存在，则生成用户信息
 */
class Wechat extends API_Conotroller
{
    function __construct()
    {
        parent::__construct();
    }

    public function openid(){
        $appid = 'wx47cdc42df11b9903';
        $secret = 'f88018a24ff624977ec58518fbc1e06c';
        $jscode = $this->input->get('jscode');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$jscode}&grant_type=authorization_code";

        $result = $this->curl_request($url);

        if($result === false) $this->ajax_return(400, REQUEST_FALSE);
        else{
            $weUser = json_decode($result);
            $user_id = $this->saveUserInfo($weUser->openid);
            $token = $this->set_token($weUser->openid,$user_id);
            $weUser->token = $token;
            $weUser->user_id = $user_id;
            $this->ajax_return(200, MESSAGE_SUCCESS,$weUser);
        }
    }

    private function saveUserInfo($openID){
        $this->load->model('User_model');
        //查询openid是否存在
        $where['wechat_openid'] = $openID;
        $re = $this->User_model->get($where);
        $user_id = 0;
        if(!$re){
            //不存在，则新增
            $data['wechat_openid'] = $openID;
            $this->User_model->insert($data);
            $user_id = $this->db->insert_id();
        }else
            $user_id = $re['user_id'];
        return $user_id;
    }
}