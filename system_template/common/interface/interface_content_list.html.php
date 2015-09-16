<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        var ajaxResultData;
        var strJsonInsert="{DocumentNews:[";
        $("document").ready(function () {

            $(".if_direct_url").each(function(){
                var directUrl=$(this).attr("idvalue");
                if(directUrl!=""){
                    $(this).css("color","red");
                    $(this).html("直接转向文档");
                }
            });

            $("#json_type").change(function() {
                var jsonType = $("#json_type").val();
                var tagId = $("#json_type").find("option[value='"+jsonType+"']").attr("idvalue") //列表类型
                window.location.href='/default.php?secu=manage&mod=interface&m=list&channel_id={ChannelId}&json_type='+jsonType+'&tag_id='+tagId;


            });


            $("#btn_select_all").click(function(event) {
                event.preventDefault();
                var inputSelect = $("[name='input_select']");
                if (inputSelect.prop("checked")) {
                    inputSelect.prop("checked", false);//取消全选
                } else {
                    inputSelect.prop("checked", true);//全选
                }
            });

            $(".grid_item").click(function(){
                SelectColorChange($(this));
            })


            /**
             * 复制
             * **/
            $("#btn_copy").click(function (event) {
                event.preventDefault();
                var channelId=$(this).attr("idvalue");
                var docIdString = "";
                var w = 500;
                var h = $(window).height() - 100;

                $('input[name=input_select]').each(function (i) {  //选中的几条
                    if (this.checked) {
                        docIdString = docIdString + ',' + ($(this).val()-1);  //c_no从1开始，数组第一位是0
                    }
                });

                docIdString=docIdString.substr(1);
                if (docIdString.length <= 0) {
                    alert("请先选择要操作的文档");
                } else {

                    var jsonType = $("#json_type").val();
                    var targetChannelType=$("#json_type").find("option[value=’"+jsonType+"‘]").attr("idvalue")
                    var url='/default.php?secu=manage&mod=interface&m=copy&channel_id='+channelId+'&doc_id_string='+docIdString+'&json_type='+jsonType+'&target_channel_type='+targetChannelType;
                    $("#dialog_frame").attr("src",url);
                    $("#dialog_resultbox").dialog({
                        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                        autoOpen:true,
                        height:w,
                        width:h,
                        modal:true, //蒙层（弹出会影响页面大小）
                        title:'复制',
                        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                    });
                }
            });


        });


        function SelectColorChange(selected){
                if ($(selected).hasClass('grid_item_selected')) {
                    $(selected).removeClass('grid_item_selected');
                } else {
                    $(selected).addClass('grid_item_selected');
            }
        }

    </script>
</head>
<body>
<div id="dialog_resultbox" title="" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src=""  style="border: 0; " width="100%" height="460"></iframe>
    </div>
</div>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_manage_all_newspaper_article" style="display:none;" class="btn2" value="全部文章管理" title="全部文章管理" type="button"/>
                <input id="btn_copy" class="btn2" idvalue="{ChannelId}" value="复制" title="复制本频道文档至其它频道，请先在下面文档中勾选需要复制的文档" type="button"/>
            </td>
            <td class="spe_line" style="width:150px;height:35px;text-align: right;"><label
                    for="json_type">解析格式：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="json_type" name="PicStyle">
                    <option value="default" idvalue="document_news_interface_content_list">资讯-默认(icms2)</option>
                    <option value="icms1" idvalue="document_news_interface_content_list">资讯-旧ICMS(icms1)</option>
                </select>
                <script language="JavaScript">$("#json_type").find("option[value='{JsonType}']").attr("selected",true)</script>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box" style="display: none">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td>标题</td>
            <td style="width: 90px;text-align:center;">源地址</td>
            <td style="width: 90px;text-align:center;">是否直接转向</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="document_news_interface_content_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_DocumentNewsId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <label>
                                    <input class="input_select" type="checkbox" name="input_select" value="{c_no}"/>
                                </label>
                            </td>
                            <td class="spe_line2" style="padding-left:10px;"><a target="_blank" class="link_view" idvalue="{f_DocumentNewsId}" pub_date="{f_year}{f_month}{f_day}"><span style="color:{f_DocumentNewsTitleColor};font-weight:{f_DocumentNewsTitleBold};">{f_DocumentNewsTitle}</span></a></td>
                            <td class="spe_line2 ori_url" style="width:90px;text-align:center;" title="源地址" idvalue="{f_OriUrl}"><a href="{f_OriUrl}" target="_blank">查看原网页</a></td>
                            <td class="spe_line2 if_direct_url" style="width:90px;text-align:center;" title="跳转地址" idvalue="{f_DirectUrl}"></td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="文档创建时间">{f_CreateDate}</td>
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
