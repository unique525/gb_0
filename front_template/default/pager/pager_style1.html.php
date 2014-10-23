<script type="text/javascript">
    $(function() {
        var pb1 = $(".pb1");
        pb1.mouseover(function() {
            $(this).attr("class", "pb1_over");
        });
        pb1.mouseleave(function() {
            $(this).attr("class", "pb1");
        });
        var pb3 = $(".pb3");
        pb3.mouseover(function() {
            $(this).attr("class", "pb3_over");
        });
        pb3.mouseleave(function() {
            $(this).attr("class", "pb3");
        });
    });

</script>
<div id="pager_btn">
    <div class="pb3"><a href="{url}&p={firstindex}">第一页</a></div>
    <div {ShowPre} class="pb3"><a href="{url}&p={preindex}">上一页</a></div>
    {PageList}
    <div {shownext} class="pb3"><a href="{url}&p={nextindex}"><span id="linkNext">下一页</span></a></div>
    <div class="pb3"><a href="{url}&p={EndIndex}">最末页</a></div>
    <div class="pb5">{NowIndex}/{AllIndex}</div>
    <div class="pb5">总共{AllCount}/每页{PageSize}</div>
    <div class="pb4" style="display:{ShowGoTo}"><label>
            <input type="text" maxlength="6" value="输入页码" class="pager_input" onfocus="this.value = '';" onkeypress="if (event.keyCode == 13) {
            if (!isNaN(parseInt(this.value))) {
                window.location.href = '{rd}&{PageIndexName}=' + this.value.replace('.', '');
            } else {
                alert('请输入数字');
            }
        }"/>
        </label></div>
    <div class="spe2"></div>
</div>