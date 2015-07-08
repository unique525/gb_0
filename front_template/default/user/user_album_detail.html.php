<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>胜利花 图片上传</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="keywords" content=""/>
    <meta name="description" content="{cfg_ForumIeDescription}"/>
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS"/>
    <meta name="author" content="{cfg_MetaAuthor}"/>
    <meta name="copyright" content="{cfg_MetaCopyright}"/>
    <meta name="application-name" content="{cfg_MetaApplicationName}"/>
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>

    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common_m.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_m.css" rel="stylesheet"/>
    <script type="text/javascript">


        $(function () {
            var userAlbumIntro = $("#f_UserAlbumIntro");
            userAlbumIntro.focus(function () {
                if (userAlbumIntro.val() == '感言（必填）') {
                    userAlbumIntro.val("");
                }
            });
            var btnConfirm = $("#btnConfirm");
            btnConfirm.click(function () {
                if ($("#f_UserAlbumIntro").val() == "") {
                    alert("请输入感言");
                    return;
                }
                if ($("#file_upload_to_content_of_wap").val() == "") {
                    alert("请选择上传图片");
                    return;
                }

                $("#mainForm").attr("action",
                    "/default.php?mod=user_album&a=create");
                $('#mainForm').submit();

            });

        });

        function fileSelect(e) {
            if (window.File && window.FileList && window.FileReader && window.Blob) {


                e = e || window.event;

                var files = e.target.files;  //FileList Objects

                var p = document.getElementById('Preview');

                p.innerText = "";// 重新选择时，清空

                var ul = document.getElementById('Errors');
                for (var i = 0, f; f = files[i]; i++) {
                    if (!f.type.match('image.*')) continue;

                    var reader = new FileReader();

                    reader.onload = (function (file) {
                        return function (e) {
                            var span = document.createElement('span');
                            span.innerHTML = '<img class="thumb" src="' + this.result + '" alt="' + file.name + '" />';

                            p.insertBefore(span, null);
                        };
                    })(f);
                    //读取文件内容
                    reader.readAsDataURL(f);
                }
            }
        }

    </script>
    <style>
        .thumb {
            max-width: 75px;
            max-height: 75px;
            margin: 10px;
        }
    </style>
</head>
<body>
<form id="mainForm" enctype="multipart/form-data" method="post">
    <div id="dialog_box" title="提示" style="display:none;">
        <div id="dialog_content">
        </div>
    </div>
    <div id="forum_topic">
        <div class="content">
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <input type="file" id="file_upload_to_content_of_wap" name="file_upload_to_content_of_wap[]"
                               onchange="fileSelect()" multiple/>

                        <div id="Preview"></div>
                    </td>
                </tr>
                <tr>
                    <td style="height:36px;text-align:center;">
                        <text_area id="f_UserAlbumIntro" class="input_box_m" name="f_UserAlbumIntro"
                                   style=" width: 95%;height:150px;">感言（必填）</text_area>
                        <input type="hidden" value="/h/100013/end.html" id="goUrl" name="goUrl" />
                    </td>
                </tr>
            </table>
            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="60" align="center">
                        <input id="btnConfirm" class="btn" value="确 认" type="button"/>
                        <input id="btnCancel" class="btn" value="取 消" type="button"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>