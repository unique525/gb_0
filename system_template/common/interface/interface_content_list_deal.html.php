<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">

        function sub()
        {
            $('#main_form').submit();
        }


        $().ready(function() {
            SortTree(0);

            $(".btn_cancel").click(function(){
                parent.$("#dialog_resultbox").dialog("close");
            });


            $("#check_channel_id").focus(function(){
                if($(this).attr("value")=="直接输入频道id..."){
                    $(this).attr("value","");
                }
            });


            //切换站点（跨站点操作）
            $(".site_selection").click(function(){
                var toSiteId=$(this).attr("idvalue");
                var docIdString=Request["doc_id_string"];
                var channelId=Request["channel_id"];
                var jsonType=Request["json_type"];
                window.location.href='/default.php?secu=manage&mod=interface&m={method}&channel_id='+channelId+'&to_site_id='+toSiteId+'&doc_id_string='+docIdString+'&json_type='+jsonType;
            });
        });


        function CheckChannelType(){
            var checkChannelId=parseInt($("#check_channel_id").val());
            $("#pop_input").attr("disabled","disabled");
            $.ajax({
                url: "/default.php?secu=manage&mod=channel&m=check_channel_type",
                data: { channel_type:{ChannelType}, checking_channel_id:checkChannelId },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function(data) {
                    if(data["result"]==="-1"){
                        $("#check_channel_id").val("节点类型获取失败");
                    }else if(data["result"]==="-2"){
                        $("#check_channel_id").val("value","目标节点没有权限");
                    }else if(data["result"]==="0"){
                        $("#pop_input").removeAttr("disabled");
                        $("#check_channel_id").val(checkChannelId+"："+data["channel_name"]+" #节点类型不匹配，请谨慎操作#");
                        $("#id_btn").css("display","block");
                        $("#pop_input").val(checkChannelId);
                    }else{
                        $("#pop_input").removeAttr("disabled");
                        $("#check_channel_id").val(checkChannelId+"："+data["channel_name"]);
                        $("#id_btn").css("display","block");
                        $("#pop_input").val(checkChannelId);

                    }
                }
            });
        }


        /**
         * 按rank迭代排序 呈树状显示
         * **/
        function SortTree(rank){
            var itemContent="";
            var parentId="";
            var items=$(".channel_item[idvalue="+rank+"]");
            if(items.length>0){
                for(var i=0;i<items.length;i++){  //id=sorted_id   idvalue=id   title=parentId
                    parentId=items[i].getAttribute("title")
                    itemContent='<li id="sorted_'+items[i].getAttribute("id")+'" title="'+parentId+'_last" idvalue="'+items[i].getAttribute("id")+'" style="margin-left: '+rank*3+'em">'+items[i].innerHTML+'</li>';
                    if(rank==0){
                        $("#sorted_tree").append(itemContent);
                    }else{
                        var prevItem=$("[title='"+parentId+"_last']");
                        if(prevItem.length<=0){
                            $("#sorted_"+parentId).after(itemContent);//父节点下没有子节点，新增一条  并更新自己为最后一个子节点
                        }else{
                            $("[title='"+parentId+"_last']").after(itemContent);  //找父节点下最后一个子节点，加在后面  并更新自己为最后一个子节点
                            $("[title='"+parentId+"_last']").attr("title",parentId);
                            $("#sorted_"+items[i].getAttribute("id")).attr("title",parentId+"_last");
                        }
                    }
                }

                SortTree(rank+1);
            }else{
            }
            if(rank==0){
                $("#left_tree").hide();
            }
        }

    </script>
    <style>
        ul{list-style-type:none;}
        li{list-style: none}
    </style>
</head>
<body>
<form id="main_form" action="/default.php?secu=manage&mod={mod}&m={method}&channel_id={ChannelId}&doc_id_string={DocIdString}" method="post">
    <input id="json_type" name="json_type" type="hidden" value="{JsonType}"/>
    <table id="tab_doc_list" style="" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style=" height: 40px; ">
                <div style="font-size: 14px;font-weight: bold;">当前操作的文档如下</div>
                <div style=" width: 90%; height: 50px; overflow: auto; background: #f5f5f5; padding: 2px; border: solid 1px #cccccc;">
                    {DocumentList}
                </div>
            </td>
        </tr>
    </table>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr style="display:{PicStyleSelector}">
            <td class="spe_line" style="width:150px;height:35px;text-align: right;"><label
                    for="PicStyle">内容图片大小格式：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="PicStyle" name="PicStyle">
                    <option value="default">默认</option>
                    <option value="mobile">手机</option>
                    <option value="pad">平板</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;">
                <label for="sel_site">跨站点请选择： </label>
                <select id="sel_site" name="SiteId">
                    <icms id="site_list" type="list">
                        <item>
                            <![CDATA[
                            <option class="site_selection" value="{f_SiteId}" idvalue="{f_SiteId}">{f_SiteName}</option>
                            ]]>
                        </item>
                    </icms>
                </select><!--，为<span style="color:red;"> {ManageUserGroupName} </span>授权-->
            </td>
            <script type="text/javascript">
                $("#sel_site").find("option[value='{SiteId}']").attr("selected",true);
            </script>
        </tr>
        <tr>
            <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;" colspan="2">
                <label for="use_ori_url">是否使用源地址作为直接转向</label>
                <input id="use_ori_url" name="use_ori_url" value="1" type="radio"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;" colspan="2">
                请选择您要{MethodName}到的频道
            </td>
        </tr>
        <tr>
            <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;" colspan="2">
                <label for="pop_input"></label>
                <input id="pop_input" name="pop_cid" value="" type="radio" disabled="disabled"/>
                <label for="check_channel_id"></label>
                <input type="text" id="check_channel_id" name="check_channel_id" class="input_box" style="height:22px;width:200px" value="直接输入频道id..."/>
                <input class="btn" value="检测频道类型" type="button" onclick="CheckChannelType()" />
            </td>
        </tr>
        <tr>
            <td class="spe_line">
                <div id="left_tree">
                    <ul>
                        <icms id="channel_tree" type="list">
                            <item>
                                <![CDATA[
                                <li id="{f_ChannelId}" class="channel_item" idvalue="{f_Rank}" title="{f_ParentId}">
                                    <input id="pop_{f_ChannelId}" name="pop_cid" value="{f_ChannelId}" type="radio">
                                    <span style="line-height:20px;font-size:14px;" class="" title="{f_ChannelName}[{f_ChannelId}]"><label for="pop_{f_ChannelId}">{f_ChannelName}</label></span>
                                </li>
                                ]]>
                            </item>
                        </icms>
                    </ul>
                </div>
                <div>
                    <ul id="sorted_tree">

                    </ul>
                </div>
            </td>
        </tr>
    </table>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2" height="30" align="center">
                <input class="btn" value="确 认" type="button" onclick="sub()" /> <input class="btn btn_cancel" value="取 消" type="button" />
            </td>
        </tr>
    </table>
</form>
</body>
</html>
