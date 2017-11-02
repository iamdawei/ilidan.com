<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//自定义路由规则
$route['logout'] = 'Home/logout';
$route['login'] = 'Home/login';
$route['session'] = 'Home/session';
$route['join'] = 'Home/join';

$route['search'] = 'Home/index';
$route['message/:num'] = 'home/message';
$route['message/:num/comment'] = 'message/comment';
$route['rank'] = 'Home/rank';
