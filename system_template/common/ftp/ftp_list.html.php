<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

    <script type="text/javascript">

        $().ready(function(){
            var siteId = Request["site_id"];
            var siteName = Request["site_name"];
            siteName=decodeURI(siteName);
            $("#btn_select_all").click(function (event) {
                event.preventDefault();
                var inputSelect = $("[name='doc_input']");
                if (inputSelect.prop("checked")) {
                    inputSelect.prop("checked", false);//取消全选
                } else {
                    inputSelect.prop("checked", true);//全选
                }
            });


            $("#btn_search").click(function (event) {
                event.preventDefault();
                var searchKey = $("#search_key").val();
                parent.G_TabUrl = '/default.php?secu=manage&mod=ftp&m=list' + '&site_id=' + siteId + '&search_key=' + searchKey;
                parent.G_TabTitle = siteName + '-ftp搜索';
                parent.addTab();
            });


            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=ftp&m=create' + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-新建ftp';
                parent.addTab();
            });


            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var ftpId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=ftp&m=modify&ftp_id=' + ftpId + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-编辑ftp';
                parent.addTab();
            });

            $(".span_site_name").html(siteName);

            $(".span_mode").each(function () {
                $(this).html(FormatPasvMode($(this).attr("title")));
            });

        });


        /**
         * 格式化类型
         * @param pasvMode 过滤类型
         * @return string
         */
        function FormatPasvMode(pasvMode){
            switch (pasvMode){
                case "0":
                    return "非静态模式";
                    break;
                case "1":
                    return "静态模式";
                    break;
                default :
                    return "未知";
                    break;
            }
        }


        /**
         * 删除
         * @param idvalue 业务id
         * @return
         */
        function DeleteFtp(idvalue) {
            $.ajax({
                url:"/default.php?secu=manage&mod=ftp&m=delete",
                data:{table_id:idvalue},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    if (parseInt(data["result"]) > 0) {
                        location.reload();
                    }
                    else alert("失败，请联系管理员");
                }
            });
        }
    </script>

</head>
<body>
{common_body_deal}

<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新建ftp" title="在本站点新建ftp" type="button"/>
            </td>
            <td id="td_main_btn" align="right" style="display: none">
                <div id="search_box">
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

    <table class="grid" width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:40px;text-align:center;">ID</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:80px;text-align:center;">站点</td>
            <td style="width:140px;text-align:center;">服务器</td>
            <td style="width:90px;text-align:center;">类型</td>
            <td style="width:130px;text-align:center;">账号</td>
            <td style="width:auto;text-align:center;">路径</td>
            <td style="width:90px;text-align:center;">测试连接</td>
            <td style="width:60px;text-align:center;">删除</td>
        </tr>
        <icms id="ftp" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="text-align:center;">{f_FtpId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_FtpId}"
                                                                          idvalue="{f_FtpId}"/></td>

                    <td class="spe_line2" style="text-align:center;"><span class="span_site_name" title="{f_SiteId}" id="site_name_{f_FtpId}"></span></td>
                    <td class="spe_line2" style="width:140px;text-align:center;"><span title="{f_FtpId}">{f_FtpHost}</span></td>
                    <td class="spe_line2" style="width:90px;text-align:center;"><span class="span_mode" title="{f_PasvMode}" id="mode_{f_PasvMode}"></span></td>
                    <td style="text-align:center;" class="spe_line2">{f_FtpUser}</td>
                    <td style="text-align:center;" class="spe_line2">{f_RemotePath}</td>
                    <td style="text-align:center;" class="spe_line2"><span class="span_test" title="{f_FtpId}" >测试连接</span></td>
                    <td style="text-align:center;" class="spe_line2"><span class="span_delete" title="{f_FtpId}"><img class="pic_manage"
                                                                                                       style="cursor: pointer"
                                                                                                       src="/system_template/default/images/manage/delete.jpg"
                                                                                                       alt="删除" title="{f_FtpId}"
                                                                                                       onclick="DeleteFtp({f_FtpId})"/></span></td>
                </tr>
                ]]></item></icms>
    </table>
    {PagerButton}
</div>
</body>
</html>

