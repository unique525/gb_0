<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

        <script type="text/javascript">
            $(function(){
                $(".channel_manage").click(function(event){
                    event.preventDefault();
                    var tId = $(this).attr("idvalue");
                    parent.G_TabUrl = '/default.php?secu=manage&mod=template_library_channel&m=list&template_library_id='+tId;
                    parent.G_TabTitle = '库频道管理';
                    parent.addTab();
                });

                $(".template_manage").click(function(event){
                    event.preventDefault();
                    var tId = $(this).attr("idvalue");
                    parent.G_TabUrl = '/default.php?secu=manage&mod=template_library_content&m=list&template_library_id='+tId+'';
                    parent.G_TabTitle = '库模板管理';
                    parent.addTab();
                });
                
                $("#btn_create").click(function(event){
                    event.preventDefault();
                    var tId = $(this).attr("idvalue");
                    window.location.href = '/default.php?secu=manage&mod=template_library&tab_index='+ parent.G_TabIndex +'&m=create&site_id={SiteId}'+'';

                });
                $(".btn_edit").click(function(event){
                    event.preventDefault();
                    var tId = $(this).attr("idvalue");
                    window.location.href = '/default.php?secu=manage&mod=template_library&m=modify&tab_index='+ parent.G_TabIndex +'&template_library_id='+tId+'';

                });
                $(".site_name[idvalue='0']").each(function(){
                    $(this).html("通用");

                });
            });
        </script>
    </head>
    <body>
    <div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新增模板库" title="" type="button"/>
            </td>
            <td id="td_main_btn" align="right">
                <div id="search_box" style="display: none">
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
        <table width="100%" class="grid" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td class="spe_line" style="text-align:center">ID</td>
            <td class="spe_line" style="width:60px;text-align:center;">编辑</td>
            <td class="spe_line">模板库名称</td>
            <td class="spe_line">作者</td>
            <td class="spe_line">创建时间</td>
            <td class="spe_line" style="width:80px;text-align:center;">所属站点</td>
            <td class="spe_line" style="text-align:center">频道管理</td>
            <td class="spe_line" style="text-align:center">模板内容</td>
        </tr>
        <icms id="template_library_list" type="list">
        <item><![CDATA[
        <tr class="grid_item">
            <td class="spe_line" style="text-align:center">{f_TemplateLibraryId}</td>
            <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                  class="btn_edit"
                                                                  src="/system_template/default/images/manage/edit.gif"
                                                                  alt="编辑" title="{f_TemplateLibraryId}"
                                                                  idvalue="{f_TemplateLibraryId}"/></td>
            <td class="spe_line">{f_TemplateLibraryName}</td>
            <td class="spe_line">{f_ManageUserName}</td>
            <td class="spe_line">{f_CreateDate}</td>
            <td class="spe_line" style="text-align:center"><span class="site_name" idvalue="{f_SiteId}">{f_SiteName}</span></td>
            <td class="spe_line" style="text-align:center"><span style="cursor:pointer;" class="channel_manage" idvalue="{f_TemplateLibraryId}">频道管理</span></td>
            <td class="spe_line" style="text-align:center"><span style="cursor:pointer;" class="template_manage" idvalue="{f_TemplateLibraryId}">模板管理</span></td>
        </tr>
        ]]>
        </item>
        </icms>
        </table>
        </div>
    <div id="PagerBtn">
        {PagerButton}
    </div>
    </body>
</html>