<style type="text/css">
    .huge {
        margin-top: 50px;
        background-color: #00b185;
        background-color: -moz-linear-gradient(45deg, #00b185 0%, #28cbd3 100%);
        background-color: -webkit-gradient(left bottom, right top, color-stop(0%, #00b185), color-stop(100%, #28cbd3));
        background-color: -webkit-linear-gradient(45deg, #00b185 0%, #28cbd3 100%);
        background-color: -o-linear-gradient(45deg, #00b185 0%, #28cbd3 100%);
        background-color: -ms-linear-gradient(45deg, #00b185 0%, #28cbd3 100%);
        background-color: linear-gradient(45deg, #00b185 0%, #28cbd3 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00b185', endColorstr='#28cbd3', GradientType=1 );
        background-image: url(/images/bg.png) !important;
        background-repeat: repeat;
        background-position: center top;
        color: #fff;
    }
    .huge .huge__text {
        padding-top: 120px;
        padding-bottom: 160px;
        text-align: center;
    }
    .title-h1 {
        font-size: 32px;
        line-height: 60px;
        font-weight: 600;
        text-transform: uppercase;
        padding-bottom: 60px;
    }
    .huge .huge-search{
        width:500px;
        margin:0 auto;
    }
    .huge-search .form-control{
        height:40px;
        font-size: 18px;
        border-radius: 0;
        border-color:#1b9af7;
    }
</style>
<div class="huge">
    <div class="container">
        <div class="col-md-12 col-lg-12 huge__text">
            <div class="title-h1 txt-shadow" id="huge-title">生活总是坎坷，能少一个是一个。</div>
            <div class="hidden-xs huge-search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="公司名称" id="searchInput" />
                    <span class="input-group-btn">
                        <button class="button button-uppercase button-primary" id="searchBtn" type="button">search</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<h3 class="text-center txt-shadow mg-top-50"><?php echo $title;?></h3>
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
<nav aria-label="Page navigation" class="text-center">
    <ul class="pagination" id="ilidan-pagination">
    </ul>
</nav>
<script type="text/javascript">
    var show_paginator = <?php echo $show_paginator;?>;
    var current_page = '<?php echo $current_page;?>';
    var total_page = '<?php echo $total_page;?>';
    function page_init(){
        $("#searchInput").keyup(function (event) {
            if(event.which == 13){
                window.location.href = '/search?kw=' + $.trim($("#searchInput").val());
            }
        });
        $("#searchBtn").on('click',function (event) {
            window.location.href = '/search?kw=' + $.trim($("#searchInput").val());
        });

        if(show_paginator) paginator_init(total_page,current_page);
    }
</script>