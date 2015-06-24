<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        /**
         * 发布自定义页面详细页 返回值 自定义页面id小于0
         */
        window.PUBLISH_SITE_CONTENT_RESULT_SITE_CONTENT_ID_ERROR = -130211;
        /**
         * 发布自定义页面详细页 返回值 频道id小于0
         */
        window.PUBLISH_SITE_CONTENT_RESULT_CHANNEL_ID_ERROR = -130212;
        /**
         * 发布自定义页面详细页 返回值 状态不正确，必须为启用状态的文档才能发布
         */
        window.PUBLISH_SITE_CONTENT_RESULT_STATE_ERROR = -130213;
        /**
         * 发布自定义页面详细页 返回值 操作完成，结果存储于结果数组中
         */
        window.PUBLISH_SITE_CONTENT_RESULT_FINISHED = 130211;


        $("document").ready(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                window.location.href = '/default.php?secu=manage' +
                    '&mod=site_content' +
                    '&m=create' +
                    '&channel_id='+parent.G_SelectedChannelId+'';
            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var siteContentId=$(this).attr("idvalue");
                var siteContentTitle=$(this).attr("title");
                window.location.href = '/default.php?secu=manage&mod=site_content&m=modify' + '&site_content_id=' + siteContentId + '';
            });


            var btnPublish = $(".btn_publish");
            btnPublish.css("cursor", "pointer");
            btnPublish.click(function(event) {
                var siteContentId = $(this).attr('idvalue');
                event.preventDefault();

                var dialogBox = $("#dialog_box");
                dialogBox.attr("title","发布文档");
                dialogBox.dialog({
                    height: 140,
                    modal: true
                });

                var dialogContent = $("#dialog_content");
                dialogContent.html("开始发布");

                $.post("/default.php?secu=manage&mod=site_content&m=async_publish&site_content_id=" + siteContentId + "", {
                    resultbox: $(this).html()
                }, function(result) {
                    dialogContent.html('<img src="/system_template/common/images/spinner2.gif" /> 正在发布...');
                    if (parseInt(result) == window.PUBLISH_SITE_CONTENT_RESULT_SITE_CONTENT_ID_ERROR) {
                        dialogContent.html('发布错误：id小于0');
                    }else if (parseInt(result) == window.PUBLISH_SITE_CONTENT_RESULT_CHANNEL_ID_ERROR) {
                        dialogContent.html('发布错误：频道id小于0');
                    }else if (parseInt(result) == window.PUBLISH_SITE_CONTENT_RESULT_STATE_ERROR) {
                        dialogContent.html('发布错误：状态不正确，必须为[已审]或[已发]状态的文档才能发布!');
                    }else{
                        dialogContent.html("发布完成<br />"+result);
                        var spanState = $("#span_state_" + siteContentId);
                        spanState.html("<"+"span style='color:#006600'>已发<"+"/span>");
                        //window.location.href = window.location.href;
                    }
                });
            });


            //格式化站点状态
            $(".span_state").each(function(){
                $(this).html(formatSiteContentState($(this).text()));
            });

            $(".link_view").each(function(){
                var siteContentId = $(this).attr("idvalue");
                var publishDate = $(this).attr("pub_date");
                if(publishDate.length>0){
                    $(this).attr("href","/h/{ChannelId}/"+siteContentId+".html");
                }
            });

            //开启站点
            $(".img_open").click(function(){
                var siteContentId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(siteContentId, state);
            });
            //停用站点
            $(".img_close").click(function(){
                var siteContentId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(siteContentId, state);
            });
        });



        function modifyState(siteContentId,state){
            if(siteContentId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=site_content&m=async_modify_state",
                    data: {
                        site_id: siteContentId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+siteContentId).html(formatSiteContentState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatSiteContentState(state){
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "30":
                    return "<"+"span style='color:#006600'>已发<"+"/span>";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
</head>
<body>
<div id="dialog_box" title="" style="display:none;">
    <div id="dialog_content">

    </div>
</div>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建自定义页面" title="新建自定义页面" type="button"/>
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
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 30px; text-align: left;"></td>
            <td>[ID]标题</td>
            <td style="width: 70px; text-align: center;">排序号</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 150px;text-align:center;">创建人</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="site_content_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_SiteContentId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_SiteContentId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" title="{f_SiteContentTitle}编辑" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_SiteContentId}" alt="编辑"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_SiteContentId}" class="span_state" idvalue="{f_SiteContentId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:30px;text-align:left;"><img class="btn_publish" src="/system_template/{template_name}/images/manage/publish.gif" idvalue="{f_SiteContentId}" title="发布文档" alt="发布"/></td>
                            <td class="spe_line2"><a target="_blank" href="" class="link_view" idvalue="{f_SiteContentId}" pub_date="{f_year}{f_month}{f_day}">[{f_SiteContentId}] {f_SiteContentTitle}</a></td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:150px;text-align:center;" title="创建人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:80px;text-align:center;"><img class="img_open" idvalue="{f_SiteContentId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;<img class="img_close" idvalue="{f_SiteContentId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></td>
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
