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
        $data['HEADER_CSS'] = '<link href="/js/select/css/cs-select.css" rel="stylesheet" type="text/css" />
                 <link href="/js/select/css/cs-skin-border.css" rel="stylesheet" type="text/css" />';

        $data['FOOTER_JAVASCRIPT'] = '<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>';
        $this->load->view('header',$data);
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
        $this->load->view('home',$main);
        $this->load->view('footer',$data);
    }

    protected function search_list($key)
    {
        $where['company_name'] = $this->input->get('file_number');

        $is_open = $this->input->get('is_open');
        if(isset($is_open) && $is_open !== 'all')
        {
            $where['is_open'] = $is_open ;
        }

        $assessment_type = $this->input->get('assessment_type');
        if(isset($assessment_type) && $assessment_type !== 'all')
        {
            $where['assessment_type'] = $assessment_type;
        }

        $where['page'] = intval($this->input->get('page'));
        $where['school_id'] = $this->school_id;
        $where['keywords'] = $this->input->get('keywords');

        //确定每页显示，初始化总条数；
        $limit = 8;
        $total = 0;

        //默认起始页；
        if(empty($where['page'])) { $where['page'] = 1; }

        // 返回数组；
        $assessmentlist = array();
        $assessmentlist['data'] = $this->assessment_model->get_list($where, $limit, $total);

        // 返回总条数
        $assessmentlist['total'] = $total;

        // 返回当前页
        $assessmentlist['current_page'] = $where['page'];

        // 返回总页数
        $assessmentlist['total_page'] = ceil($total / $limit);

        $this->ajax_return(200,MESSAGE_SUCCESS,$assessmentlist);
    }

    public function login()
    {
        if(REQUEST_METHOD !== REQUEST_POST) $this->ajax_return(400,MESSAGE_ERROR_REQUEST_TYPE);
        $account = $this->input->post('username');
        $password = $this->input->post('password');
        $record = $this->input->post('record');
        $type = $this->input->post('type');

        if (empty($account) || empty($password)) {
            $this->ajax_return(400,MESSAGE_ERROR_PARAMETER);
        }
        $password = md5($password . ENCRYPT_KEY);
        switch ($type) {
            case 't':
                //教师
                $this->_teacher_login($account,$password,$record);
                break;
            case 's' :
                //学生
                $this->_student_login($account,$password);
                break;
            case 'g' :
                $this->_group_login($account,$password,$record);
                break;
            default:
                $this->ajax_return(400,MESSAGE_ERROR_PARAMETER);
                break;
        }
    }

    protected function _group_login($account,$password,$record)
    {
        $this->load->model('group_model');
        $where['guser_account'] = $account;
        $where['guser_password'] = $password;
        $data = $this->group_model->get_info($where, 'guser_id,guser_name,group_id');
        if ($data) {
            $sign = $this->set_kkd_token($data['guser_id'],$data['group_id'], 'g');
            $time = ($record)?(7*86400):0;
            $this->load->helper('cookie');
            set_cookie('token',$sign,$time);
            $this->load->model('user_model');
            $this->user_model->add_user_log($log=array('admin_id'=>$data['guser_id'],'log_type'=>0,'log_descript'=>'login','user_type'=>'g',
                'log_full_sql'=>$this->db->last_query(),'user_ip'=>$_SERVER['REMOTE_ADDR'],'log_datetime'=>date('Y-m-d H:i:s')));
            $this->ajax_return(200,MESSAGE_SUCCESS,$sign);
        }else
            $this->ajax_return(400,MESSAGE_ERROR_ACCOUNT_PASSWORD);
    }

    protected function _teacher_login($account,$password,$record)
    {
        $this->load->model('teacher_model');
        $where['teacher_account'] = $account;
        $where['teacher_password'] = $password;
        $data = $this->teacher_model->get_teacher($where, 'teacher_id,teacher_name,teacher_photo,tea.school_id');
        if ($data) {
            $sign = $this->set_kkd_token($data['teacher_id'],$data['school_id'], 't');
            $time = ($record)?(7*86400):0;
            $this->load->helper('cookie');
            set_cookie('token',$sign,$time);
            $this->load->model('user_model');
            $this->user_model->add_user_log($log=array('admin_id'=>$data['teacher_id'],'log_type'=>0,'log_descript'=>'login','user_type'=>'t',
                'log_full_sql'=>$this->db->last_query(),'user_ip'=>$_SERVER['REMOTE_ADDR'],'log_datetime'=>date('Y-m-d H:i:s')),$data['school_id']);
            $this->ajax_return(200,MESSAGE_SUCCESS,$sign);
        }else
            $this->ajax_return(400,MESSAGE_ERROR_ACCOUNT_PASSWORD);
    }

    protected function _student_login($account,$password)
    {
        $this->load->model('student_model');
        $data = $this->student_model->match_student_info($account, $password);
        if ($data) {
            $sign = $this->set_kkd_token($data['student_id'],0, 's');

            $this->ajax_return(200,MESSAGE_SUCCESS,$sign);
        }else
            $this->ajax_return(400,MESSAGE_ERROR_ACCOUNT_PASSWORD);
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_photo']);
        unset($_SESSION['user_type']);
        unset($_SESSION['group_model']);
        unset($_SESSION['school_id']);
        session_destroy();
        $this->load->helper('cookie');
        set_cookie('token',0,-1);
        $this->direct('/login.html');
    }
}