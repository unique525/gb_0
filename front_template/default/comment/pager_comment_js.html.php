<div id="pagerbtn_comment">
<div class="pb3"><span style="cursor:pointer;" onclick="{JsFunctionName}({FirstIndexC}{ParamList})">第一页</span></div>
<div {ShowPre} class="pb3"><span style="cursor:pointer;" onclick="{JsFunctionName}({PreIndexC}{ParamList})">上一页</span></div>
{PageList}
<div {ShowNext} class="pb3"><span style="cursor:pointer;" onclick="{JsFunctionName}({NextIndexC}{ParamList})">下一页</span></div>
<div class="pb3"><span style="cursor:pointer;" onclick="{JsFunctionName}({EndIndexC}{ParamList})">最末页</span></div>
<div class="pb5">{NowIndex}/{AllIndex}</div>
<div class="pb5">总共{AllCount}/每页{PageSize}</div>
<div class="pb4"><input type="text" maxlength="6" value="输入页码" class="pagerinput" onfocus="this.value='';" onkeypress="if(event.keyCode == 13){if(!isNaN(parseInt(this.value))){{JsFunctionName}(this.value.replace('.',''){ParamList});}else{alert('请输入数字');}}"></div>
<div class="spe2"></div>
</div>