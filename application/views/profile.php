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
    <h3 class="txt-shadow mg-top-120">欢迎来到 iLiDan</h3>
    <h4 class="text-muted">生活总是坎坷，能少一个是一个。</h4>
    <div class="row main mg-top-50">
        <div class="col-sm-12 col-md-8">
            <h4 class="txt-shadow"><strong>修改你的个人信息</strong></h4>
            <div class="row">
                <form method="post" action="/setting/info" autocomplete="off" class="col-sm-12 col-md-8" id="userForm">
                    <?php if(isset($error_message))
                        echo '<div class="alert alert-danger alert-dismissible" role="alert" id="alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="danger-text">'.$error_message.'</span>
        </div>'
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="inputAccount">账户名</label>
                        <input type="text" class="form-control" value="<?php echo $profile['user_account']; ?>" name="inputCode" id="inputAccount" disabled>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSn">姓 氏</label>
                        <input type="text" class="form-control" value="<?php echo $profile['surname']; ?>" id="inputSn" maxlength="20" disabled>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputSex">性 别</label>
                        <select class="form-control" id="inputSex" disabled>
                            <option value="0"<?php if($profile['sex']) echo ' selected'; ?>>男</option>
                            <option value="1"<?php if($profile['sex']) echo ' selected'; ?>>女</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="name">名 字</label>
                        <input type="text" class="form-control" value="<?php echo $profile['name']; ?>" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="industry">行 业</label>
                        <input type="text" maxlength="10" value="<?php echo $profile['industry']; ?>" class="form-control" name="industry" id="industry">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="post">职 位</label>
                        <input type="text" maxlength="10" value="<?php echo $profile['post']; ?>" class="form-control" name="post" id="post">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="phone">电 话</label>
                        <input type="text" maxlength="11" value="<?php echo $profile['phone']; ?>" class="form-control" name="phone" id="phone">
                    </div>
                    <p class="help-block tip">当你按下「保存账户」按钮时，则表示你同意并坚决执行 iLiDan 守则( 是什么？我也还不知道 )。</p>
                    <button type="submit" data-lock="true" data-unlock-txt="保存账户" data-lock-txt="保存中..." id="sendBtn" class="button button-primary button-rounded">保存账户</button>
                </form>
            </div>
            <p class="mg-top-50"></p>
            <h4 class="txt-shadow pass-title"><strong>修改密码</strong></h4>
            <div class="row">
                <form method="post" action="/setting/password" autocomplete="off" class="col-sm-12 col-md-8 pass-form" id="passForm">
                    <div class="form-group">
                        <label class="control-label" for="oldPassword">原来的密码</label>
                        <input type="password" value="" class="form-control" name="oldPassword" id="oldPassword" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="newPassword">新的密码</label>
                        <input type="password" value="" class="form-control" name="newPassword" id="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="cNewPassword">确认新密码</label>
                        <input type="password" value="" class="form-control" name="cNewPassword" id="cNewPassword" required>
                    </div>
                    <button type="button" data-lock="true" data-unlock-txt="修改密码" data-lock-txt="修改密码..." id="passwordBtn" class="button button-primary button-rounded">修改密码</button>
                </form>
            </div>
        </div>
        <div class="col-md-4 hidden-xs hidden-sm">
            <div class="panel panel-info">
                <div class="panel-heading txt-shadow"><strong>写在此处的一些话</strong></div>
                <div class="panel-body">
                    <p class="text-muted">行业、职位、电话信息只是为了做信息完整填写，如果有信息公布纠纷时，iLiDan 会作为联系参考和你协商，网站将不会展示出去给别人。<br /><br />
                        当你发布消息或留言时，只会展示出你的姓氏。如：Mr .王、Mrs .刘<br /><br />
                        这个网站是由个人开发和维护，所以，如果您是技术大神，请手下留情。</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var pass_lock = false;
    var comment_uri = '/setting/';
    function page_init(){
        $("#userForm").submit(function(){
            ajax_submit();
            return false;
        });
        $("#passwordBtn").on('click',function(){
            var btnThat = $(this);
            var op = $("#oldPassword").val();
            var np = $("#newPassword").val();
            var cnp = $("#cNewPassword").val();
            if(op == np) return alert('新旧密码不能相同');
            if(np != cnp) return alert('确认密码不一致');
            comment_uri = $('#passForm').attr('action');
            $.ajax({
                url: comment_uri,
                type:'post',
                data:$('#passForm').serialize(),
                success:function(data){
                    if(data.code == 200) {
                        alert('你的密码已变更');
                    }
                    else alert(data.info);
                },
                beforeSend:function(){
                    if(pass_lock === true){
                        btnThat.text('修改密码...');
                        return false;
                    }
                    else pass_lock = true;
                },
                complete:function(){
                    btnThat.text('修改密码');
                    pass_lock = false;
                    document.getElementById('passForm').reset();
                }
            });
        });
    }
    function ajax_submit(){
        ILIDAN_AJAX_OBJ = $("#sendBtn");
        comment_uri = $('#userForm').attr('action');
        $.ajax({
            url: comment_uri,
            type:'put',
            data:$('#userForm').serialize(),
            success:function(data){
                if(data.code == 200) {
                    alert('你的账户信息修改好了');
                }
                else alert(data.info);
            }
        });
    }
</script>