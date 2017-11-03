<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 程序控制器默认入口
 * 登录，登出，分配Token
 */

class Home extends WEB_Conotroller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $key = $this->input->get('kw');

        $limit = 12;
        $total = 0;
        $currentPage = 1;
        $show_paginator = 0;
        if(!empty($key)){
            $this->load->model('Message_model');
            $where['keywords'] = $key;
            $result = $this->Message_model->get_list($where, $limit,$currentPage, $total);
            if($result){
                $main['title'] = "找到 {$total} 条符合 {$key} 的结果";
                $show_paginator = 1;
            } else{
                $main['title'] = "没有找到符合 {$key} 的结果";
            }
        }else{
            $this->load->model('Message_model');
            $result = $this->Message_model->get_top_n(12);
            $main['title'] = '近期收录';
        }
        $main['current_page'] = $currentPage;
        $main['total_page'] = ceil($total / $limit);
        $main['show_paginator'] = $show_paginator;
        $main['page_datas'] = $result;
        $this->load->view('header');
        $this->load->view('home',$main);
        $this->load->view('footer');
    }

    public function message(){
        $message_id = $this->uri->segment(2, 0);
        $this->load->model('Message_model');
        $result = $this->Message_model->get($message_id,'message_status=1');

        if(!$result){
            show_404();
        }

        $message_id = $this->uri->segment(2, 0);
        $this->load->model('Comment_model');
        $reComment = $this->Comment_model->get_all($message_id);

        $data['message_info'] = $result;
        $data['message_comment'] = $reComment;
        $data['message_id'] = $message_id;
        $this->load->view('header');
        $this->load->view('message',$data);
        $this->load->view('footer');
    }

    public function rank()
    {
        $this->load->model('Message_model');
        $result = $this->Message_model->get_top_n(12,'count DESC');

        $main['page_datas'] = $result;
        $this->load->view('header');
        $this->load->view('rank',$main);
        $this->load->view('footer');
    }

    public function login(){
        if(REQUEST_METHOD === REQUEST_GET) $this->load->view('login');
        else{
            echo $this->input->post('_fid');
            if($this->input->post('_fid')) $this->direct('/login');
            $account = trim($this->input->post('inputUser'));
            $password = $this->input->post('inputPassword');
            $password = md5($password . ENCRYPT_KEY);
            $this->load->model('User_model');
            $where['user_account'] = $account;
            $where['user_password'] = $password;
            $data = $this->User_model->get($where);
            if ($data) {
                $sign = $this->set_token($data['user_id']);
                $time = 7*86400;
                $this->load->helper('cookie');
                set_cookie('token',$sign,$time);
                $this->direct('/');
            }else{
                $data['login_message'] = MESSAGE_ERROR_ACCOUNT_PASSWORD;
                $this->load->view('login',$data);
            }
        }
    }

    public function join(){
        if(REQUEST_METHOD === REQUEST_GET){
            $this->load->view('header');
            $this->load->view('join');
            $this->load->view('footer');
        }
        else if(REQUEST_METHOD === REQUEST_POST){
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
            }else{
                $whereUnique['user_account'] = $account;
                $re = $this->User_model->get($whereUnique);
                if($re) $this->ajax_return(400,MESSAGE_ERROR_ACCOUNT_UNIQUE);

                $insertData['user_account'] = $account;
                $insertData['user_password'] = $password;
                $insertData['surname'] = $surname;
                $insertData['sex'] = $sex;

                $re = $this->User_model->insert($insertData);
                $this->ajax_return(200,MESSAGE_SUCCESS,$re);
            }
        }else{
            show_404();
        }
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['surname']);
        unset($_SESSION['sex']);
        session_destroy();
        $this->load->helper('cookie');
        set_cookie('token',0,-1);
        $this->direct('/');
    }

    public function add(){
        $OT['FOOTER_JAVASCRIPT'] = '<script type="text/javascript" src="/js/area.js"></script>';
        $this->load->view('header');
        $this->load->view('add');
        $this->load->view('footer',$OT);
    }

    public function profile(){
        $data['profile'] = $this->userInfo;
        $this->load->view('header');
        $this->load->view('profile',$data);
        $this->load->view('footer');
    }
}