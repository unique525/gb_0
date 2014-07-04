<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type="text/css" href="{rootpath}/images/common.css" rel="stylesheet" />
        <script type="text/javascript" src="http://www.image1.cn/common/js/jquery-1.5.1.min.js"></script>
        <script type="text/javascript" src="{rootpath}/js/common.js"></script>
        <script type="text/javascript" src="{rootpath}/common/js/jquery-ui-1.8.17.custom.min.js"></script>
        <link href="{rootpath}/common/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    function calculate(id){
        var itemcount=$(".griditem").size();
        var recordcount=0;
        var addcount=0;
        var allcount=0;
        if(id>0){
            for(var i=1;i<=itemcount;i++){
                if(i!=id){
                    var addother=parseInt($("#num_"+id).val())/parseFloat($("#per_"+id).html())*parseFloat($("#per_"+i).html());
                    $("#addcount_"+i).val(parseInt($("#addcount_"+i).val())+parseInt(addother));
                }else{
                    $("#addcount_"+i).val(parseInt($("#num_"+i).val())+parseInt($("#addcount_"+i).val()))                     
                }
            }
        }
        for(var i=1;i<=itemcount;i++){
            recordcount+=parseInt($("#recordcount_"+i).html());
            addcount+=parseInt($("#addcount_"+i).val());
        }
        allcount=recordcount+addcount;
        for(var i=1;i<=itemcount;i++){
            $("#per_"+i).html(((parseInt($("#recordcount_"+i).html())+parseInt($("#addcount_"+i).val()))/allcount*100).toFixed(1))
        }
    }
        $().ready(function () {
		calculate();
        });
        
        function sub(){
            var itemcount=$(".griditem").size();
            result=0;
            for(var i=1;i<=itemcount;i++){
                if(parseInt($("#recordcount_"+i).html())+parseInt($("#addcount_"+i).html())<0){
                    result=1;
                }
            }
            if(result>0){
                alert('数据错误！有票数为负');
            }else{
                $('#add_together').submit();
            }
        }
</script>
    </head>
    <body>

<div style="margin:8px;hasvisdata">

<table class="docgrid" cellpadding="0" cellspacing="0">
<tr class="gridtitle">
<td style="width:60px;text-align: center;"></td>
<td style="width:10px;text-align: center;"></td>
<td style="text-align: center;">选项名称</td>
<td style="width:60px;text-align:center;">票数</td>
<td style="width:60px; text-align:center;">加票数</td>
<td style="width:60px; text-align:center;">百分比</td>
<td style="width:60px;text-align:center;">选项ID</td>
</tr>
        <form id="add_together" method="post" enctype="multipart/form-data"  action="/vote/index.php?a=voteselectitemmanage&m={method}&voteid={voteid}&voteitemid={voteitemid}">
<cscms id="voteselectitemlist" type="list">
<item>
<![CDATA[
<tr class="griditem">
<td style="text-align: center;"><input id="num_{c_no}" type="text" style="width:60px"/></td>
<td style="text-align: center;"><input id="add_{c_no}" type="button" value="加" onclick='calculate({c_no})' /></td>
<td class="speline" style="text-align: center;" >{f_voteselectitemtitle}</td>
<td class="speline" style="text-align: center;" id="recordcount_{c_no}">{f_recordcount}</td>
<td class="speline" style="text-align: center;">
    <input class="input" id="addcount_{c_no}" name="addcount_{c_no}" type="text"   value="{f_addcount}" style="width:60px"/>
    <input class="input" name="id_{c_no}" type="text"   value="{f_voteselectitemid}" style="width:60px;display:none"/>
</td>
<td class="speline" style="text-align: center;" id="per_{c_no}"></td>
<td class="speline" style="text-align: center;">{f_voteselectitemid}</td>
</tr>
]]>
</item>
</cscms>
        <tr>
            <td><input style="margin-top: 30px" value="提交" class="btn" type="button" onclick="sub()" /></td>
        </tr>
        </form>
</table>
</div>
    </body>
</html>