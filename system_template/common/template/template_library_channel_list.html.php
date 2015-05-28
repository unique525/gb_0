<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">

            $(function(){
                $("#btn_create").click(function(){
                    window.location.href = '/default.php?secu=manage' +
                        '&mod=template_library_channel&m=create&tab_index='+ parent.G_TabIndex +'&template_library_id={TemplateLibraryId}';
                });

                $(".span_channel_type").each(function () {
                    $(this).html(FormatChannelType($(this).attr("idvalue")));
                });

                $(".template_manage[idvalue='0']").each(function(){  //idvalue=模板数
                    $(this).html("暂无模板");
                    $(this).css("color","#D8D8D8");
                });

                SortTree(0);


            });


            function Modify(Id){
                window.location.href = '/default.php?secu=manage&mod=template_library_channel&m=modify&tab_index='+ parent.G_TabIndex +'&template_library_channel_id='+Id+'';
            }

            function TemplateManage(templateLibraryChannelId){
                parent.G_TabUrl = '/default.php?secu=manage&mod=template_library_content&m=content&template_library_channel_id='+templateLibraryChannelId;
                parent.G_TabTitle = '库模板管理';
                parent.addTab();
            }


            function Delete(templateLibraryChannelId){
                if(confirm("删除后子频道，模板都会丢失，确认删除？")){
                    $.ajax({
                        url:"/default.php?secu=manage&mod=template_library_channel&m=delete&template_library_channel_id="+templateLibraryChannelId,
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function (data) {
                            alert(1);
                                location.reload();
                        }
                    });
                }
            }


            /**
             * 去的频道类型
             * @param channelTypeId 显示类型
             * @return string
             */
            function FormatChannelType(channelTypeId){
                switch (channelTypeId){
                    case "1":
                        return "新闻资讯";
                        break;
                    case "2":
                        return "咨询答复";
                        break;
                    case "3":
                        return "图片轮换";
                        break;
                    case "4":
                        return "产品";
                        break;
                    case "5":
                        return "频道结合产品";
                        break;
                    case "6":
                        return "活动";
                        break;
                    case "7":
                        return "在线调查";
                        break;
                    case "8":
                        return "自定义页面";
                        break;
                    case "9":
                        return "友情链接";
                        break;
                    case "10":
                        return "活动表单";
                        break;
                    case "11":
                        return "文字直播";
                        break;
                    case "12":
                        return "投票";
                        break;
                    case "13":
                        return "在线测试";
                        break;
                    case "14":
                        return "分类信息";
                        break;
                    case "15":
                        return "电子报";
                        break;
                    case "50":
                        return "外部接口";
                        break;
                    case "0":
                        return "站点首页";
                        break;
                    default :
                        return "未知";
                        break;
                }
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
                        itemContent='<tr class="grid_item item_rank_'+rank+'" id="sorted_'+items[i].getAttribute("id")+'" title="'+parentId+'_last" idvalue="'+items[i].getAttribute("id")+'" style="background-color: rgb('+(255-rank*11)+','+(255-rank*11)+','+(255-rank*11)+')">'+items[i].innerHTML+'</tr>';
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
           
    </head>
    <body>


        <div class="div_list">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="td_main_btn" width="83">
                        <input id="btn_create" class="btn2" value="新增频道" title="" type="button"/>
                    </td>
                    <td id="td_main_btn" align="right">
                        <div id="search_box" style="display: none">
                            <label for="search_key"></label><input id="search_key" name="search_key" class="input_box"
                                                                   type="text">
                            <input id="btn_search" class="btn2" value="查 询" type="button">
                            <span id="search_type" style="display: none"></span>
                            <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的文档" type="button"
                                   style="display: none">
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="grid" cellpadding="0" cellspacing="0" id="left_tree">
                <tr class="grid_title">
                    <td class="spe_line" style="width:40px;text-align:center">ID</td>
                    <td class="spe_line" style="width:80px;text-align:center;">父频道id</td>
                    <td class="spe_line" style="width:40px;text-align:center;">编辑</td>
                    <td class="spe_line">频道名称</td>
                    <td class="spe_line" style="width:80px;text-align:center;">频道类型</td>
                    <td class="spe_line" style="width:80px;text-align:center;">管理模板</td>
                    <td class="spe_line" style="width:40px;text-align:center;">删除</td>
                </tr>
                <icms id="template_library_channel_list" type="list">
                    <item><![CDATA[
                        <tr class="grid_item item_rank_{f_Rank} channel_item" id="{f_TemplateLibraryChannelId}" idvalue="{f_Rank}" title="{f_ParentId}">
                            <td class="spe_line" style="text-align:center">{f_TemplateLibraryChannelId}</td>
                            <td class="spe_line" idvalue="{f_ParentId}" style="width:80px;text-align:center;">{f_ParentId}</td>
                            <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                                  class="btn_edit"
                                                                                  src="/system_template/default/images/manage/edit.gif"
                                                                                  alt="编辑" title="{f_TemplateLibraryChannelId}"
                                                                                  idvalue="{f_TemplateLibraryChannelId}"
                                                                                  onclick="Modify('{f_TemplateLibraryChannelId}')"/></td>
                            <td class="spe_line">{f_ChannelName}</td>
                            <td class="spe_line span_channel_type" idvalue="{f_ChannelType}" style="width:80px;text-align:center;"></td>
                            <td class="spe_line template_manage" idvalue="{f_TemplateCount}" style="width:80px;text-align:center;cursor: pointer" onclick="TemplateManage({f_TemplateLibraryChannelId})">管理模板</td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="delete" idvalue="{f_TemplateLibraryChannelId}" src="/system_template/{template_name}/images/manage/delete.jpg" style="cursor:pointer" onclick="Delete({f_TemplateLibraryChannelId})"/>
                            </td>
                        </tr>
                        ]]>
                    </item>
                </icms>
            </table>
            <table width="100%" class="grid" cellpadding="0" cellspacing="0" id="sorted_tree">
                <tr class="grid_title">
                    <td class="spe_line" style="width:40px;text-align:center">ID</td>
                    <td class="spe_line" style="width:80px;text-align:center;">父频道id</td>
                    <td class="spe_line" style="width:40px;text-align:center;">编辑</td>
                    <td class="spe_line">频道名称</td>
                    <td class="spe_line" style="width:80px;text-align:center;">频道类型</td>
                    <td class="spe_line" style="width:80px;text-align:center;">管理模板</td>
                    <td class="spe_line" style="width:40px;text-align:center;">删除</td>
                </tr>
            </table>
        </div>
    </body>
</html>