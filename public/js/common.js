$.ajaxSetup({
    headers: {
        'TOKEN': $.cookie('token')
    },
    dataType:'json',
    beforeSend:ilidan_ajax_beforeSend,
    error:ilidan_ajax_error,
    complete:ilidan_ajax_complete
});


getUrlParam = function (name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return decodeURI(r[2]);
    return null;
}

//公共信息常量配置部分
var ILIDAN_CONST_LOGIN_USERNAME = '您输入的账号有误';
var ILIDAN_MESSAGE_ERROR_PARAMETER = '请求参数不正确';

var ilidan_loading_txt = '<p class="ilidan-loading"><img src="/images/loading.gif" /><br />正在载入数据...</p>';
var ilidan_nonedata_txt = '<p class="ilidan-nonedata">别找了，真没东西啦~！</p>';

/*动态载入JS,CSS文件*/
function load_file(filename,filetype,callback){

    if(filetype == "js"){
        var fileref = document.createElement('script');
        fileref.setAttribute("type","text/javascript");
        fileref.setAttribute("src",filename);
        document.body.appendChild(fileref);
        fileref.onload=fileref.onreadystatechange=function(){
            if(!this.readyState||this.readyState=='loaded'||this.readyState=='complete'){
                callback();
            }
            fileref.onload=fileref.onreadystatechange=null;
        }
    }else if(filetype == "css"){
        var fileref = document.createElement('link');
        fileref.setAttribute("rel","stylesheet");
        fileref.setAttribute("type","text/css");
        fileref.setAttribute("href",filename);
        document.getElementsByTagName("head")[0].appendChild(fileref);
    }
}

Date.prototype.Format = function(fmt) {
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
}

//当前AJAX请求对象
var ILIDAN_AJAX_OBJ = '';

function ilidan_ajax_beforeSend()
{
    var is_lock = ILIDAN_AJAX_OBJ.attr('data-lock');
    if(is_lock === 'lock') return false;
    else request_lock();
}

function ilidan_ajax_complete()
{
    request_unlock();
}

function ilidan_ajax_error()
{
    alert('warning : ajax , site error.');
}

function request_lock()
{
    var obj = ILIDAN_AJAX_OBJ;
    var text = obj.attr('data-lock-txt');
    obj.attr('data-lock','lock');
    obj.text(text);
}

function request_unlock()
{
    var obj = ILIDAN_AJAX_OBJ;
    var text = obj.attr('data-unlock-txt');
    obj.attr('data-lock','unlock');
    obj.text(text);
}

function ilidan_dialog_ini(title,content,cssClass)
{
    if(!cssClass) cssClass='';
    var top = document.body.scrollTop||document.documentElement.scrollTop;
    var temp_obj = '<div class="ilidan-dialog-shadow"></div><div class="ilidan-dialog-wrap" style="top:'+(top+50)+'px;"><div class="ilidan-dialog-container"><div class="ilidan-dialog-header"><span>[dialog-title]</span><a class="ilidan-dialog-close" href="javascript:ilidan_dialog_close();"></a></div><div class="ilidan-dialog-content [cssClass]">[dialog-content]</div></div></div>';
    temp_obj = temp_obj.replace('[dialog-title]',title).replace('[dialog-content]',content).replace('[cssClass]',cssClass);
    $('body').append(temp_obj);
}

function ilidan_dialog_close()
{
    //$("html,body").animate({scrollTop: 0}, 500);
    $('.ilidan-dialog-shadow').length ? $('.ilidan-dialog-shadow').remove() : '';
    $('.ilidan-dialog-wrap').length ? $('.ilidan-dialog-wrap').remove() : '';
}

function ilidan_select_int()
{
    [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
        new SelectFx(el);
    } );
}
function page_loading_wait(obj)
{
    if(typeof (obj) !== 'string') obj = "#main-content";
    $(obj).html(ilidan_loading_txt);
}
function paginator_init(total_page,current_page)
{
    current_page=Number(current_page);
    total_page=Number(total_page);
    if(total_page==0) total_page = 1;
    if(current_page > total_page) current_page = 1;//避免当页码超出时抛出错误

    $.jqPaginator('#ilidan-pagination', {
        totalPages: total_page,
        visiblePages: 10,
        currentPage: current_page,
        prev: '<li class="prev"><a class="previous" href="javascript:void(0);">上一页</a></li>',
        next: '<li class="next"><a class="next" href="javascript:void(0);">下一页</a></li>',
        first: '<li class="first"><a href="javascript:void(0);">首页</a></li>',
        last: '<li class="last"><a href="javascript:void(0);">尾页</a></li>',
        page: '<li class="page"><a href="javascript:void(0);">{{page}}</a></li>',
        onPageChange: function (num, type) {
            if(type != 'init'){
                $("html,body").animate({scrollTop: 150}, 200);
            }
        }
    });
}
/*
 $.extend($.gritter.options, {
 class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
 position: 'bottom-left', // possibilities: bottom-left, bottom-right, top-left, top-right
 fade_in_speed: 100, // how fast notifications fade in (string or int)
 fade_out_speed: 100, // how fast the notices fade out
 time: 3000 // hang on the screen for...
 });
 */
if(typeof($.gritter) === 'object'){
    window.alert=function(txt){
        $.extend($.gritter.options, {
            position: 'bottom-right',
            fade_in_speed: 1000,
            fade_out_speed: 1000,
            time: 3000
        });
        $.gritter.add({
            title: 'iLiDan 提示',
            text: txt,
            class_name: 'my-gritter-class'
        });
    };
}