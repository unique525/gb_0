<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                //parent.G_TabUrl = '/default.php?secu=manage&mod=newspaper&m=create';
                //parent.G_TabTitle =  '新增';
                //parent.addTab();
            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var newspaperId=$(this).attr("idvalue");
                var newspaperTitle=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=newspaper&m=modify' + '&newspaper_id=' + newspaperId + '';
                parent.G_TabTitle = newspaperTitle;
                parent.addTab();
            });



            //格式化站点状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            //开启站点
            $(".img_open").click(function(){
                var newspaperId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(newspaperId, state);
            });
            //停用站点
            $(".img_close").click(function(){
                var newspaperId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(newspaperId, state);
            });
        });



        function modifyState(newspaperId,state){
            if(newspaperId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=newspaper&m=async_modify_state",
                    data: {
                        newspaper_id: newspaperId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+newspaperId).html(formatState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatState(state){
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
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_manage_all_newspaper_article" style="display:none;" class="btn2" value="全部文章管理" title="全部文章管理" type="button"/>
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
            <td>名称</td>
            <td style="width: 180px;text-align:center;">发布时间</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td style="width:80px;text-align:left;padding:0 10px 0 10px">相关管理</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="newspaper_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_NewspaperId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_NewspaperId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" title="{f_NewspaperId}编辑" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_NewspaperId}" alt="编辑"/></td>
                            <td class="spe_line2"><a target="_blank" href="/default.php?mod=newspaper&a=gen_one&channel_id={ChannelId}&publish_date={f_PublishDate}">{f_NewspaperTitle}</a></td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="发布时间">{f_PublishDate}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_NewspaperId}" class="span_state" idvalue="{f_NewspaperId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open" idvalue="{f_NewspaperId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close" idvalue="{f_NewspaperId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></td>

                            <td class="spe_line2" style="width:80px;text-align:left;padding:0 10px 0 10px">
                                <a href="/default.php?secu=manage&mod=newspaper_page&&m=list&newspaper_id={f_NewspaperId}">版面管理</a>
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
