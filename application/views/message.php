<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><style type="text/css">
    .message-info{
        margin-top:120px;
    }
    .message-info .form-control{
        height:40px;
        font-size: 18px;
        border-radius: 0;
        border-color:#1b9af7;
    }
    .text-muted{
        line-height: 1.5em;
    }
    h3,h4,h5{
        margin-top:20px;
    }
    h3{
        margin-top:0;
    }
    .message-list-header{
        padding-bottom: 15px;
    }
    .message-list{
        margin-top:15px;
        width:100%;
    }
    .message-list .input-group-addon{
        border-radius: 0;
        font-size: 12px;
        width:120px;
        text-align: right;
        border-color: #DEDEDE;
    }
    .message-list .form-control{
        height:auto;
        min-height:40px;
        font-size: 12px;
        line-height: 2em;
        padding-top: 12px;
        padding-bottom: 12px;
        border-color: #DEDEDE;
    }
</style>
<div class="container message-info">
    <div class="col-sm-12 col-md-8 pd-left-0">
        <h3 class="txt-shadow"><?php echo $message_info->company_name;?></h3>
        <h4><?php echo "$message_info->company_city , $message_info->surname";?> , <?php echo date('Y.m.d',strtotime($message_info->message_datetime));?></h4>
        <p class="text-muted mg-top-50">请不要说诸如「很坑」「垃圾」之类没有切实意义的词句。<br />在每则信息下，暂时每个用户只有一次留言的机会。<br />
            请简明精要（20字内）的阐述你在这家公司碰到的坑。如下述：</p>
        <ul>
            <li>面试官技术水平很差</li>
            <li>公司不给社保</li>
            <li>工资喜欢拖欠</li>
        </ul>
        <div class="input-group mg-top-50">
            <input type="text" class="form-control" placeholder="巧啊，你也被这家公司支配过。" id="messageInput" maxlength="20" />
            <span class="input-group-btn">
                <button class="button button-uppercase button-primary" data-lock="true" data-unlock-txt="发布你的留言" data-lock-txt="留言提交中" id="sendBtn" type="button">发布你的留言</button>
            </span>
        </div>
    </div>
    <div class="col-md-4 warp-panel-tips hidden-xs hidden-sm">
        <div class="panel panel-info">
            <div class="panel-heading txt-shadow"><strong>写在此处的一些话</strong></div>
            <div class="panel-body">
                <p class="text-muted">iLiDan 不会泄露您的名字，当你发布消息或留言时，将会展示出你的姓氏。如：Mr .王、Mrs .刘<br /><br />
                网络是嘈杂的，任何事情需要的是您自己的主观判断，希望您能尽量客观、实事的评说。<br /><br />
                如果你的评论是无任何参考价值，或虚假的，那么系统会进行删除处理。</p>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 mg-top-50 pd-left-0">
        <h5 class="message-list-header">同是天涯沦落人 <span class="label label-default"><?php echo $message_info->count;?></span></h5>
        <?php
        foreach($message_comment as $data){
            $userInfo = 'Mr .';
            if($data->user_sex) $userInfo = 'Mrs .';
            $userInfo .= $data->user_surname;
            echo  '<div class="input-group message-list">
<span class="input-group-addon">'.$userInfo.' ：</span>
    <div class="form-control">'.$data->comment_content.'</div>
</div>';
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    var comment_uri = '/message/<?php echo $message_id;?>/comment';
    function page_init(){
        $("#sendBtn").on('click',function(){
            if(!ilidanUid) return alert('你还没有登录');
            var messageValue = $.trim($("#messageInput").val());
            if(messageValue.length > 0 && messageValue.length < 21){

                ILIDAN_AJAX_OBJ = $(this);
                $.ajax({
                    url: comment_uri,
                    type:'post',
                    data:'comment_content=' + messageValue,
                    success:function(data){
                        if(data.code == 200) {
                            var surname = data.result.user_surname;
                            var sex = (data.result.user_sex)?'Mr':'Mrs';
                            $(".message-list-header").after(`<div class="input-group message-list">
                                <span class="input-group-addon">${sex} .${surname} ：</span>
                            <div class="form-control">${messageValue}</div>
                                </div>`);
                            $("#messageInput").val('');
                        }
                        else alert(data.info);
                    }
                });

            }else
            {
                alert('请输入 20 字以内留言');
            }
        });
    }
</script>