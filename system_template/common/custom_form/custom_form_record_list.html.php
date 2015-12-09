<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/custom_form/custom_form.js"></script>
    <script type="text/javascript">
        $("document").ready(function(){
            var channelId = Request["channel_id"];

            //日期控件
            $(".GetDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 3,
                showButtonPanel: true
            });

            $("#btn_create_custom_form_record").click(function(event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=create&custom_form_id={CustomFormId}';
                parent.G_TabTitle = '新增记录';
                parent.addTab();
            });

            var btnEdit = $(".btn_edit_custom_form_record");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function(event) {
                var customFormRecordId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=modify&custom_form_id={CustomFormId}&custom_form_record_id=' + customFormRecordId + '&channel_id=' + channelId;
                parent.G_TabTitle = '编辑记录';
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
                    searchTable.html(searchTable.html()+'<div style="margin:5px">字段名：<select id="field_'+numberOfSearchKey+'" ><option value="0_1" >全文本字段</option>{FieldSelection}<option value="0_-1" >状态</option></select>字段内容：<input type="text" id="content_'+numberOfSearchKey+'" />(若搜索数值区间，表示方法：小数_大数。例：10_20表示大于10且小于20)</div>')
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
            });

            //新窗口显示打印
            $("#new_window").click(function(){
                var endPage=$("#end_page").val();
                if(endPage>0){
                    var length=endPage*20;
                    var url="/default.php?secu=manage&mod=custom_form_record&m=list&custom_form_id={CustomFormId}&new_window=1&ps="+length;
                    window.open(url);
                }else{
                    alert("页数输入错误！");
                }
            });

            //随机抽取
            $(".new_window_for_random").click(function(){
                var randomHit=$(this).attr("num");
                var randomCount=$("#random_count").val();
                var beginTime=$("#random_begin_time").val();
                var endTime=$("#random_end_time").val();
                var timePick="";
                if(beginTime!=""&&beginTime!="开始时间"&&endTime!=""&&endTime!="结束时间"){
                    timePick="&begin_time="+beginTime+"&end_time="+endTime;
                }
                if(randomCount>0){
                    var url="/default.php?secu=manage&mod=custom_form_record&m=random_list&custom_form_id={CustomFormId}&new_window=1&ps="+randomCount+timePick+"&random_hit="+randomHit;
                    window.open(url);
                }else{
                    alert("随机数输入错误！");
                }
            });



            //删除附件
            var deleteAttachment = $(".btn_delete_attachment");
            deleteAttachment.click(function () {
                var r=confirm("是否确认删除！");
                if(r==true){
                    var customFormContentId = $(this).attr("id");

                    $.ajax({
                        type: "get",
                        url: "/default.php?secu=manage" +
                            "&mod=custom_form_content" +
                            "&m=async_delete_attachment",
                        data: {
                            custom_form_content_id: customFormContentId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(data) {
                            var result = parseInt(data["result"]);
                            if(result>0){
                                alert("操作完成");
                            }else{
                                alert("没有上传附件或删除失败");
                            }
                        }
                    });
                }

            });

        });


    </script>
</head>
<body>
{common_body_deal}
<div class="div_list">
    <table  width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td id="td_main_btn" align="left" style="padding: 3px 0;" width="85">
                <input id="btn_create_custom_form_record" class="btn2" value="新增记录" title="在本表单新增一条记录" type="button"/>
            </td>
            <td id="td_main_btn" align="left" style="padding: 3px 0;" width="113">
                <input id="btn_add_search_key" class="btn2" value="添加搜索字段" title="添加要搜索的关键字" type="button" />
            </td>
            <td id="td_main_btn" align="left" style="padding: 3px 0;" width="85">
                <input id="btn_run_search" class="btn2" value="提交搜索" title="提交搜索" type="button" style="display:none" />
            </td>
            <td id="td_main_btn" align="right" style="padding: 3px 0;">
                <div id="search_box" style="float:left">
                    <label for="random_count"></label><input id="random_count" name="random_count" class="input_number" value="随机数" type="text">
                    <label for="random_begin_time"></label><input id="random_begin_time" name="random_begin_time" class="GetDate" value="开始时间" type="text">
                    <label for="random_end_time"></label><input id="random_end_time" name="random_end_time" class="GetDate" value="结束时间" type="text">
                    <input id="new_window_for_random" class="btn2 new_window_for_random" value="随机抽取" type="button">
                </div>

            </td>
            <td id="td_main_btn" align="right" style="width:240px;padding: 3px 0;">
                <div id="search_box">
                    <label for="end_page"></label><input id="end_page" name="end_page" class="input_number" value="显示页数" type="text">
                    <input id="new_window" class="btn2" value="新窗口显示" type="button">
                </div>
            </td>
        </tr>
    </table>
    <table id="top" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td id="search_table" align="left" style="padding: 3px 0;">
            </td>
        </tr>
    </table>
    {ListTable}
    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                {PagerButton}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
