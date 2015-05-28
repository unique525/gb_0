<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
        <style type="text/css">
            .import{cursor:pointer}
        </style>
        <script type="text/javascript">
            $(function(){
                $(".import").click(function(){
                    var templateLibraryId = $(this).attr("idvalue");
                    $.ajax({
                        url:"/default.php?secu=manage&mod=template_library&m=check",
                        data:{channel_id:{ChannelId}},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            var result =  data["result"];
                            if(result == 0){
                                window.location.href="/default.php?secu=manage&mod=template_library&m=import&channel_id={ChannelId}&template_library_id="+templateLibraryId;
                            }else{
                                var r = confirm("此频道已经有导入的模板了,是否要替换?(替换会丢失所有已存在的频道，包括频道内的文档)");
                                if(r == true){
                                    window.location.href="/default.php?secu=manage&mod=template_library&m=import&channel_id={ChannelId}&template_library_id="+templateLibraryId;
                                }
                            }
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
    <div class="div_list">
        <table width="100%" class="doc_grid" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td class="spe_line" width="10"></td>
            <td class="spe_line">模板库名称</td>
            <td class="spe_line">创建时间</td>
            <td class="spe_line" style="text-align:center">操作</td>
        </tr>
        <icms id="template_library_list" type="list">
        <item><![CDATA[
        <tr class="grid_item">
            <td class="spe_line" width="10"></td>
            <td class="spe_line">{f_TemplateLibraryName}</td>
            <td class="spe_line">{f_CreateDate}</td>
            <td class="spe_line">
                <div id="import_{f_TemplateLibraryId}" class="import btn2" idvalue="{f_TemplateLibraryId}" style="width:30px;margin:2px">
                    导入
                </div>
            </td>
        </tr>
        ]]>
        </item>
        </icms>
        </table>
        </div>
    </body>
</html>