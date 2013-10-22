<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link id="css_font" type="text/css" href="/system_template/{templatename}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/manage/default.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
        <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $(document).tooltip();
                $("#selectall").click(function(event) {
                    event.preventDefault();
                    //alert($("[name='docinput']").prop("checked"));
                    if ($("[name='docinput']").prop("checked")) {
                        $("[name='docinput']").prop("checked", false);//取消全选
                    } else {
                        $("[name='docinput']").prop("checked", true);//全选
                    }
                });

                $(".formatstate").each(function(i, n) {
                    var state = parseInt($(this).text());
                    switch (state) {
                        case 0:
                            $(this).text("正常");
                            break;
                        case 1:
                            $(this).text("禁止访问");
                            break;
                        case 2:
                            $(this).text("暂时关闭");
                            break;
                        case 3:
                            $(this).text("按用户加密");
                            break;
                        case 4:
                            $(this).text("按身份加密");
                            break;
                        case 5:
                            $(this).text("按发帖加密");
                            break;
                        case 6:
                            $(this).text("按积分加密");
                            break;
                        case 7:
                            $(this).text("按金钱加密");
                            break;
                        case 8:
                            $(this).text("按魅力加密");
                            break;
                        case 9:
                            $(this).text("按经验加密");
                            break;
                        case 10:
                            $(this).text("禁止发帖");
                            break;
                        case 100:
                            $(this).text("已删除");
                            break;
                        default:
                            $(this).text("未知状态");
                            break;
                    }
                });

                $(".edit_doc").css("cursor", "pointer");
                $(".edit_doc").click(function(event) {
                    var docid = $(this).attr('idvalue');
                    event.preventDefault();
                    var pageIndex = parseInt(Request["p"]);
                    if (pageIndex <= 0) {
                        pageIndex = 1;
                    }
                    parent.G_TabUrl = '/default.php?secu=manage&mod=documentnews&m=modify&documentnewsid=' + docid + '&p=' + pageIndex + '&cid=' + parent.G_SelectedDocumentChannelId;
                    parent.G_TabTitle = parent.G_SelectedDocumentChannelName + '-编辑文档';
                    parent.addTab();
                });

                //改变状态按钮事件捕获
                $(".imgchangestate").click(function(event) {
                    var docid = $(this).attr('idvalue');
                    event.preventDefault();
                    ShowBox('divstate_' + docid);
                });
                $(".span_closebox").click(function(event) {
                    var docid = $(this).attr('idvalue');
                    event.preventDefault();
                    document.getElementById('divstate_' + docid).style.display = "none";
                });


                //排序变化
                $("#sortgrid").sortable();
                $("#sortgrid").bind("sortstop", function(event, ui) {
                    var sortlist = $("#sortgrid").sortable("serialize");
                    $.post("/default.php?secu=manage&mod=documentnews&m=async_updatesort&" + sortlist, {
                        resultbox: $(this).html()
                    }, function() {
                        //操作完成后触发的命令
                    });

                });
                $("#sortgrid").disableSelection();

                //选中时的样式变化
                $('.griditem').click(function() {
                    if ($(this).hasClass('docselected')) {
                        $(this).removeClass('docselected');
                    } else {
                        $(this).addClass('docselected');
                    }
                });
                $(".docnewssetstate").click(function() {
                    var docid = $(this).attr('idvalue');
                    var state = $(this).attr('statevalue');
                    DocumentNewsChangeState(docid, state);
                });
            });
        </script>   
    </head>
    <body>
        <div id="rightbtns">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="td_main_btn">
                        <input id="btnaddone" class="btn7" value="新建版块分区" title="新建版块分区，版块分区下需要建立二级版块才能正常使用" type="button" />
                    </td>

                </tr>
            </table>
        </div>

        <div id="doclist">
            <table class="docgrid" cellpadding="0" cellspacing="0">
                <tr class="gridtitle">
                    <td style="width: 30px; text-align: center; cursor: pointer;" id="selectall">全</td>
                    <td style="width: 40px; text-align: center;">编辑</td>
                    <td style="width: 90px; text-align: center;">状态</td>
                    <td style="width: 40px;"></td>
                    <td>名称</td>
                    <td style="width: 50px; text-align: center;">排序</td>
                    <td style="width: 120px;">版主</td>
                </tr>
            </table>
            <ul id="sortgrid" style="list-style: none;">
                {ForumList}
            </ul>
        </div>
    </body>
</html>
