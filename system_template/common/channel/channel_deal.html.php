<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" href="/system_template/{templatename}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/editor.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <script type="text/javascript" src="{rootpath}/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/common.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript" src="{rootpath}/system_js/editor.js"></script>
        <script type="text/javascript">
            $(function(){
                
                $("#f_createdate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                }); 
                
                if(Request["id"] === undefined){
                    var today = new Date();
                    var month = today.getMonth()+1;
                    var s_date = today.getFullYear()+"-"+month+"-"+today.getDate();
                    var s_hour = today.getHours()<10?"0"+today.getHours():today.getHours();
                    var s_minute = today.getMinutes()<10?"0"+today.getMinutes():today.getMinutes();
                    var s_second = today.getSeconds()<10?"0"+today.getSeconds():today.getSeconds();
                    $("#f_createdate").val(s_date + " " + s_hour + ":"+s_minute+":"+s_second ); 
                }
                
                var img1 = $("#img_titlepic1");
                var theimage1 = new Image();
                theimage1.src = img1.attr("src");
                var tp1 = '{titlepic1}';
                $("#preview_titlepic1").click(function() {
                    if(tp1 !== ''){
                        $("#dialog_titlepic1").dialog({
                            width : theimage1.width+30,
                            height : theimage1.height+50
                        });
                    }
                    else{
                        alert('还没有上传题图1');
                    }
                });
                
                var img2 = $("#img_titlepic2");
                var theimage2 = new Image();
                theimage2.src = img2.attr("src");
                var tp2 = '{titlepic2}';
                $("#preview_titlepic2").click(function() {
                    if(tp2 != ''){
                        $("#dialog_titlepic2").dialog({
                            width : theimage2.width+30,
                            height : theimage2.height+50
                        });
                    }
                    else{
                        alert('还没有上传题图2');
                    }
                });
                
                var img3 = $("#img_titlepic3");
                var theimage3 = new Image();
                theimage3.src = img3.attr("src");
                var tp3 = '{titlepic3}';
                $("#preview_titlepic3").click(function() {
                    if(tp3 != ''){
                        $("#dialog_titlepic3").dialog({
                            width : theimage3.width+30,
                            height : theimage3.height+50
                        });
                    }
                    else{
                        alert('还没有上传题图3');
                    }
                });
                
                $("#f_documentchanneltype").change(function(){
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
                $("#f_documentchanneltype").change();
                

            });  
    
            function sub()
            {
                if($('#f_documentchannelname').val() == ''){
                    alert('请输入频道名称');
                    return;
                }else if($('#f_documentchannelintro').text().length>1000){
                    alert('频道简介不能超过1000个字符');
                    return;
                }else
                {
                    $('#mainform').submit();
                }
            }

        </script>
    </head>
    <body>
        <form id="mainform" enctype="multipart/form-data"  action="index.php?a=docchannel&m={method}&id={id}&cid={parentid}" method="post">
            <div>
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="speline" width="20%" height="30" align="right">上级频道：</td>
                        <td class="speline">{parentname}
                            <input name="f_parentid" type="hidden" value="{parentid}" />
                            <input name="f_siteid" type="hidden" value="{siteid}" />
                            <input name="f_rank" type="hidden" value="{rank}" />
                            <input name="f_adminuserid" type="hidden" value="{adminuserid}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">频道名称：</td>
                        <td class="speline"><input name="f_documentchannelname" id="f_documentchannelname" value="{documentchannelname}" type="text" class="inputbox" style=" width: 300px;" /></td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">创建时间：</td>
                        <td class="speline"><input id="f_createdate" name="f_createdate" value="{createdate}" type="text" class="inputbox" style=" width: 75px;font-size:14px;" /></td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">频道类型：</td>
                        <td class="speline">
                            <select id="f_documentchanneltype" name="f_documentchanneltype">
                                <option value="1" {s_documentchanneltype_1}>新闻信息类</option>
                                <option value="2" {s_documentchanneltype_2}>咨询答复类</option>
                                <option value="3" {s_documentchanneltype_3}>图片轮换类</option>
                                <option value="4" {s_documentchanneltype_4}>产品类</option>
                                <option value="5" {s_documentchanneltype_5}>频道结合产品类</option>
                                <option value="6" {s_documentchanneltype_6}>活动类</option>
                                <option value="7" {s_documentchanneltype_7}>投票类</option>
                                <option value="8" {s_documentchanneltype_8}>自定义页面类</option>
                                <option value="9" {s_documentchanneltype_9}>友情链接类</option>
                                <option value="10" {s_documentchanneltype_10}>活动表单类</option>
                                <option value="11" {s_documentchanneltype_11}>文字直播类</option>
                                <option value="12" {s_documentchanneltype_12}>投票调查类</option>
                                <option value="0" {s_documentchanneltype_0}>站点首页类</option>
                                <option value="50" {s_documentchanneltype_50}>外部接口类</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">排序号：</td>
                        <td class="speline">
                            <input name="f_sort" type="text" value="{sort}" class="inputnumber" />
                        </td>
                    </tr>
                </table>
                    <div class="type_0">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr>
                        <td class="speline" width="20%" height="30" align="right">发布方式：</td>
                        <td class="speline">
                            <select id="f_publishtype" name="f_publishtype">
                                <option value="1" {s_publishtype_1}>自动发布新稿</option>
                                <option value="0" {s_publishtype_0}>仅发布终审文档</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">发布文件夹：</td>
                        <td class="speline">
                            <input name="f_publishpath" type="text" value="{publishpath}" class="inputbox" /> (可以为空)
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">是否有单独FTP设置：</td>
                        <td class="speline">
                            <select id="f_hasftp" name="f_hasftp">
                                <option value="0" {s_hasftp_0}>无</option>
                                <option value="1" {s_hasftp_1}>有</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">是否显示子频道数据：</td>
                        <td class="speline">
                            <select id="f_ShowChildList" name="f_ShowChildList">
                                <option value="0" {s_ShowChildList_0}>否</option>
                                <option value="1" {s_ShowChildList_1}>是</option>
                            </select> (在显示列表数据时)
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">评论：</td>
                        <td class="speline">
                            <select id="f_OpenComment" name="f_OpenComment">
                                <option value="0" {s_OpenComment_0}>不允许</option>
                                <option value="10" {s_OpenComment_10}>允许但需要审核（先审后发）</option>
                                <option value="20" {s_OpenComment_20}>允许但需要审核（先发后审）</option>
                                <option value="30" {s_OpenComment_30}>自由评论</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">是否在频道导航树上隐藏：</td>
                        <td class="speline">
                            <select id="f_Invisible" name="f_Invisible">
                                <option value="0" {s_Invisible_0}>不隐藏</option>
                                <option value="1" {s_Invisible_1}>隐藏</option>
                            </select>
                        </td>
                    </tr>
                    


                    <tr>
                        <td class="speline" height="30" align="right">IE标题：</td>
                        <td class="speline">
                            <input name="f_ietitle" type="text" value="{ietitle}" class="inputbox" style=" width: 400px;" maxlength="200" />
                        </td>
                    </tr>


                    <tr>
                        <td class="speline" height="30" align="right">IE关键字：</td>
                        <td class="speline">
                            <input name="f_iekeywords" type="text" value="{iekeywords}" class="inputbox" style=" width: 400px;" maxlength="200" />
                        </td>
                    </tr>

                    <tr>
                        <td class="speline" height="30" align="right">IE描述文字：</td>
                        <td class="speline">
                            <input name="f_iedescription" type="text" value="{iedescription}" class="inputbox" style=" width: 400px;" maxlength="200" />
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">是否加入模板库的循环调用：</td>
                        <td class="speline">
                            <select id="f_IsCircle" name="f_IsCircle">
                                <option value="0" {s_IsCircle_0}>否</option>
                                <option value="1" {s_IsCircle_1}>是</option>
                            </select> (在使用模板库调用频道数据时)
                        </td>
                    </tr> 
                    <tr>
                        <td class="speline" height="30" align="right">是否显示在聚合页中：</td>
                        <td class="speline">
                            <select id="f_IsShowIndex" name="f_IsShowIndex">
                                <option value="0" {s_IsShowIndex_0}>否</option>
                                <option value="1" {s_IsShowIndex_1}>是</option>
                            </select> (在使用频道聚合页中时)
                        </td>
                    </tr> 
                    <tr>
                        <td class="speline" height="30" align="right">频道图片1：</td>
                        <td class="speline">
                            <input id="titlepic_upload1" name="titlepic_upload1" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic1" style="cursor:pointer">[预览]</span>
                            <div id="dialog_titlepic1" title="频道图片1预览（{titlepic1}）" style="display:none;">
                                    <div id="pubtable">
                                        <table>
                                            <tr>
                                                <td><img id="img_titlepic1" src="{titlepic1}" alt="titlepic1" /></td>
                                            </tr></table>
                                    </div>
                            </div>
                        </td>
                    </tr> 
                    <tr>
                        <td class="speline" height="30" align="right">频道图片2：</td>
                        <td class="speline">
                            <input id="titlepic_upload2" name="titlepic_upload2" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic2" style="cursor:pointer">[预览]</span>
                            <div id="dialog_titlepic2" title="频道图片2预览（{titlepic2}）" style="display:none;">
                                    <div id="pubtable">
                                        <table>
                                            <tr>
                                                <td><img id="img_titlepic2" src="{titlepic2}" alt="titlepic2" /></td>
                                            </tr></table>
                                    </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">频道图片3：</td>
                        <td class="speline">
                            <input id="titlepic_upload3" name="titlepic_upload3" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic3" style="cursor:pointer">[预览]</span>
                            <div id="dialog_titlepic3" title="频道图片3预览（{titlepic3}）" style="display:none;">
                                    <div id="pubtable">
                                        <table>
                                            <tr>
                                                <td><img id="img_titlepic3" src="{titlepic3}" alt="titlepic3" /></td>
                                            </tr></table>
                                    </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">频道介绍：</td>
                        <td class="speline">
                            <textarea class="mceEditor" id="f_documentchannelintro" name="f_documentchannelintro" style=" width: 70%; height: 250px;">{DocumentChannelIntro}</textarea>                            
                        </td>
                    </tr>
                        </table>
                    </div>
               <div class="type_1" style="display: none;">
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
               <tr>
                        <td class="speline" width="20%" height="30" align="right">发布API接口地址：</td>
                        <td class="speline">
                            <input name="f_PublishAPIUrl" type="text" value="{PublishAPIUrl}" class="inputbox" style=" width: 500px;" maxlength="200" />
                        </td>
                    </tr>
                    <tr>
                        <td class="speline" height="30" align="right">发布API接口类型：</td>
                        <td class="speline">
                            <select id="f_PublishAPIType" name="f_PublishAPIType">
                                <option value="0" {s_PublishAPIType_0}>XML</option>
                            </select> (在使用频道聚合页中时)</td>
                    </tr> 
                </table>
            </div>
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr>
                        <td height="30" align="center">
                            <input class="btn" value="确 认" type="button" onclick="sub()" /> <input class="btn" value="取 消" type="button" onclick="cancel_tab()" />
                        </td>
                    </tr>

                </table>

            </div>
        </form>
    </body>
</html>