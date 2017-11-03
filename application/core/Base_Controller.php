<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 自定义的基类
 * 主要包括：
 *   公共参数配置
 *   用户验证，token生成，ajax返回值
 */

define('ILIDAN_DOMAIN', '');

define('ILIDAN_UPLOAD_FILE','gif|jpg|png|doc|docx|ppt|pptx|xls|xlsx|mp3|mp4|rar|zip');

define('DEFAULT_AJAX_RETURN', 'JSON');
define('DEFAULT_JSONP_HANDLER', 'jsonpReturn');
define('VAR_JSONP_HANDLER', 'callback');
define('ENCRYPT_KEY', 'ILIDAN_SYSTEM');
define('DEFAULT_PASSWORD', '000000');
define('REGISTER_CODE', 1024);

define('REQUEST_METHOD', strtoupper($_SERVER['REQUEST_METHOD']));
define('REQUEST_GET', 'GET');
define('REQUEST_POST', 'POST');
define('REQUEST_PUT', 'PUT');
define('REQUEST_DELETE', 'DELETE');

/*
 * ---------------------
 * 请求响应info配置
*/

define('MESSAGE_SUCCESS', 'success');

//身份信息
define('MESSAGE_ERROR_TOKEN_OVERDUE', '你的TOKEN已过期');
define('MESSAGE_ERROR_WARNING_TOKEN', '非法警告：TOKEN不正确');
define('MESSAGE_ERROR_WARNING_AUTH', '非法警告：用户身份错误');
define('MESSAGE_ERROR_USER_ROLE', '该功能没有权限');

//参数错误
define('MESSAGE_ERROR_PARAMETER', '请求参数不正确');
define('MESSAGE_ERROR_PARAMETER_CONFIRM', '确认密码不匹配');
define('MESSAGE_ERROR_ACCOUNT_NONE', '账号不能为空');
define('MESSAGE_ERROR_ACCOUNT_UNIQUE', '该账号已存在');
define('MESSAGE_ERROR_COMPANY_UNIQUE', '该公司已收录');
define('MESSAGE_ERROR_ACCOUNT_PASSWORD', '账号或密码不正确');
define('MESSAGE_ERROR_CODE', '注册码错误');
define('MESSAGE_ERROR_NON_DATA', '数据不存在');
define('MESSAGE_ERROR_REQUEST_TYPE', '请求方式不正确');
define('MESSAGE_ERROR_CHANGE_PASSWORD', '您的初始密码不正确');
define('MESSAGE_ERROR_DATA_WRITE', '数据更新错误');

define('MESSAGE_ERROR_COMMENT_UNQIUE', '你已经留过言了');


class Base_Controller extends CI_Controller
{
    protected $HTTP_TOKEN = '';
    protected $HTTP_TOKEN_SIGN = array();
    protected $USER_ID = null;

    protected function valid_kkd_token($token)
    {
        if(!isset($token)) return false;
        if(!$token) return false;
        $sign = @unserialize(base64_decode($token));
        if($sign === false) return false;
        if($sign['user_id'] && $sign['create_time']){
            $this->HTTP_TOKEN_SIGN = $sign;
            $this->USER_ID = $sign['user_id'];
            return true;
        }
        return false;
    }

    protected function direct($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function ajax_return($code='',$info='',$data='',$type=''){
        $result = array(
            'code' => $code,
            'info' => $info,
            'result' => $data
        );
        $this->_ajax_return($result,$type);
    }

    private function _ajax_return($data,$type='') {
        if(empty($type)) $type  =   DEFAULT_AJAX_RETURN;
        switch (strtoupper($type)){
            case 'JSON' :
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[VAR_JSONP_HANDLER]) ? $_GET[VAR_JSONP_HANDLER] :DEFAULT_JSONP_HANDLER;
                exit($handler.'('.json_encode($data).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
        }
    }

    protected function curl_request($url, $type = "GET", $data = '')
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch,CURLOPT_HEADER,0);

        $type = strtolower($type);
        switch ($type){
            case 'get':
                break;
            case 'post':
                //post请求配置
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function set_token($user_id)
    {
        $sign = array(
            'user_id' => $user_id,
            'create_time' => time()
        );

        return base64_encode(serialize($sign));
    }
}

/*
 * 这个类是提供web服务控制器的基类
 *
 * 主要用于请求header里的token验证
 *
 * */
class API_Conotroller extends Base_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        $router = & load_class('Router', 'core');
        $controller = strtolower($router->fetch_class());
        $method = strtolower($router->fetch_method());
        //跳过请求openid的验证
        if($controller == 'user' && REQUEST_METHOD == REQUEST_POST) return true;

        if(isset($_SERVER['HTTP_TOKEN']))
            $this->HTTP_TOKEN = $_SERVER['HTTP_TOKEN'];
        else
            $this->ajax_return(300,MESSAGE_ERROR_WARNING_AUTH);

        if(!isset($this->HTTP_TOKEN) && mpty($this->HTTP_TOKEN)) $this->ajax_return(300,MESSAGE_ERROR_WARNING_TOKEN);

        $sign = $this->valid_kkd_token($this->HTTP_TOKEN);
        if($sign === false) $this->ajax_return(300,MESSAGE_ERROR_WARNING_TOKEN);
    }
}
/*
 * 这个类是提供对页面访问（home控制器）的基类
 *
 * 主要用于请求cookie和session分配与验证
 *
 * */
class WEB_Conotroller extends Base_Controller
{
    protected $userInfo = [];
    function __construct()
    {
        parent::__construct();
        $this->init();
    }

    private function init()
    {
        session_start();

        $router = & load_class('Router', 'core');
        $controller = strtolower($router->fetch_class());
        $method = strtolower($router->fetch_method());

        $this->load->helper('cookie');
        $this->HTTP_TOKEN = get_cookie('token');

        if($this->HTTP_TOKEN){
            $sign = $this->valid_kkd_token($this->HTTP_TOKEN);
            if($sign === false) $this->direct('/login');

            $this->load->model('User_model');
            $where['user_id'] = $this->USER_ID;
            $this->userInfo = $this->User_model->get($where);
            if(!$this->userInfo) $this->direct('/login');
            else{
                $_SESSION['surname'] = $this->userInfo['surname'];
                $_SESSION['sex'] = $this->userInfo['sex'];
            }
        }

        //如果用户是发布和查看个人信息，则需要判断是否登录
        if($method == 'add' || $method == 'profile'){
            if(isset($_SESSION['surname']) && isset($_SESSION['sex']) && $this->USER_ID){
                return true;
            }else
            {
                $this->direct('/login');
            }
        }

    }
}