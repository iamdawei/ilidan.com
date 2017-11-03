<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//自定义路由规则
$route['logout'] = 'Home/logout';
$route['login'] = 'Home/login';
$route['join'] = 'Home/join';

$route['search'] = 'Home/index';
$route['message/:num'] = 'Home/message';
$route['message/:num/comment'] = 'Message/comment';

$route['rank'] = 'Home/rank';

$route['add']['get'] = 'Home/add';
$route['add']['post'] = 'Message/index';

$route['profile'] = 'Home/profile';
$route['profile/:num'] = 'User/index';
