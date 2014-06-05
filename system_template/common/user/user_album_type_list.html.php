<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        var addOpen = 0;
        $(function(){
            $("#add").click(function(){
                if(addOpen == 0){
                    addOpen = 1;
                    $("#type_list").append('<li id="new_type_li">' +
                            '<table width="100%" cellpadding="0" cellspacing="0">'+
                            '<tr class="grid_item">'+
                            '<td style="width:1px;background:#ffffff"></td>'+
                            '<td class="spe_line2" style="width:50px;text-align: center"></td>'+
                            '<td style="width:1px;background:#ffffff"></td>'+
                            '<td class="spe_line2" style="width:100px;text-align: center"><input type="text" style="width:90px"  id="new_type_name"/></td>'+
                            '<td style="width:1px;background:#ffffff"></td>'+
                            '<td class="spe_line2" style="width:200px;text-align: center">'+
                               '<input type="button" value="确定" class="btn2" id="confirm"/>&nbsp;&nbsp;&nbsp;&nbsp;'+
                               '<input type="button" value="取消" class="btn2" id="cancel"/>'+
                            '</td>'+
                            '<td style="width:1px;background:#ffffff"></td>'+
                            '<td></td>'+
                            '</tr>'+
                            '</table>' +
                            '</li>');
                }else{
                    alert("您正在新增类别");
                }
            });

            $("body").on("click",'#cancel',function(){
                if(addOpen == 1){
                    $("#new_type_li").remove();
                    addOpen=0;
                }else{
                    alert("你并没有新增");
                }
            });

            $("body").on("click",'#confirm',function(){
                var type_name = $("#new_type_name").val();
                alert(type_name);
            });
        });
    </script>
</head>
<body>
<div style="margin-top: 1px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:1px"></td>
            <td  id="td_main_btn" style="width:80px;">
                <div class="btn2" id="add">新增类别</div>
            </td>
            <td></td>
        </tr>
    </table>
</div>
<table class="grid" width="100%" cellpadding="0" cellspacing="0">
    <tr  class="grid_title">
        <td style="width:50px;text-align: center">ID</td>
        <td style="width:100px;text-align: center">类别名</td>
        <td  style="width:200px;text-align: center">操作</td>
        <td></td>
    </tr>
</table>
<ul id="type_list">
<icms id="user_album_type_list" type="list">
    <item>
        <![CDATA[
            <li>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr class="grid_item">
                        <td class="spe_line2" style="width:50px;text-align: center">{f_UserAlbumTypeId}</td>
                        <td class="spe_line2" style="width:100px;text-align: center">{f_UserAlbumTypeName}</td>
                        <td class="spe_line2" style="width:200px;text-align: center">
                            <span style="cursor:pointer" class="btn2">启用</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="cursor:pointer" class="btn2">停用</span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="cursor:pointer" class="btn2">修改</span>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </li>
        ]]>
    </item>

</icms>
</ul>
</body>
</html>