<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .mg-top-120{margin-top:120px;}
    h3,h4{
        margin-top:20px;
    }
    .main h4{margin-top:0;}
    .btn-group-justified{
        border:1px solid #dfe2e5;
    }
    .btn-group{
        border-left:1px solid #dfe2e5;
        padding:5px 15px;
        color:#333;
    }
    .btn-group:first-child{
        border-left:none;
    }
    .btn-group-none{
        background-color: #fafbfc;
        color: #c6cbd1;
    }
    form{
        margin-top: 15px;
    }
    form .form-group{

    }
    .help-block{font-size:12px;}
    .tip{
        border-top:1px solid #dfe2e5;
        border-bottom:1px solid #dfe2e5;
        padding:15px 0;
        margin-top:15px;
        color:#333;
    }
</style>
<div class="container">
    <h3 class="txt-shadow mg-top-120">加入iLiDan</h3>
    <h4 class="text-muted">生活总是坎坷，能少一个是一个。</h4>
    <div class="btn-group-justified mg-top-50">
        <div class="btn-group">
            <strong>开始</strong><br>创建账号
        </div>
        <div class="btn-group btn-group-none">
            <strong>完善</strong><br>个人基本信息
        </div>
        <div class="btn-group btn-group-none">
            <strong>最后</strong><br>请开始你的表演
        </div>
    </div>
    <div class="row main mg-top-50">
        <div class="col-sm-12 col-md-8">
            <h4 class="txt-shadow"><strong>创建你的个人账户</strong></h4>
            <div class="row">
                <form method="post" action="/join" autocomplete="off" class="col-sm-12 col-md-8" id="joinForm">
                    <?php if(isset($error_message))
                        echo '<div class="alert alert-danger alert-dismissible" role="alert" id="alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="danger-text">'.$error_message.'</span>
        </div>'
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="inputCode">注册码</label>
                        <input type="text" class="form-control" name="inputCode" id="inputCode" required>
                        <p class="help-block">注册码是通过网站定期发放的。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputUser">用户名</label>
                        <input type="text" class="form-control" name="inputUser" id="inputUser" maxlength="20" required>
                        <p class="help-block">以字母开头，长度在 20 个字符(下划线、字母、数字)。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputPassword">密 码</label>
                        <input type="password" class="form-control" name="inputPassword" id="inputPassword" required>
                        <p class="help-block">请你放心，我不会傻到直接明文保存。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSurname">姓 氏</label>
                        <input type="text" class="form-control" name="inputSurname" id="inputSurname" required>
                        <p class="help-block">你的姓氏将会在发布信息或留言时展示。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSex">性 别</label>
                        <select class="form-control" id="inputSex" name="inputSex">
                            <option value="0">男</option>
                            <option value="1">女</option>
                        </select>
                        <p class="help-block">你的性别会伴随姓氏展示在页面上。</p>
                    </div>
                    <p class="help-block tip">当你按下「创建账户」按钮时，则表示你同意并坚决执行 iLiDan 守则( 是什么，我也还不知道 )。</p>
                    <button type="submit" data-lock="true" data-unlock-txt="创建账户" data-lock-txt="创建中..." id="sendBtn" class="button button-primary button-rounded">创建账户</button>
                </form>
            </div>
        </div>
        <div class="col-md-4 hidden-xs hidden-sm">
            <div class="panel panel-info">
                <div class="panel-heading txt-shadow"><strong>写在此处的一些话</strong></div>
                <div class="panel-body">
                    <p class="text-muted">iLiDan 暂时没有开放注册，单会不定期开放注册码。<br /><br />
                        当你发布消息或留言时，只会展示出你的姓氏。如：Mr .王、Mrs .刘<br /><br />
                        这个网站是由个人开发和维护，所以，如果您是技术大神，请手下留情。</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var submit_lock = true;
    function page_init(){
        $("#joinForm").submit(function(){
            ajax_submit();
            return false;
        });
        $("#inputUser").change(function(){
            var inputUser = $(this);
            var objPar = inputUser.parent();
            if(/^[a-zA-Z]{1}[A-Za-z0-9_]*$/.test(inputUser.val())){
                if(objPar.hasClass('has-error')) objPar.removeClass('has-error');
                submit_lock = false;
            }else{
                if(!objPar.hasClass('has-error')) objPar.addClass('has-error');
                submit_lock = true;
            }
        });
    }
    function ajax_submit(){
        if(submit_lock) return false;
        var comment_uri = '/join';
        ILIDAN_AJAX_OBJ = $("#sendBtn");
        $.ajax({
            url: comment_uri,
            type:'post',
            data:$('#joinForm').serialize(),
            success:function(data){
                if(data.code == 200) {
                    alert('OK');
                }
                else alert(data.info);
            }
        });
    }
</script>