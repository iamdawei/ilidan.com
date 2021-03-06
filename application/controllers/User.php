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
                $this->ajax_return(400,MESSAGE_ERROR_REQUEST_TYPE);
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
        $this->load->model('User_model');
        $account = trim($this->input->post('inputUser'));
        $password = $this->input->post('inputPassword');
        $surname = $this->input->post('inputSurname');
        $sex = $this->input->post('inputSex');
        if($account && $password && $surname){
            if(strlen($account) > 20) $this->ajax_return(400,MESSAGE_ERROR_ACCOUNT_NONE);
        }
        else $this->ajax_return(400,MESSAGE_ERROR_PARAMETER);

        $password = md5($password . ENCRYPT_KEY);
        $code = $this->input->post('inputCode');
        if($code != REGISTER_CODE){
            $this->ajax_return(400,MESSAGE_ERROR_CODE);
        }else {
            $whereUnique['user_account'] = $account;
            $re = $this->User_model->get($whereUnique);
            if ($re) $this->ajax_return(400, MESSAGE_ERROR_ACCOUNT_UNIQUE);

            $insertData['user_account'] = $account;
            $insertData['user_password'] = $password;
            $insertData['surname'] = $surname;
            $insertData['sex'] = $sex;
            $insertData['register_datetime'] = date('Y-m-d H:i:s',time());

            $re = $this->User_model->insert($insertData);
            $this->ajax_return(200, MESSAGE_SUCCESS, $re);
        }
    }

    private function update(){
        $user_id = $this->USER_ID;

        $data = array(
            'name' =>  $this->input->input_stream('name'),
            'industry' =>  $this->input->input_stream('industry'),
            'post' => $this->input->input_stream('post'),
            'phone' =>  $this->input->input_stream('phone')
        );
        $re = $this->User_model->update($user_id,$data);
        if ($re === false) {
            $this->ajax_return(400,MESSAGE_ERROR_DATA_WRITE);
        }else
            $this->ajax_return(200,MESSAGE_SUCCESS,$this->User_model->last_sql());
    }

    public function password(){
        if(REQUEST_METHOD !== REQUEST_POST) $this->ajax_return(400,MESSAGE_ERROR_REQUEST_TYPE);

        $user_id = $this->USER_ID;

        $oldPass =md5($this->input->post('oldPassword').ENCRYPT_KEY);
        $where['user_id'] = $user_id;

        $rePass = $this->User_model->get($where,'user_password');
        if($oldPass != $rePass['user_password'])
        {
            $this->ajax_return(400, MESSAGE_ERROR_CHANGE_PASSWORD,$this->User_model->last_sql());
        }

        $newpassword = $this->input->post('newPassword');
        $confirm = $this->input->post('cNewPassword');

        if($newpassword !== $confirm){
            $this->ajax_return(400, MESSAGE_ERROR_PARAMETER_CONFIRM);
        }

        $newpassword = md5($newpassword.ENCRYPT_KEY);
        $upData['user_password'] = $newpassword;
        $res = $this->User_model->update($user_id,$upData);

        if ($res < 0) {
            $this->ajax_return(400, MESSAGE_ERROR_PARAMETER);
        } else {
            $this->ajax_return(200, MESSAGE_SUCCESS);
        }
    }
}