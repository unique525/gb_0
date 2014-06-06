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
                            '<td class="spe_line2" style="width:50px;text-align: center"></td>'+
                            '<td class="spe_line2" style="width:100px;text-align: center"><input type="text" style="width:90px"  id="new_type_name"/></td>'+
                            '<td class="spe_line2" style="width:200px;text-align: center">'+
                               '<input type="button" value="确定" class="btn2" id="confirm"/>&nbsp;&nbsp;&nbsp;&nbsp;'+
                               '<input type="button" value="取消" class="btn2" id="cancel"/>'+
                            '</td>'+
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

            $(".modify").click(function(){
                var id_value= $(this).attr("idvalue");
                $(".modify_operation_"+id_value).css("display","block");
                $(".normal_operation_"+id_value).css("display","none");
            });

            $(".modify_cancel").click(function(){
                var id_value= $(this).attr("idvalue");
                $(".modify_operation_"+id_value).css("display","none");
                $(".normal_operation_"+id_value).css("display","block");
            });

            $(".modify_confirm").click(function(){
                $.ajax({

                });
            });
        });
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td  id="td_main_btn" style="width:80px;">
                <div class="btn2" id="add">新增类别</div>
            </td>
            <td></td>
        </tr>
    </table>
<table class="grid" width="100%" cellpadding="0" cellspacing="0">
    <tr  class="grid_title2">
        <td style="width:50px;text-align: center">ID</td>
        <td style="width:50px;text-align: center">编辑</td>
        <td style="width:100px;text-align: center">类别名</td>
        <td style="width:200px;text-align: center">所属站点</td>
        <td style="width:80px;text-align: center">状态</td>
        <td  style="width:50px;text-align: center">启用</td>
        <td  style="width:50px;text-align: center">停用</td>
    </tr>
</table>
<ul id="type_list">
<icms id="user_album_type_list" type="list">
    <item>
        <![CDATA[
            <li>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr class="grid_item2">
                        <td class="spe_line2" style="width:50px;text-align: center">{f_UserAlbumTypeId}</td>
                        <td class="spe_line2" style="width:50px;text-align: center">
                            <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="modify" idvalue="{f_UserAlbumTypeId}"/>
                        </td>
                        <td class="spe_line2" style="width:100px;text-align: center">
                            <div class="normal_operation_{f_UserAlbumTypeId}">
                                {f_UserAlbumTypeName}
                            </div>
                            <div class="modify_operation_{f_UserAlbumTypeId}" style="display:none">
                                <input type="text" value="{f_UserAlbumTypeName}" style="width:90px;text-align: center" id="new_type_name_{f_UserAlbumTypeId}"/>&nbsp;&nbsp;
                                <span style="cursor:pointer" class="btn2 modify_confirm" idvalue="{f_UserAlbumTypeId}">确定</span>&nbsp;&nbsp;
                                <span style="cursor:pointer" class="btn2 modify_cancel" idvalue="{f_UserAlbumTypeId}">取消</span>
                            </div>
                        </td>
                        <td class="spe_line2" style="width:200px;text-align: center">

                        </td>
                        <td class="spe_line2" style="width:80px;text-align: center">

                        </td>
                        <td class="spe_line2" style="width:50px;text-align: center">
                            <img src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                        </td>
                        <td class="spe_line2" style="width:50px;text-align: center">
                            <img src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                        </td>
                    </tr>
                </table>
            </li>
        ]]>
    </item>

</icms>
</ul>
</div>
</body>
</html>