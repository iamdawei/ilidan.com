<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
.mg-top-120{margin-top:120px;}
h3,h4{
    margin-top:20px;
}
</style>
<div class="container text-center">
    <h3 class="txt-shadow mg-top-120">天字号地牢</h3>
    <h4 class="text-muted">排行榜前20+，看看有哪些穷凶极恶之徒。</h4>
</div>
<div class="container list mg-top-50">
    <div class="row"><?php
        foreach($page_datas as $data){
echo  '<div class="col-sm-4 col-xs-12">
    <div class="list-item">
        <a href="/message/'.$data->message_id.'">'.$data->company_name.'</a>
        <p>发布人：'.$data->surname.' ， '.$data->count.'人留言</p>
    </div>
</div>';
        }
?>
    </div>
</div>