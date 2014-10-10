<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/product/product_pic.js"></script>
    <style type="text/css">
        .grid_item{width:290px; height:240px; border:1px #CCC solid; margin: 3px 3px 3px 3px; float: left}
        .image{height:150px;padding: 4px;}
        .pic_title{line-height:22px; border-top:0 #CCC solid; margin: 0 4px;}
        .tools{height:30px;border-top:0 #CCC solid; padding-top: 4px;}
        .tools span{height:30px;text-align: center; line-height: 25px}
    </style>
    <script type="text/javascript">
        $(function() {
            var tag=Request["tag"];
            if(tag.length>0)
            {
                tag=decodeURIComponent(tag);
                $("#select_tag").val(tag);
            }
        });
        function GetProductPicByTag()
        {
            var tag=$("#select_tag").val();
            tag=encodeURIComponent(tag);
            var ps=Request["ps"];
            var tabIndex = Request["tab_index"];
            window.location.href='/default.php?secu=manage&mod=product_pic&m=list&product_id={ProductId}&tag='+tag+'&tab_index='+tabIndex+'&ps='+ps;
        }
    </script>
</head>
<body>
<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="200px"></iframe>
    </div>
</div>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新增图片" title="新增图片" type="button"/>
                &nbsp;&nbsp;&nbsp;&nbsp;按产品图片类别查看：
                <select id="select_tag" onchange="GetProductPicByTag()">
                    <option value="">全部</option>
                    <icms id="product_pic_tag_list">
                        <item>
                            <![CDATA[
                            <option value="{f_ProductPicTag}">{f_ProductPicTag}</option>
                            ]]>
                        </item>
                    </icms>
                </select>
            </td>
        </tr>
    </table>
</div>
<div id="grid_list">
    <icms id="product_pic_list" type="list">
        <item>
            <![CDATA[
            <div class="grid_item">
                <div class="tools">
                    <span><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ProductPicId}" alt="编辑"/></span>
                    <span><img class="btn_up" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_ProductPicId}" title="向上移动" alt="向上"/><img class="btn_down" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_ProductPicId}" title="向下移动" alt="向下"/></span>
                    <span title="排序数字，越大越靠前">{f_Sort}</span>
                    <span class="span_state" title="{f_State}" id="span_state_{f_ProductPicId}">{f_State}</span>
                    <span><img alt="" class="div_start" idvalue="{f_ProductPicId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer" />&nbsp;&nbsp;&nbsp;&nbsp;<img alt="" class="div_stop" idvalue="{f_ProductPicId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" /></span>
                </div>
                <div class="image"><img alt="" src="{f_UploadFileThumbPath1}" width="150px" height="150px"/></div>
                <div class="pic_title">{f_ProductPicTag}</div>
            </div>
            ]]>
        </item>
    </icms>
    <div>{pager_button}</div>
</div>
</body>
</html>