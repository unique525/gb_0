<script type="text/javascript">
    $(function() {
        $(".pb1").mouseover(function() {
            $(this).attr("class", "pb1_over");
        });
        $(".pb1").mouseout(function() {
            $(this).attr("class", "pb1");
        });
        $(".pb3").mouseover(function() {
            $(this).attr("class", "pb3_over");
        });
        $(".pb3").mouseout(function() {
            $(this).attr("class", "pb3");
        });
    });

</script>
<div id="pagerbtn">
    <div class="pb3"><a href="{url}&p={firstindex}">第一页</a></div>
    <div {showpre} class="pb3"><a href="{url}&p={preindex}">上一页</a></div>
    {pagerlist}
    <div {shownext} class="pb3"><a href="{url}&p={nextindex}"><span id="linkNext">下一页</span></a></div>
    <div class="pb3"><a href="{url}&p={endindex}">最末页</a></div>
    <div class="pb5">{nowindex}/{allindex}</div>
    <div class="pb5">总共{allcount}/每页{pagesize}</div>
    <div class="pb4"><input type="text" maxlength="6" value="输入页码" class="pagerinput" onfocus="this.value = '';" onkeypress="if (event.keyCode == 13) {
            if (!isNaN(parseInt(this.value))) {
                window.location.href = '{rd}&p=' + this.value.replace('.', '');
            } else {
                alert('请输入数字');
            }
        }" /></div>
    <div class="spe2"></div>
</div>