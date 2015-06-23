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
        var tableTypeOfUserAvatar = window.UPLOAD_TABLE_TYPE_USER_AVATAR;
        var tableId = parseInt("{SiteId}");

        window.AjaxFileUploadCallBack = function (fileElementId, data) {
            var uploadFileId = data.upload_file_id;
            var uploadFilePath = data.upload_file_path;

            if (fileElementId == "user_default_male_avatar") {
                $("#cfg_UserDefaultMaleAvatar").val(uploadFileId);
                $("#preview_UserDefaultMaleImage").attr("src", uploadFilePath);
            } else {
                $("#cfg_UserDefaultFemaleAvatar").val(uploadFileId);
                $("#preview_UserDefaultFemaleImage").attr("src", uploadFilePath);
            }
        }

        $(function () {
            $('#tabs').tabs();

            //站点默认男性用户头像
            var btnUploadToMaleAvatar = $("#btnUploadToMaleAvatar");
            btnUploadToMaleAvatar.click(function () {

                var fileElementId = 'user_default_male_avatar';
                var attachWatermark = 0;
                var loadingImageId = "loadingOfUserDefaultMaleAvatar";
                var uploadFileId = 0;

                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfUserAvatar,
                    tableId,
                    loadingImageId,
                    $(this),
                    attachWatermark,
                    uploadFileId
                );
            });

            //站点默认女性用户头像
            var btnUploadToFemaleAvatar = $("#btnUploadToFemaleAvatar");
            btnUploadToFemaleAvatar.click(function () {

                var fileElementId = 'user_default_female_avatar';
                var fUploadFile = null;
                var editor = null;
                var attachWatermark = 0;
                var loadingImageId = "loadingOfUserDefaultFemaleAvatar";
                var inputTextId = null;
                var previewImageId = null;
                var uploadFileId = 0;


                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfUserAvatar,
                    tableId,
                    loadingImageId,
                    $(this),
                    editor,
                    fUploadFile,
                    attachWatermark,
                    inputTextId,
                    previewImageId,
                    uploadFileId
                );
            });

        });

        function submitForm(closeTab) {

            if (closeTab == 1) {
                $("#CloseTab").val("1");
            } else {
                $("#CloseTab").val("0");
            }

            $("#mainForm").attr("action", "/default.php?secu=manage&mod=site_config&m=set&type=2&site_id={SiteId}&tab_index=" + parent.G_TabIndex + "");
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
                            <li><a href="#tabs-1">基本设置</a></li>
                            <li><a href="#tabs-2">注册相关</a></li>
                            <li><a href="#tabs-3">tabs-3</a></li>
                            <li><a href="#tabs-4">tabs-4</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div style="">
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserCount">会员总数：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserCount" id="cfg_UserCount" value="{cfg_UserCount}"
                                                   maxlength="200" type="text" class="input_number"
                                                   style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserNameLength">注册会员名的最大长度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserNameLength" id="cfg_UserNameLength"
                                                   value="{cfg_UserNameLength}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserDefaultState">注册会员默认状态：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserDefaultState" id="cfg_UserDefaultState"
                                                   value="{cfg_UserDefaultState}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员相册图片缩略图宽度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAlbumThumbWidth" id="cfg_UserAlbumThumbWidth"
                                                   value="{cfg_UserAlbumThumbWidth}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员相册图片缩略图高度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAlbumThumbHeight" id="cfg_UserAlbumThumbHeight"
                                                   value="{cfg_UserAlbumThumbHeight}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员大头像宽度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAvatarBigWidth" id="cfg_UserAvatarBigWidth"
                                                   value="{cfg_UserAvatarBigWidth}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员大头像高度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAvatarBigHeight" id="cfg_UserAvatarBigHeight"
                                                   value="{cfg_UserAvatarBigHeight}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员小头像宽度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAvatarSmallWidth" id="cfg_UserAvatarSmallWidth"
                                                   value="{cfg_UserAvatarSmallWidth}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumThumbWidth">会员小头像高度：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAvatarSmallHeight" id="cfg_UserAvatarSmallHeight"
                                                   value="{cfg_UserAvatarSmallHeight}" maxlength="200" type="text"
                                                   class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserAlbumToBestMustVoteCount">升级成精华册需要的支持票数：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserAlbumToBestMustVoteCount"
                                                   id="cfg_UserAlbumToBestMustVoteCount"
                                                   value="{cfg_UserAlbumToBestMustVoteCount}" maxlength="200"
                                                   type="text" class="input_number" style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserDefaultMaleAvatar">站点默认男性用户头像：</label></td>
                                        <td class="spe_line">
                                            <img id="preview_UserDefaultMaleImage"
                                                 src="{cfg_UserDefaultMaleAvatar_upload_file_path}"/><br/>
                                            <input id="user_default_male_avatar" name="user_default_male_avatar"
                                                   type="file" class="input_box"
                                                   style="width:200px; background: #ffffff;"/>
                                            <input id="cfg_UserDefaultMaleAvatar" name="cfg_UserDefaultMaleAvatar"
                                                   type="hidden" value="{cfg_UserDefaultMaleAvatar}"/>
                                            <img id="loadingOfUserDefaultMaleAvatar"
                                                 src="/system_template/common/images/loading1.gif"
                                                 style="display:none;"/>
                                            <input id="btnUploadToMaleAvatar" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserDefaultFemaleAvatar">站点默认女性用户头像：</label></td>
                                        <td class="spe_line">
                                            <img id="preview_UserDefaultFemaleImage"
                                                 src="{cfg_UserDefaultFemaleAvatar_upload_file_path}"/><br/>
                                            <input id="user_default_female_avatar" name="user_default_female_avatar"
                                                   type="file" class="input_box"
                                                   style="width:200px; background: #ffffff;"/>
                                            <input id="cfg_UserDefaultFemaleAvatar" name="cfg_UserDefaultFemaleAvatar"
                                                   type="hidden" value="{cfg_UserDefaultFemaleAvatar}"/>
                                            <img id="loadingOfUserDefaultFemaleAvatar"
                                                 src="/system_template/common/images/loading1.gif"
                                                 style="display:none;"/>
                                            <input id="btnUploadToFemaleAvatar" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-2">
                            <div>

                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserDefaultUserGroupIdForRole">注册后默认会员组：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_UserDefaultUserGroupIdForRole" id="cfg_UserDefaultUserGroupIdForRole" value="{cfg_UserDefaultUserGroupIdForRole}"
                                                   maxlength="200" type="text" class="input_number"
                                                   style=" width: 200px;"/>
                                        </td>
                                    </tr>
                                </table>
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
