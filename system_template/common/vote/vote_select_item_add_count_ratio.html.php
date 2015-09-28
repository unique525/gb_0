<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
{common_head}

<script type="text/javascript" language="JavaScript">
    <!--
    function calculate(id){
        var itemCount=parseInt($(".grid_item").size());
        var recordCount=0;
        var addCount=0;
        var allCount=0;
        if(id>0){
            for(var i=0;i <= itemCount;i++){
                if(i!=id){
                    var addOther=parseInt($("#num_"+id).val())/parseFloat($("#per_"+id).html())*parseFloat($("#per_"+i).html());
                    $("#AddCount_"+i).val(parseInt($("#AddCount_"+i).val())+parseInt(addOther));
                }else{
                    $("#AddCount_"+i).val(parseInt($("#num_"+i).val())+parseInt($("#AddCount_"+i).val()))
                }
            }
        }
        for(var i=1;i<=itemCount;i++){
            recordCount+=parseInt($("#RecordCount_"+i).html());
            addCount+=parseInt($("#AddCount_"+i).val());
        }
        allCount=recordCount+addCount;
        for(var i=1;i<=itemCount;i++){
            $("#per_"+i).html(((parseInt($("#RecordCount_"+i).html())+parseInt($("#AddCount_"+i).val()))/allCount*100).toFixed(1))
        }
    }
        $().ready(function () {
		calculate(0);
        });
        
        function sub(){
            var itemCount=$(".grid_item").size();
            var result=0;
            for(var i=1;i<=itemCount;i++){
                if((parseInt($("#RecordCount_"+i).html())+parseInt($("#AddCount_"+i).html()))<0){
                    result=1;
                }
            }
            if(result>0){
                alert('数据错误！有票数为负');
            }else{
                $('#mainForm').submit();
            }
        }
    -->
</script>
</head>
<body>
<form id="mainForm" method="post" enctype="multipart/form-data"  action="/vote/index.php?a=voteselectitemmanage&m={method}&voteid={voteid}&voteitemid={voteitemid}">
<table class="grid" cellpadding="0" cellspacing="0">
<tr class="grid_title">
<td style="width:60px;text-align: center;"></td>
<td style="width:10px;text-align: center;"></td>
<td style="text-align: center;">选项名称</td>
<td style="width:60px;text-align:center;">票数</td>
<td style="width:60px; text-align:center;">加票数</td>
<td style="width:60px; text-align:center;">百分比</td>
<td style="width:60px;text-align:center;">选项ID</td>
</tr>
<icms id="vote_select_item_list" type="list">
<item>
<![CDATA[
<tr class="grid_item">
<td style="text-align: center;"><input id="num_{c_no}" type="text" style="width:60px"/></td>
<td style="text-align: center;"><input id="add_{c_no}" type="button" value="加" onclick='calculate({c_no})' /></td>
<td class="spe_line" style="text-align: center;" >{f_VoteSelectItemTitle}</td>
<td class="spe_line" style="text-align: center;" id="RecordCount_{c_no}">{f_RecordCount}</td>
<td class="spe_line" style="text-align: center;">
    <input class="input" id="AddCount_{c_no}" name="AddCount_{c_no}" type="text"   value="{f_AddCount}" style="width:60px"/>
    <input class="input" name="id_{c_no}" type="text"   value="{f_VoteSelectItemId}" style="width:60px;display:none"/>
</td>
<td class="spe_line" style="text-align: center;" id="per_{c_no}"></td>
<td class="spe_line" style="text-align: center;">{f_VoteSelectItemId}</td>
</tr>
]]>
</item>
</icms>
</table>
<table class="grid" cellpadding="0" cellspacing="0">
<tr>
<td><input style="margin-top: 30px" value="提交" class="btn" type="button" onclick="sub()" /></td>
</tr>
</table>
</form>
</body>
</html>