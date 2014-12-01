<div id="pagerbtn_comment">
<div class="pb3"><span style="cursor:pointer;" onclick="{jsfunctionname}({firstindex_c}{paramlist})">第一页</span></div>
<div {showpre} class="pb3"><span style="cursor:pointer;" onclick="{jsfunctionname}({preindex_c}{paramlist})">上一页</span></div>
{pagerlist}
<div {shownext} class="pb3"><span style="cursor:pointer;" onclick="{jsfunctionname}({nextindex_c}{paramlist})">下一页</span></div>
<div class="pb3"><span style="cursor:pointer;" onclick="{jsfunctionname}({endindex_c}{paramlist})">最末页</span></div>
<div class="pb5">{nowindex}/{allindex}</div>
<div class="pb5">总共{allcount}/每页{pagesize}</div>
<div class="pb4"><input type="text" maxlength="6" value="输入页码" class="pagerinput" onfocus="this.value='';" onkeypress="if(event.keyCode == 13){if(!isNaN(parseInt(this.value))){{jsfunctionname}(this.value.replace('.',''){paramlist});}else{alert('请输入数字');}}"></div>
<div class="spe2"></div>
</div>