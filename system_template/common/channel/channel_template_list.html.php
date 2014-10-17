<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $("document").ready(function () {
            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=channel_template' +
                    '&m=create&channel_id={ChannelId}';
                parent.G_TabTitle =  '新增模板';
                parent.addTab();
            });


            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var channelTemplateId=$(this).attr("idvalue");
                var channelTemplateName=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=channel_template&m=modify' + '&channel_template_id=' + channelTemplateId + '';
                parent.G_TabTitle = channelTemplateName+'-模板编辑';
                parent.addTab();
            });

            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatChannelTemplateState($(this).text()));
            });

            //格式化模板类型
            $(".span_channel_template_type").each(function(){
                $(this).html(formatChannelTemplateType($(this).text()));
            });

            //格式化发布模式
            $(".span_publish_type").each(function(){
                $(this).html(formatPublishType($(this).text()));
            });

            //格式化附件
            $(".span_attachment_length").each(function(){
                if($(this).text().length>0){
                    $(this).html('<img src="/system_template/{template_name}/images/manage/zip.jpg" />');
                }else{
                    $(this).html('无');
                }
            });


            //开启站点
            $(".img_open").click(function(){
                var channelTemplateId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(channelTemplateId, state);
            });
            //停用站点
            $(".img_close").click(function(){
                var channelTemplateId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(channelTemplateId, state);
            });
        });



        function modifyState(channelTemplateId,state){
            if(channelTemplateId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=channel_template&m=async_modify_state",
                    data: {
                        channel_template_id: channelTemplateId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+channelTemplateId).html(formatChannelTemplateState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatChannelTemplateState(state){
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        /**
         * 格式化模板类型
         * @return {string}
         */
        function formatChannelTemplateType(channelTemplateType){
            switch (channelTemplateType){
                case "0":
                    return "普通";
                    break;
                case "1":
                    return "动态";
                    break;
                default :
                    return "未知";
                    break;
            }
        }

        /**
         * 格式化发布模式
         * @param {int} publishType
         * @returns {string}
         */
        function formatPublishType(publishType){
            switch (publishType){
                case "0":
                    return "<span title='联动发布，只发布在本频道下'>联动1</span>";
                    break;
                case "1":
                    return "<span title='联动发布，只发布在触发频道下，有可能是本频道，也有可能是继承频道'>联动2</span>";
                    break;
                case "2":
                    return "<span title='联动发布，发布在所有继承树关系的频道下'>联动3</span>";
                    break;
                case "10":
                    return "<span title='非联动发布，只发布在本频道下'>非联动</span>";
                    break;
                case "20":
                    return "不发布";
                    break;
                case "30":
                    return "<span title='资讯详细页模板'>资讯详细</span>";
                    break;
                case "31":
                    return "<span title='活动详细页模板'>活动详细</span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建模板" title="新建模板" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td>模板名称</td>
            <td style="width: 200px; text-align: center;">发布文件名</td>
            <td style="width: 40px; text-align: center;">附件</td>
            <td style="width: 100px; text-align: center;">发布模式</td>
            <td style="width: 80px; text-align: center;">模板类型</td>
            <td style="width: 200px; text-align: center;">模板标签</td>
            <td style="width: 160px;text-align:center;">创建时间</td>
            <td style="width: 150px;text-align:center;">创建人</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="channel_template_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_ChannelTemplateId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <label>
                                    <input class="input_select" type="checkbox" name="input_select"
                                           value="{f_ChannelTemplateId}"/>
                                </label>
                            </td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="btn_modify" style="cursor:pointer;" title="{f_ChannelTemplateName}" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ChannelTemplateId}" alt="编辑"/>
                            </td>
                            <td class="spe_line2">
                                {f_ChannelTemplateName}
                            </td>
                            <td class="spe_line2" style="width:200px;text-align:center;" title="发布文件名">{f_PublishFileName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;" title="是否带有附件">
                                <span class="span_attachment_length">{f_AttachmentLength}</span>
                            </td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="发布模式">
                                <span class="span_publish_type">{f_PublishType}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align:center;" title="模板类型">
                                <span class="span_channel_template_type">{f_ChannelTemplateType}</span>
                            </td>
                            <td class="spe_line2" style="width:200px;text-align:center;" title="模板标签">
                                <span class="span_channel_template_tag">{f_ChannelTemplateTag}</span>
                            </td>
                            <td class="spe_line2" style="width:160px;text-align:center;" title="创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:150px;text-align:center;" title="创建人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_ChannelTemplateId}" class="span_state" idvalue="{f_ChannelTemplateId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open" idvalue="{f_ChannelTemplateId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close" idvalue="{f_ChannelTemplateId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
