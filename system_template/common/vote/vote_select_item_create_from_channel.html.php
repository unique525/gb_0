<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}

    <script type="text/javascript" language="JavaScript">
        $().ready(function () {
            $("#btn_check").click(function(){
                $("#btn_check").hide();
                var channelId=parseInt($("#channel_id").val());
                var itemCount=parseInt($("#item_count").val());
                var state=parseInt($("#state").val());
                if(channelId>0&&itemCount>0){
                    $.ajax({
                        url:"/default.php?secu=manage&mod=vote_select_item&m=pre_check_creating_from_channel&channel_id="+channelId+"&item_count="+itemCount+"&state="+state,
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            if(data["ChannelType"]==-100){
                                alert("节点类型错误！");
                            }else{
                                var formatStr="";
                                switch(data["ChannelType"]){
                                    case 1: //document news
                                        for(var i=0;i<data["list"].length;i++){
                                            $("#check_result").append(data["list"][i]["DocumentNewsTitle"]+"<br />");
                                            //$("#main_form").append("")


                                            //处理转向地址
                                            var directUrl="";
                                            if((data["list"][i]["DirectUrl"])==""){
                                                directUrl=data["list"][i]["DocumentNewsUrl"];
                                            }else{
                                                directUrl=data["list"][i]["DirectUrl"];
                                            }
                                            //格式化源url
                                            formatStr+=',' +
                                                '{"f_VoteSelectItemTitle":"'+data["list"][i]["DocumentNewsTitle"]+'",' +
                                                '"f_VoteItemId":"{VoteItemId}",' +
                                                '"f_TableType":"'+data["ChannelType"]+'",' +
                                                '"f_TableId":"'+data["list"][i]["DocumentNewsId"]+'",' +
                                                '"f_DirectUrl":"'+directUrl+
                                                '"}';
                                        }

                                }
                                formatStr="["+formatStr.substr(1)+"]";
                                $("#JSONArray").val(formatStr);
                                $("#ChannelId").val(channelId);
                                $("#ChannelType").val(data["ChannelType"]);
                                $("#ItemCount").val(itemCount);
                                $(".btn_create").show();
                            }
                        }
                    });
                }else{
                    alert("请填入正确数值");
                }
            });

            $(".btn_create").click(function(){
                $(".btn_create").hide();
                $("#main_form").submit();
                //var formData=$("#main_form").serialize();
                //    $.ajax({
                //        type:"post",
                //        data:formData,
                //        url:"/default.php?secu=manage&mod=vote_select_item&m=create_from_channel",
                //        dataType:"jsonp",
                //        jsonp:"jsonpcallback",
                //        success:function(data){
                //            alert(data["result"]);
                //        }
                //    });
            });
        });

    </script>
</head>
<body>
{common_body_deal}
    <div>
        <div class="button">
            <label for="channel_id">源节点id:</label><input name="channel_id" id="channel_id" value="" type="text" />
            <label for="item_count">导出条数:</label><input name="item_count" id="item_count" value="" type="text" />
                <label for="state">导出：</label>
                    <select id="state" name="state">
                        <option value="30">已发</option>
                        <option value="0">新稿</option>
                        <option value="1">已编</option>
                        <option value="2">返工</option>
                        <option value="11">一审</option>
                        <option value="12">二审</option>
                        <option value="13">三审</option>
                        <option value="14">终审</option>
                    </select>
            <button id="btn_check" class="btn2">检查</button>
            <button id="" class="btn_create btn2" style="display: none">导出到投票</button>
        </div>
        <form id="main_form" action="/default.php?secu=manage&mod=vote_select_item&m=create_from_channel&vote_item_id={VoteItemId}" method="post">
            <input name="ChannelId" id="ChannelId" value="" type="hidden" />
            <input name="ChannelType" id="ChannelType" value="" type="hidden" />
            <input name="ItemCount" id="ItemCount" value="" type="hidden" />
            <input name="JSONArray" id="JSONArray" value="" type="hidden" />
        </form>
        <div id="check_result" style="margin:10px;"></div>
        <button id="" class="btn_create btn2" style="display: none">导出到投票</button>
    </div>
</body>
</html>