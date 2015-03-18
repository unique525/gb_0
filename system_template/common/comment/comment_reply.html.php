<!DOCTYPE html>
<html>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
<link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet"/>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(function () {

            $("#btnSubmit").click(function () {
                var commentContent = $("#commentContent");
                if (commentContent.val() == '') {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入回复内容");
                } else {
                    $("#mainForm").attr("action", "/default.php?mod=comment&m=reply");
                    $('#mainForm').submit();
                }
            });

        });

    </script>

</head>
<body>
<div id="dialog_box" title="提示" style="display:none;">
    <div id="dialog_content">
    </div>
</div>
<div>
<form id="mainForm" enctype="multipart/form-data" method="post">
    <table>
        <tr>
            <td>评论回复：</td>
            <td>
                <textarea id="commentContent" name="commentContent"
                          style="height: 250px;width: 350px;margin: 10 10 30 10;"></textarea>
                <input type="hidden" name="commentId" value="{commentId}">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><span class="btn" id="btnSubmit">提 交</span></td>
        </tr>
    </table>
</form>
</div>
</body>
</html>