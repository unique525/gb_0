<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {

            $("#btn_select_all").click(function(event) {
                event.preventDefault();
                var inputSelect = $("[name='input_select']");
                if (inputSelect.prop("checked")) {
                    inputSelect.prop("checked", false);//取消全选
                } else {
                    inputSelect.prop("checked", true);//全选
                }
            });

            $("#btn_create").click(function (event) {
                event.preventDefault();
                //parent.G_TabUrl = '/default.php?secu=manage&mod=newspaper&m=create';
                //parent.G_TabTitle =  '新增';
                //parent.addTab();
            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var newspaperArticleId=$(this).attr("idvalue");
                var newspaperArticleTitle=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=newspaper_article&m=modify' + '&newspaper_article_id=' + newspaperArticleId + '';
                parent.G_TabTitle = newspaperArticleTitle;
                parent.addTab();
            });

            $(".btn_pic_list").click(function (event) {
                event.preventDefault();
                var newspaperArticleId=$(this).attr("idvalue");
                var newspaperArticleTitle=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=newspaper_article_pic&&m=list&newspaper_article_id='+newspaperArticleId+'';
                parent.G_TabTitle = newspaperArticleTitle;
                parent.addTab();
            });
            /**
             * 复制
             * **/
            $("#btn_copy").click(function (event) {
                event.preventDefault();
                var channelId=$(this).attr("idvalue");
                var docIdString = "";
                var w = 500;
                var h = $(window).height() - 100;

                $('input[name=input_select]').each(function (i) {
                    if (this.checked) {
                        docIdString = docIdString + ',' + $(this).val();
                    }
                });

                docIdString=docIdString.substr(1);
                if (docIdString.length <= 0) {
                    alert("请先选择要操作的文档");
                } else {

                    var url='/default.php?secu=manage&mod=newspaper_article&m=copy&channel_id='+channelId+'&doc_id_string='+docIdString;
                    $("#dialog_frame").attr("src",url);
                    $("#dialog_resultbox").dialog({
                        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                        autoOpen:true,
                        height:w,
                        width:h,
                        modal:true, //蒙层（弹出会影响页面大小）
                        title:'复制',
                        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                    });
                }
            });

            //格式化站点状态
            $(".span_state").each(function(){
                $(this).html(formatState(parseInt($(this).text())));
            });
            //开启
            $(".img_open").click(function(){
                var newspaperArticleId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(newspaperArticleId, state);
            });
            //停用
            $(".img_close").click(function(){
                var newspaperArticleId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(newspaperArticleId, state);
            });

            //拖动排序变化
            var sortGrid = $("#sort_grid");
            sortGrid.sortable();
            sortGrid.bind("sortstop", function(event, ui) {
                var sortList = $("#sort_grid").sortable("serialize");
                $.post("/default.php?secu=manage&mod=newspaper_article&m=async_modify_sort_by_drag&" + sortList, {
                    resultbox: $(this).html()
                }, function() {
                    window.location.href = window.location.href;
                });
            });
            sortGrid.disableSelection();
        });



        function modifyState(newspaperArticleId,state){
            if(newspaperArticleId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=newspaper_article&m=async_modify_state",
                    data: {
                        newspaper_article_id: newspaperArticleId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+newspaperArticleId).html(formatState(state));
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
                case 0:
                    return "启用";
                    break;
                case 100:
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
<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src="" style="border: 0; " width="100%" height="500px"></iframe>
    </div>
</div>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <a class="btn2" style="padding:3px 10px" href="/default.php?secu=manage&mod=newspaper_page&m=list&newspaper_id={NewspaperId}" idvalue="{NewspaperId}" title="返回版面管理">返回版面管理</a>
                <a id="btn_copy" class="btn2" style="padding:3px 10px;cursor: pointer" idvalue="{ChannelId}" title="复制本频道文档至其它频道，请先在下面文档中勾选需要复制的文档" >复制</a>
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
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td style="width:80px;text-align:left;padding:0 10px 0 10px">相关管理</td>
            <td style="width: 120px;text-align:center;">版面名称</td>
            <td style="width: 60px;text-align:center;">版面编号</td>
            <td>标题</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 80px; text-align: center;">点击数</td>
            <td style="width: 80px; text-align: center;">评论数</td>
            <td style="width: 100px;text-align:center;">排序号</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="newspaper_article_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_NewspaperArticleId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_NewspaperArticleId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" title="{f_NewspaperArticleId}" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_NewspaperArticleId}" alt="编辑"/></td>

                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_NewspaperArticleId}" class="span_state" idvalue="{f_NewspaperArticleId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open" idvalue="{f_NewspaperArticleId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close" idvalue="{f_NewspaperArticleId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></td>

                            <td class="spe_line2" style="width:80px;text-align:left;padding:0 10px 0 10px">
                                <a class="btn_pic_list" style="cursor: pointer" idvalue="{f_NewspaperArticleId}" title="图片管理">图片管理</a>
                            </td>
                            <td class="spe_line2" style="width:120px;text-align:center;" title="">{f_NewspaperPageName}</td>
                            <td class="spe_line2" style="width:60px;text-align:center;" title="">{f_NewspaperPageNo}</td>
                            <td class="spe_line2"><a target="_blank" href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">{f_NewspaperArticleTitle}</a></td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:80px;text-align:center;" title="">{f_HitCount}</td>
                            <td class="spe_line2" style="width:80px;text-align:center;" title="">{f_CommentCount}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="排序号">{f_Sort}</td>
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
