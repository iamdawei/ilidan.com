
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="shortcut icon" href="">
    <title>iLidan - 登录</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.bootcss.com/Buttons/2.0.0/css/buttons.min.css" rel="stylesheet"/>

    <link href="/css/common.css" rel="stylesheet"/>
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
        }
        .logo{
            background-color: #222;
            border-color: #080808;
            padding:10px 15px;
            color:#dddddd;
            font-size: 24px;
        }
        .logo:hover{
            color:#ffffff;
            text-decoration: none;
        }
        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin .checkbox {
            font-weight: normal;
        }
        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
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
<div class="text-center"><a class="logo" href="/"><strong>iLiDan</strong></a></div>
<div class="container">
    <form class="form-signin" method="post" action="/login">
        <h3 class="form-signin-heading">登 录 iLiDan</h3>
        <?php if(isset($login_message))
        echo '<div class="alert alert-danger alert-dismissible" role="alert" id="alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="danger-text">'.$login_message.'</span>
        </div>'
        ?>
        <label for="inputUser" class="sr-only">账 号</label>
        <input type="text" id="inputUser" name="inputUser" class="form-control" placeholder="账 号" required autofocus>
        <label for="inputPassword" class="sr-only">密 码</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="密 码" required>
        <button class="button button-rounded btn-block button-primary button-rounded" type="submit" id="sendBtn">登 录</button>
        <p class="help-block"><br />登录后的一段时间内，你不需要再次登录，除非主动退出或清除cookie</p>
    </form>
</div>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript">
    var isSubmit = false;
    $(function(){
        $("form").submit(function(){
            if(isSubmit == false){
                isSubmit = true;
                return true;
            }else{
                return false;
            }
        });
    });
</script>
</html>