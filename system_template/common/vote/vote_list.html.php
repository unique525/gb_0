<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/vote/vote.js"></script>
</head>
<body>
<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="500px"></iframe>
    </div>
</div>
<div class="div_list">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td id="td_main_btn">
            <input id="btn_create" class="btn2" value="新建投票调查" title="在本频道新建投票调查" type="button"/>
        </td>
    </tr>
</table>
<table class="grid" width="100%" cellpadding="0" cellspacing="0">
<tr class="grid_title">
<td style="width:30px; text-align: center;">全</td>
<td style="width:40px;text-align:center;">编辑</td>
<td style="text-align: center">投票名</td>
<td style="text-align: center">投票ID</td>
<td style="width:60px; text-align:center;">编辑题目</td>
<td style="width:180px; text-align:center;">创建时间</td>
<td style="width:180px; text-align:center;">开始时间</td>
<td style="width:180px; text-align:center;">到期时间</td>
<td style="width:60px; text-align:center;">状态</td>
<td style="width:80px; text-align: center;">启用|停用</td>
</tr>
<icms id="vote_list" type="list">
<item>
<![CDATA[
<tr class="grid_item">
<td class="spe_line2" style="text-align: center;"><label><input name="vote_input" class="input_select" type="checkbox" value=""/></label></td>
<td class="spe_line2" style="text-align: center;"><img class="btn_modify" style="cursor:pointer" src="/system_template/{template_name}/images/manage/edit.gif" alt="编辑" idvalue="{f_VoteId}" /></td>
<td class="spe_line2" style="text-align: center;">{f_VoteTitle}</td>
<td class="spe_line2" style="text-align: center;">{f_VoteId}</td>
<td class="spe_line2" style="text-align: center;"><span class="btn_open_vote_item_list" style="cursor:pointer;"  idvalue="{f_VoteId}" title="{f_VoteTitle}" >编辑题目</span></td>
<td class="spe_line2" style="text-align:center;">{f_CreateDate}</td>
<td class="spe_line2" style="text-align:center;">{f_BeginDate}</td>
<td class="spe_line2" style="text-align:center;">{f_EndDate}</td>
<td class="spe_line2" style="text-align:center;"><span class="span_state" title="{f_State}" id="span_state_{f_VoteId}">{f_State}</span></td>
<td class="spe_line2" style="text-align: center;"><img alt="" class="div_start" idvalue="{f_VoteId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;<img alt="" class="div_stop" idvalue="{f_VoteId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" /></td>
</tr>
]]>
</item>
</icms>
</table>
</div>
<div>{pager_button}</div>
</body>
</html>