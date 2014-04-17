<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_header}
        <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
        <script type="text/javascript">
            var editor;
            $(function(){
                editor = $('#f_ChannelIntro').xheditor();
                $("#f_CreateDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });

                $("#preview_title_pic1").click(function() {
                    var imgTitlePic1 = "{TitlePic1}";
                    if(imgTitlePic1 !== ''){
                        var imageOfTitlePic1 = new Image();
                        imageOfTitlePic1.src = imgTitlePic1;
                        $("#img_title_pic").attr("src",imgTitlePic1);
                        $("#dialog_title_pic").dialog({
                            width : imageOfTitlePic1.width+30,
                            height : imageOfTitlePic1.height+30
                        });
                    }
                    else{
                        $("#dialog_title_pic").dialog({width:300,height:100});
                        $("#dialog_title_pic_content").html("还没有上传题图1");
                    }
                });


                $("#preview_title_pic2").click(function() {
                    var imgTitlePic2 = "{TitlePic2}";
                    if(imgTitlePic2 !== ''){
                        var imageOfTitlePic2 = new Image();
                        imageOfTitlePic2.src = imgTitlePic2;
                        $("#img_title_pic").attr("src",imgTitlePic2);
                        $("#dialog_title_pic").dialog({
                            width : imageOfTitlePic2.width+30,
                            height : imageOfTitlePic2.height+30
                        });
                    }
                    else{
                        $("#dialog_title_pic").dialog({width:300,height:100});
                        $("#dialog_title_pic_content").html("还没有上传题图2");
                    }
                });

                $("#preview_title_pic3").click(function() {
                    var imgTitlePic3 = "{TitlePic2}";
                    if(imgTitlePic3 !== ''){
                        var imageOfTitlePic3 = new Image();
                        imageOfTitlePic3.src = imgTitlePic3;
                        $("#img_title_pic").attr("src",imgTitlePic3);
                        $("#dialog_title_pic").dialog({
                            width : imageOfTitlePic3.width+30,
                            height : imageOfTitlePic3.height+30
                        });
                    }
                    else{
                        $("#dialog_title_pic").dialog({width:300,height:100});
                        $("#dialog_title_pic_content").html("还没有上传题图2");
                    }
                });

                var selChannelType = $("#f_ChannelType");
                selChannelType.change(function(){
                      $(this).css("background-color","#FFFFCC");
                      var dnt = $(this).val();
                      if(dnt == '50'){
                          $(".type_0").css("display","none");
                          $(".type_1").css("display","");
                      }else{
                          $(".type_0").css("display","");
                          $(".type_1").css("display","none");
                      }

                });
                selChannelType.change();
            });
    
            function submitForm()
            {
                if($('#f_ChannelName').val() == ''){
                    alert('请输入频道名称');
                }else if($('#f_ChannelIntro').text().length>1000){
                    alert('频道简介不能超过1000个字符');
                }else{
                    $('#mainForm').submit();
                }
            }

        </script>
    </head>
    <body>
        <form id="mainForm" enctype="multipart/form-data"  action="/default.php?mod=channel&m={method}&channel_id={channel_id}&parent_id={parent_id}" method="post">
            <div>
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="spe_line" width="20%" height="30" align="right">上级频道：</td>
                        <td class="spe_line">{ParentName}
                            <input name="f_ParentId" type="hidden" value="{ParentId}" />
                            <input name="f_SiteId" type="hidden" value="{SiteId}" />
                            <input name="f_Rank" type="hidden" value="{Rank}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_ChannelName">频道名称：</label></td>
                        <td class="spe_line"><input name="f_ChannelName" id="f_ChannelName" value="{ChannelName}" type="text" class="input_box" style="width:300px;" /></td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_CreateDate">创建时间：</label></td>
                        <td class="spe_line"><input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="text" class="input_box" style="width:80px;" /></td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_ChannelType">频道类型：</label></td>
                        <td class="spe_line">
                            <select id="f_ChannelType" name="f_ChannelType">
                                <option value="1" {s_ChannelType_1}>新闻信息类</option>
                                <option value="2" {s_ChannelType_2}>咨询答复类</option>
                                <option value="3" {s_ChannelType_3}>图片轮换类</option>
                                <option value="4" {s_ChannelType_4}>产品类</option>
                                <option value="5" {s_ChannelType_5}>频道结合产品类</option>
                                <option value="6" {s_ChannelType_6}>活动类</option>
                                <option value="7" {s_ChannelType_7}>投票类</option>
                                <option value="8" {s_ChannelType_8}>自定义页面类</option>
                                <option value="9" {s_ChannelType_9}>友情链接类</option>
                                <option value="10" {s_ChannelType_10}>活动表单类</option>
                                <option value="11" {s_ChannelType_11}>文字直播类</option>
                                <option value="12" {s_ChannelType_12}>投票调查类</option>
                                <option value="0" {s_ChannelType_0}>站点首页类</option>
                                <option value="50" {s_ChannelType_50}>外部接口类</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                        <td class="spe_line">
                            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number" />
                        </td>
                    </tr>
                </table>
                    <div class="type_0">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr>
                        <td class="spe_line" width="20%" height="30" align="right"><label for="f_PublishType">发布方式：</label></td>
                        <td class="spe_line">
                            <select id="f_PublishType" name="f_PublishType">
                                <option value="1" {s_PublishType_1}>自动发布新稿</option>
                                <option value="0" {s_PublishType_0}>仅发布终审文档</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_PublishPath">发布文件夹：</label></td>
                        <td class="spe_line">
                            <input id="f_PublishPath" name="f_PublishPath" type="text" value="{PublishPath}" class="input_box" /> (可以为空)
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_HasFtp">是否有单独FTP设置：</label></td>
                        <td class="spe_line">
                            <select id="f_HasFtp" name="f_HasFtp">
                                <option value="0" {s_HasFtp_0}>无</option>
                                <option value="1" {s_HasFtp_1}>有</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_ShowChildList">是否显示子频道数据：</label></td>
                        <td class="spe_line">
                            <select id="f_ShowChildList" name="f_ShowChildList">
                                <option value="0" {s_ShowChildList_0}>否</option>
                                <option value="1" {s_ShowChildList_1}>是</option>
                            </select> (在显示列表数据时)
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_OpenComment">评论：</label></td>
                        <td class="spe_line">
                            <select id="f_OpenComment" name="f_OpenComment">
                                <option value="0" {s_OpenComment_0}>不允许</option>
                                <option value="10" {s_OpenComment_10}>允许但需要审核（先审后发）</option>
                                <option value="20" {s_OpenComment_20}>允许但需要审核（先发后审）</option>
                                <option value="30" {s_OpenComment_30}>自由评论</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_Invisible">是否在频道导航树上隐藏：</label></td>
                        <td class="spe_line">
                            <select id="f_Invisible" name="f_Invisible">
                                <option value="0" {s_Invisible_0}>不隐藏</option>
                                <option value="1" {s_Invisible_1}>隐藏</option>
                            </select>
                        </td>
                    </tr>
                    


                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_BrowserTitle">浏览器标题：</label></td>
                        <td class="spe_line">
                            <input id="f_BrowserTitle" name="f_BrowserTitle" type="text" value="{BrowserTitle}" class="input_box" style="width:400px;" maxlength="200" />
                        </td>
                    </tr>


                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_BrowserKeywords">浏览器关键字：</label></td>
                        <td class="spe_line">
                            <input id="f_BrowserKeywords" name="f_BrowserKeywords" type="text" value="{BrowserKeywords}" class="input_box" style="width:400px;" maxlength="200" />
                        </td>
                    </tr>

                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_BrowserDescription">浏览器描述文字：</label></td>
                        <td class="spe_line">
                            <input id="f_BrowserDescription" name="f_BrowserDescription" type="text" value="{BrowserDescription}" class="input_box" style=" width: 400px;" maxlength="200" />
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_IsCircle">是否加入模板库的循环调用：</label></td>
                        <td class="spe_line">
                            <select id="f_IsCircle" name="f_IsCircle">
                                <option value="0" {s_IsCircle_0}>否</option>
                                <option value="1" {s_IsCircle_1}>是</option>
                            </select> (在使用模板库调用频道数据时)
                        </td>
                    </tr> 
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_IsShowIndex">是否显示在聚合页中：</label></td>
                        <td class="spe_line">
                            <select id="f_IsShowIndex" name="f_IsShowIndex">
                                <option value="0" {s_IsShowIndex_0}>否</option>
                                <option value="1" {s_IsShowIndex_1}>是</option>
                            </select> (在使用频道聚合页中时)
                        </td>
                    </tr> 
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="file_title_pic_1">频道图片1：</label></td>
                        <td class="spe_line">
                            <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box" style="width:400px;background:#ffffff;margin-top:3px;" /> <span id="preview_title_pic1" style="cursor:pointer">[预览]</span>
                            <div id="dialog_title_pic1" title="频道图片1预览（{TitlePic1}）" style="display:none;">
                                    <div id="dialog_title_pic1_content">
                                        <table>
                                            <tr>
                                                <td><img id="img_title_pic" src="" alt="" /></td>
                                            </tr>
                                        </table>
                                    </div>
                            </div>
                        </td>
                    </tr> 
                    <tr>
                        <td class="spe_line" height="30" align="right">频道图片2：</td>
                        <td class="spe_line">
                            <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box" style="width:400px; background: #ffffff; margin-top: 3px;" /> <span id="preview_title_pic2" style="cursor:pointer">[预览]</span>

                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right">频道图片3：</td>
                        <td class="spe_line">
                            <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box" style="width:400px; background: #ffffff; margin-top: 3px;" /> <span id="preview_title_pic3" style="cursor:pointer">[预览]</span>

                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_ChannelIntro">频道介绍：</label></td>
                        <td class="spe_line">
                            <textarea cols="30" rows="30" id="f_ChannelIntro" name="f_ChannelIntro" style="width:70%;height:250px;">{ChannelIntro}</textarea>
                        </td>
                    </tr>
                        </table>
                    </div>
               <div class="type_1" style="display: none;">
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
               <tr>
                        <td class="spe_line" width="20%" height="30" align="right"><label for="f_PublishApiUrl">发布API接口地址：</label></td>
                        <td class="spe_line">
                            <input id="f_PublishApiUrl" name="f_PublishApiUrl" type="text" value="{PublishApiUrl}" class="input_box" style="width: 500px;" maxlength="200" />
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right"><label for="f_PublishApiType">发布API接口类型：</label></td>
                        <td class="spe_line">
                            <select id="f_PublishApiType" name="f_PublishApiType">
                                <option value="0" {s_PublishApiType_0}>XML</option>
                            </select> (在使用频道聚合页中时)</td>
                    </tr> 
                </table>
            </div>
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr>
                        <td height="30" align="center">
                            <input class="btn" value="确 认" type="button" onclick="submitForm()" /> <input class="btn" value="取 消" type="button" onclick="cancelTab()" />
                        </td>
                    </tr>

                </table>

            </div>
        </form>
    </body>
</html>
