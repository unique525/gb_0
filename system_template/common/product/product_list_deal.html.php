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
        });


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
    <table id="tab_doc_list" style="" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style=" height: 40px; ">
                <div style="font-size: 14px;font-weight: bold;">当前操作的产品如下</div>
                <div style=" width: 90%; height: 50px; overflow: auto; background: #f5f5f5; padding: 2px; border: solid 1px #cccccc;">
                    {DocumentList}
                </div>
            </td>
        </tr>
    </table>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" style=" height: 40px; font-size: 14px; font-weight: bold;" colspan="2">
                请选择您要{MethodName}到的频道
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
