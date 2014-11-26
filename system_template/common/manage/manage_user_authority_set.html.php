<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript">

        $(function () {
            $('#tabs').tabs();


        });

        function submitForm(closeTab) {

            if (closeTab == 1) {
                $("#CloseTab").val("1");
            } else {
                $("#CloseTab").val("0");
            }

            $("#mainForm").attr("action", "/default.php?secu=manage&mod=manage_user_authority&m=set_by_manage_user&tab_index=" + parent.G_TabIndex + "");
            $('#mainForm').submit();

        }

    </script>
</head>
<body>
<div class="div_deal">
    <form id="mainForm" enctype="multipart/form-data" method="post">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">站点权限</a></li>
                            <li><a href="#tabs-2">频道权限</a></li>
                            <li><a href="#tabs-3">会员权限</a></li>
                            <li><a href="#tabs-4">系统权限</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div style="">
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageSite">管理站点：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageSite" name="auth_ManageSite" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageComment">管理评论：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageComment" name="auth_ManageComment" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageTemplateLibrary">管理模板：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageTemplateLibrary" name="auth_ManageTemplateLibrary" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageFilter">管理过滤：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageFilter" name="auth_ManageFilter" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageFtp">管理FTP：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageFtp" name="auth_ManageFtp" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageAd">管理广告：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageAd" name="auth_ManageAd" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageDocumentTag">管理文档标签：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageDocumentTag" name="auth_ManageDocumentTag" type="checkbox" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="auth_ManageConfig">管理配置：</label></td>
                                        <td class="spe_line">
                                            <input id="auth_ManageConfig" name="auth_ManageConfig" type="checkbox" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-2">
                            <div>

                            </div>
                        </div>

                        <div id="tabs-3">
                            <div>

                            </div>
                        </div>

                        <div id="tabs-4">
                            <div>

                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
