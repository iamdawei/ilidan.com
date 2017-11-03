<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .mg-top-120{margin-top:120px;}
    h3,h4{
        margin-top:20px;
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
    .select-inline{
        width: 200px;
        display: inline-block;
    }
</style>
<div class="container">
    <h3 class="txt-shadow mg-top-120">Hi，<?php if(isset($_SESSION['sex']) && isset($_SESSION['surname'])) {
            echo (($_SESSION['sex'])?'Mrs':'Mr').' .'.$_SESSION['surname'];
        }
        ?>，请开始你的表演</h3>
    <div class="row main mg-top-50">
        <div class="col-sm-12 col-md-8">
            <h4 class="txt-shadow"><strong>公布一条消息</strong></h4>
            <div class="row">
                <form method="post" action="/add" autocomplete="off" class="col-sm-12 col-md-8" id="addForm">
                    <?php if(isset($error_message))
                        echo '<div class="alert alert-danger alert-dismissible" role="alert" id="alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="danger-text">'.$error_message.'</span>
        </div>'
                    ?>
                    <div class="form-group">
                        <label class="control-label" for="company_name">公司名称</label>
                        <input type="text" class="form-control" name="company_name" id="company_name" required>
                        <p class="help-block">当提交被收录之后，这个公司信息就会被公开。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="company_province">所在城市</label>
                        <div class="form-group">
                            <select class="form-control select-inline" id="company_province" name="company_province" required>
                                <option value="">省份</option>
                            </select>
                            <select class="form-control select-inline" id="company_city" name="company_city" required>
                                <option value="">城市</option>
                            </select>
                        </div>
                        <p class="help-block">这家公司所在的城市。</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="message_content">说点什么</label>
                        <textarea class="form-control" name="message_content" id="message_content" maxlength="255" rows="3" style="resize:none" required></textarea>
                        <p class="help-block">请尽量简明扼要的进行描述。</p>
                    </div>
                    <p class="help-block tip">当你按下「发布这条消息」按钮时，则表示你同意并坚决执行 iLiDan 守则( 是什么，我也还不知道 )。</p>
                    <button type="submit" data-lock="true" data-unlock-txt="发布这条消息" data-lock-txt="发布中..." id="sendBtn" class="button button-primary button-rounded">发布这条消息</button>
                </form>
            </div>
        </div>
        <div class="col-md-4 hidden-xs hidden-sm">
            <div class="panel panel-info">
                <div class="panel-heading txt-shadow"><strong>写在此处的一些话</strong></div>
                <div class="panel-body">
                    <p class="text-muted">iLiDan 不会泄露您的名字，当你发布消息或留言时，将会展示出你的姓氏。如：Mr .王、Mrs .刘<br /><br />
                        网络是嘈杂的，任何事情需要的是您自己的主观判断，希望您能尽量客观、实事的评说。<br /><br />
                        iLiDan 会在你提交之后进行人工审查，如果你发布的消息没有及时显示，请不要着急。</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var submit_lock = true;
    function page_init(){
        _init_area(["company_province","company_city"]);
        $("#addForm").submit(function(){
            ajax_submit();
            return false;
        });
        $("#company_name").change(function(){
            var objPar = $(this).parent();
            if(objPar.hasClass('has-error')) objPar.removeClass('has-error');
        });
    }
    function ajax_submit(){
        var comment_uri = '/add';
        ILIDAN_AJAX_OBJ = $("#sendBtn");
        $.ajax({
            url: comment_uri,
            type:'post',
            data:$('#addForm').serialize(),
            success:function(data){
                if(data.code == 200) {
                    document.getElementById("addForm").reset();
                    alert('OK');
                }else if(data.code == 300){
                    $("#company_name").parent().addClass('has-error');
                    alert(data.info);
                }
                else alert(data.info);
            }
        });
    }
</script>