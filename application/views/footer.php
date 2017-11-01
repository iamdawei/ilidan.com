<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="clearfix"></div>
<footer class="footer">
        <p style="font-size: 24px;">ilidan.com</p>
        <p style="font-size: 12px;font-weight: normal;">来自 iam喂喂</p>
</footer>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/jquery.js"><\/script>')</script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/jquery.gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="/js/jquery.Paginator/jqPaginator.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<?php
echo isset($FOOTER_JAVASCRIPT) ? $FOOTER_JAVASCRIPT : '';
?>
<script type="text/javascript">
    //    var head_txt = [];
    //    head_txt[0] = '弄堂里，槐树下，一片清声知鸟笑<br />泥田里，水塘游，阵阵稻香老黄牛';
    //    head_txt[1] = '许你一场相见如故，眉目成书<br />还你一世分道陌路，背影成孤';
    //    var randomNub = (Math.floor(Math.random() * head_txt.length));
    //
    //    $("#header-title").html(head_txt[randomNub]);
    $(function () {
        $("#searchCompany").keyup(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                window.location.href = '/search?kw=' + $.trim($(this).val());
            }
        });
        if (typeof(page_init) === 'function') page_init();
    });
</script>
</html>