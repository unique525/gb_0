<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $("document").ready(function(){
            $("#btn_create_custom_form_record").click(function(event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=create&custom_form_id={CustomFormId}';
                parent.G_TabTitle = parent.G_SelectedChannelName + '-新增记录';
                parent.addTab();
            });

            var btnEdit = $(".btn_edit_custom_form_record");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function(event) {
                var customFormRecordId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=modify&custom_form_id={CustomFormId}&custom_form_record_id=' + customFormRecordId + '&channel_id=' + parent.G_SelectedChannelId;
                parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑记录';
                parent.addTab();
            });


            ///////////
            // 搜索框

            var btnAddSearch = $("#btn_add_search_key");
            btnAddSearch.css("cursor", "pointer");
            var btnRunSearch = $("#btn_run_search");
            btnRunSearch.css("cursor", "pointer");
            var searchTable = $("#search_table");
            var numberOfSearchKey=0;
            btnAddSearch.click(function(event) {
                event.preventDefault();
                btnRunSearch.show();
                numberOfSearchKey++;
                if(numberOfSearchKey<={CustomFormFieldCount}){
                    searchTable.html(searchTable.html()+'<div style="margin:5px">字段名：<select id="field_'+numberOfSearchKey+'" ><option value="0" >全字段</option>{FieldSelection}</select>字段内容（仅文本类型）：<input type="text" id="content_'+numberOfSearchKey+'" /></div>')
                }else{
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("已达到此表单最大字段数");
                    numberOfSearchKey--;
                }
            });

            btnRunSearch.click(function(event){
                var url="";
                for(var i=1;i<=numberOfSearchKey;i++){
                    url+="&content_"+i+"="+$("#content_"+i).val();
                    url+="&field_"+i+"="+$("#field_"+i).val();
                }
                url="/default.php?secu=manage&mod=custom_form_record&m=list&custom_form_id={CustomFormId}&number_of_search_key=" + numberOfSearchKey + url;
                event.preventDefault();
                parent.G_TabUrl = url + '&channel_id=' + parent.G_SelectedChannelId;
                parent.G_TabTitle = parent.G_SelectedChannelName + '搜索记录';
                parent.addTab();
            })

        });


    </script>
</head>
<body>
{common_body_deal}
<div class="div_list">
    <table  width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left" style="padding: 3px 0;" width="85">
                <input id="btn_create_custom_form_record" class="btn2" value="新增记录" title="在本表单新增一条记录" type="button"/>
            </td>
            <td align="left" style="padding: 3px 0;" width="113">
                <input id="btn_add_search_key" class="btn2" value="添加搜索字段" title="添加要搜索的关键字" type="button" />
            </td>
            <td align="left" style="padding: 3px 0;">
                <input id="btn_run_search" class="btn2" value="提交搜索" title="提交搜索" type="button" style="display:none" />
            </td>
        </tr>
    </table>
    <table id="top" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td id="search_table" align="left" style="padding: 3px 0;">
            </td>
        </tr>
    </table>
    {ListTable}
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                {PagerButton}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
