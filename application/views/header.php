<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="shortcut icon" href="">
    <title>iLidan</title>
    <?php
    echo isset($HEADER_CSS) ? $HEADER_CSS : '';
    ?>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.bootcss.com/Buttons/2.0.0/css/buttons.min.css" rel="stylesheet"/>

    <link href="/css/common.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/js/jquery.gritter/css/jquery.gritter.css"/>
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<noscript>
    <div class="ilidan-dialog-shadow"></div>
    <div class="ilidan-dialog-wrap" style="top:50px;">
        <div class="ilidan-dialog-container">
            <div class="ilidan-dialog-content table-style">
                <div style="text-align: center;"><p>你的浏览器不支持javascript，这将会严重影响浏览体验，请去设置开启</p></div>
            </div>
        </div>
    </div>
</noscript>
<body>
<!--[if lt IE 9]>
<style>
    html, body {
        overflow: hidden;
    }
</style>
<div class="ilidan-dialog-shadow"></div>
<div class="ilidan-dialog-wrap" style="top:50px;">
    <div class="ilidan-dialog-container">
        <div class="ilidan-dialog-content table-style">
            <div style="text-align: center;"><p>你的浏览器实在<strong>太....太旧了</strong>，放学别走，升级完浏览器再说</p><br/>
                <a target="_blank" class="btn btn-primary" href="http://browsehappy.com">立即升级</a></div>
        </div>
    </div>
</div>
<![endif]-->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">iLiDan</a>
        </div>
        <div class="navbar-collapse collapse" role="navigation">
            <ul class="nav navbar-nav">
                <li><a href="/rank" target="_blank">天字号地牢</a></li>
                <li><input type="text" class="form-control search-input" placeholder="搜公司名" autocomplete="off" id="searchCompany"></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/login">登录</a></li>
                <li><a href="/join">注册</a></li>
            </ul>
        </div>
    </div>
</div>
