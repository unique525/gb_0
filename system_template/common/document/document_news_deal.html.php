<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="text/css" href="/system_template/{relative_path}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/images/editor.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{relative_path}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
        <script type="text/javascript" src="/system_js/tiny_mce/tiny_mce_src.js"></script>
        <script type="text/javascript" src="/system_js/tiny_mce/editor.js?uploadfiletype=1"></script>
        <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/system_js/tiny_mce/icolorpicker.js"></script>
        <script type="text/javascript" src="{rootpath}/js/ajaxfileupload_v2.1.js"></script>
        <link rel="stylesheet" href="/system_js/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
        <script type="text/javascript" src="../js/plupload/js/plupload.js"></script>
        <script type="text/javascript" src="../js/plupload/js/plupload.flash.js"></script>
        <script type="text/javascript" src="../js/plupload/js/plupload.gears.js"></script>
        <script type="text/javascript" src="../js/plupload/js/plupload.silverlight.js"></script>
        <script type="text/javascript" src="../js/plupload/js/plupload.html4.js"></script>
        <script type="text/javascript" src="../js/plupload/js/plupload.html5.js"></script>
        <script type="text/javascript" src="../js/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>

        <script type="text/javascript">
            var batchattachwatermark = "0";
            $(function(){
                
                $('#tabs').tabs();
                $("#f_showdate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 3,
                    showButtonPanel: true
                });                

                if(Request["id"] == undefined){
                    var today = new Date();
                    var month = today.getMonth()+1;
                    var s_date = today.getFullYear()+"-"+month+"-"+today.getDate();
                    var s_hour = today.getHours()<10?"0"+today.getHours():today.getHours();
                    var s_minute = today.getMinutes()<10?"0"+today.getMinutes():today.getMinutes();
                    var s_second = today.getSeconds()<10?"0"+today.getSeconds():today.getSeconds();
                    $("#f_showdate").val(s_date);
                    $("#f_showhour").val(s_hour);
                    $("#f_showminute").val(s_minute);
                    $("#f_showsecond").val(s_second);

                    $("#f_createdate").val(s_date + " " + s_hour + ":"+s_minute+":"+s_second );
                    $("#btncontinue").css("display","inline");

                }
                var window_h = Request["height"];
                if(!window_h || window_h<=0){
                    window_h = 600;
                }
                $("#f_documentnewscontent").css("height", window_h - 180);
                $("#tabs-1").css("height", window_h - 121);
                $("#tabs-2").css("height", window_h - 121);
                $("#tabs-3").css("height", window_h - 121);

                var img = $("#img_titlepic");
                var theimage = new Image();
                theimage.src = img.attr("src");
                var tp = '{titlepic}';                               
                $("#preview_titlepic").click(function() {
                    if(tp != ''){

                        $("#dialog_titlepic").dialog({
                            width : theimage.width+30,
                            height : theimage.height+50
                        });

                    }
                    else{
                        alert('还没有上传题图');
                    }
                });
                
                var img2 = $("#img_titlepic2");
                var theimage2 = new Image();
                theimage2.src = img2.attr("src2");
                var tp2 = '{titlepic2}';  
                $("#preview_titlepic2").click(function() {
                    if(tp2 != ''){

                        $("#dialog_titlepic2").dialog({
                            width : theimage.width+30,
                            height : theimage.height+50
                        });

                    }
                    else{
                        alert('还没有上传题图2');
                    }
                });
                
                var img3 = $("#img_titlepic3");
                var theimage3 = new Image();
                theimage3.src = img3.attr("src3");
                var tp3 = '{titlepic3}';  
                $("#preview_titlepic3").click(function() {
                    if(tp3 != ''){

                        $("#dialog_titlepic3").dialog({
                            width : theimage.width+30,
                            height : theimage.height+50
                        });

                    }
                    else{
                        alert('还没有上传题图3');
                    }
                });

                $("#select_source").css("cursor","pointer");
                $("#select_source").click(function() {
                    var w = 800;
                    var h = 500;
                    tb_show("", "../document/index.php?a=source&m=select&height=" + h + "&width=" + w + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=true", false);
                });
                
                $("#batchattachwatermark").change(function() {
                    if($("#batchattachwatermark").attr("checked")){
                        batchattachwatermark = "1";
                    }else{
                        batchattachwatermark = "0";
                    }
                    
                });

                $("#btnclose").click(function() {
                    var tab = parseInt(Request['tab'])-1;
                    self.parent.$('#tabs').tabs('remove',tab);
                });                
                
                ///////////////////加粗///////////////////////// /
                $("#titlebold").click(function() {
                    changebold();
                });
                //
                //加载BOLD
                var _bold = $("#f_documentnewstitlebold").val();
                if(_bold == "bold"){
                    $("#titlebold").attr('checked',true);
                    changebold();
                }
                
                ///////////////////
                //加载TITLE COLOR
                var _titlecolor = $("#f_documentnewstitlecolor").val();
                if(_titlecolor.length>=3){
                    $("#f_documentnewstitle").css("color",_titlecolor);
                }
                
              
                //组图上传                              
                
                $("#uploader").plupload({
                    // General settings
                    runtimes : 'flash,silverlight,html5,gears,html4',
                    url : '{rootpath}/common/index.php?a=uploadbatch&cid={cid}&type=1&batchattachwatermark='+batchattachwatermark,
                    max_file_size : '100mb',
                    max_file_count: 20, // user can add no more then 20 files at a time
                    chunk_size : '250kb',
                    unique_names : true,
                    multiple_queues : true,

                    // Resize images on clientside if we can
                    //resize : {width : 320, height : 240, quality : 90},
		
                    // Rename files by clicking on their titles
                    rename: true,
		
                    // Sort files
                    sortable: true,

                    // Specify what files to browse for
                    filters : [
                        {title : "Image files", extensions : "jpg,gif,png,bmp,jpeg"},
                        {title : "Zip files", extensions : "zip,rar"},
                        {title : "Video files", extensions : "avi,mp4,mp3,wmv,wma"}
                    ],

                    // Flash settings
                    flash_swf_url : '../js/plupload/js/plupload.flash.swf',

                    // Silverlight settings
                    silverlight_xap_url : '../js/plupload/js/plupload.silverlight.xap',
                    preinit: {
                        Init: function(up, info) {
                        },
                        UploadFile: function(up, file) {
                            up.settings.url = '{rootpath}/common/index.php?a=uploadbatch&cid={cid}&type=1&batchattachwatermark='+batchattachwatermark;
                        }
                    },
                    init: {
                        
                        FileUploaded: function(up, file, info) {
                            // Called when a file has finished uploading
                            //log('[FileUploaded] File:', file, "Info:", info);
                            var filesize = parseInt(file.size);
                            var fileid = parseInt(info.response);
                            if(fileid>0 && filesize>0){
                                var uploadfiles = $("#f_uploadfiles").val();
                                uploadfiles = uploadfiles + "," + fileid;
                                $("#f_uploadfiles").val(uploadfiles);
                                   
                                //modify file size
                                $.post("../common/index.php?a=uploadfile&m=modifyfilesize&id=" + fileid + "&size="+filesize, {
                                    results: $(this).html()
                                }, function(xml) {
                                    
                                });
                                if(!$("#f_ShowPicMethod").attr('checked')){ //没有打勾
                                    $.post("../common/index.php?a=uploadfile&m=geturl&id=" + fileid, {
                                        results: $(this).html()
                                    }, function(xml) {
                                        var outfile = tofile_html(xml);
                                        var htmlContent = tinyMCE.get('f_documentnewscontent').getContent();
                                        htmlContent = htmlContent + outfile;
                                        tinyMCE.get('f_documentnewscontent').setContent(htmlContent);
                                    }); 
                                }
                            }                            
                        }
                    }
                });
                
               
            });
            
            function changebold(){
                
                if($("#titlebold").attr('checked')){
                    $("#f_documentnewstitlebold").val("bold");
                    $("#f_documentnewstitle").css("font-weight","bold");
                }else{
                    $("#f_documentnewstitlebold").val("normal");
                    $("#f_documentnewstitle").css("font-weight","normal");
                }
                
            }
            
            /**
             * ajax上传
             */
            function ajaxFileUpload()
            {   
                $("#loading")
                .ajaxStart(function(){
                    $(this).show();
                })
                .ajaxComplete(function(){
                    $(this).hide();
                });
                
                var attachwatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
                    attachwatermark = 1;
                }

                $.ajaxFileUpload({
                    url:'{rootpath}/common/index.php?a=upload&cid={cid}&type=1&attachwatermark='+attachwatermark,
                    secureuri:false,
                    fileElementId:'fileToUpload',
                    dataType: 'json',
                    success: function (data, status)
                    {
                        if(typeof(data.error) != 'undefined')
                        {
                            if(data.error != ''){
                                $("#resulttable").html(data.error);
                                $("#dialog_resultbox").dialog({      
                                });
                            }
                            else{
                                var uploadfiles = $("#f_uploadfiles").val();
                                uploadfiles = uploadfiles + "," + data.fileid;
                                $("#f_uploadfiles").val(uploadfiles);
                                //tinyMCE.get('f_documentnewscontent').restoreSelection();
                                var contentfile = tofile_html(data.msg);                                
                                tinyMCE.get('f_documentnewscontent').execCommand('mceInsertContent', false, contentfile);
                                //var htmlContent = tinyMCE.get('f_documentnewscontent').getContent();
                                //htmlContent = htmlContent + data.msg;
                                //tinyMCE.get('f_documentnewscontent').setContent(htmlContent);
                            }
                        }
                    },
                    error: function (data, status, e)
                    {
                        alert(e);
                    }
                })
                return false;

            }

            function setsourcename(_value){
                $("#f_sourcename").val(_value);
            }

            function insertcontent(_value){
                var today = new Date();
                var month = today.getMonth()+1;
                var day = today.getDate();
                _value = _value.replace("{month}",month);
                _value = _value.replace("{day}",day);
                var htmlContent = tinyMCE.get('f_documentnewscontent').getContent();
                htmlContent = _value + htmlContent;
                tinyMCE.get('f_documentnewscontent').setContent(htmlContent);
            }

            function sub()
            {
                $("#btnConfirm").attr("disabled",true);
                if($('#f_documentnewstitle').val() == ''){
                    alert('请输入标题');
                    return;
                }
                else
                {
                    $('#mainform').submit();
                }
            }
        </script>
        <style type="text/css">
            .ui-datepicker-next{ background: url({rootpath}/images/arr1.png) no-repeat;}
            .ui-datepicker-prev{ background: url({rootpath}/images/arr2.png) no-repeat;}
            body{ font-family: "宋体"}
        </style>

    </head>
    <body>

        <div id="dialog_resultbox" title="" style="display:none;">
            <div id="resulttable">

            </div>
        </div>
        <form id="mainform" enctype="multipart/form-data" action="index.php?a=docnews&m={method}&id={id}&cid={cid}&p={pageindex}&tab={tab}" method="post">

            <div id="tabs" style="border: none; width: 99%; margin-left: 0;">
                <ul>
                    <li><a href="#tabs-1">文档内容</a></li>
                    <li><a href="#tabs-2">文档参数</a></li>
                    <li><a href="#tabs-3">批量上传</a></li>
                    <li><a href="#tabs-4">其他属性</a></li>
                </ul>
                <div id="tabs-1" style=" font-family: 宋体">
                    <div class="speline" style="line-height: 40px;text-align: left;">
                        <table width="750px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style=" width: 60px;">标题：</td>
                                <td style=" width: 600px;">
                                    <input type="text" class="iColorPicker inputbox" id="f_documentnewstitle" name="f_documentnewstitle" value="{documentnewstitle}" style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200" />
                                    <input type="hidden" id="f_documentnewstitlecolor" name="f_documentnewstitlecolor" value="{documentnewstitlecolor}"  />
                                    <input type="hidden" id="f_documentnewstitlebold" name="f_documentnewstitlebold" value="{documentnewstitlebold}"  />
                                    <input type="hidden" id="f_adminuserid" name="f_adminuserid" value="{adminuserid}" />
                                    <input type="hidden" id="f_adminusername" name="f_adminusername" value="{adminusername}" />
                                    <input type="hidden" id="f_userid" name="f_userid" value="{userid}" />
                                    <input type="hidden" id="f_username" name="f_username" value="{username}" />
                                    <input type="hidden" id="f_siteid" name="f_siteid" value="{siteid}" />
                                    <input type="hidden" id="f_documentchannelid" name="f_documentchannelid" value="{documentchannelid}" />
                                    <input type="hidden" id="f_createdate" name="f_createdate" value="{createdate}" />
                                    <input type="hidden" id="f_uploadfiles" name="f_uploadfiles" value="{uploadfiles}" />
                                </td>
                                <td>
                                    <input type="checkbox" id="titlebold" /> 加粗
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style=" margin-top: 3px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="75%"><textarea class="mceEditor" id="f_documentnewscontent" name="f_documentnewscontent" style=" width: 100%;">{documentnewscontent}</textarea></td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                                        <tr>
                                            <td class="speline" style="width:74px;height:35px;text-align: right;">来源：</td>
                                            <td class="speline" style="text-align: left; line-height:180%;">
                                                <input type="text" class="inputbox" id="f_sourcename" name="f_sourcename" value="{sourcename}" style=" width: 180px;font-size:14px; margin-top: 4px;" maxlength="50" /><br /><span id="select_source">[选择来源]</span>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="speline" style="width:74px;height:35px;text-align: right;">常用来源：</td>
                                            <td class="speline" style="text-align: left; line-height:180%;">
                                                <cscms id="sourcecommonlist" type="list">
                                                    <item>
                                                        <![CDATA[<span style="cursor: pointer;" onclick="setsourcename('{f_sourcename}');">{f_sourcename}</span><br />]]>
                                                    </item>
                                                </cscms>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="speline" style="width:74px;height:35px;text-align: right;">作者：</td>
                                            <td class="speline" style="text-align: left">
                                                <input type="text" class="inputbox" id="f_author" name="f_author" value="{author}" style=" width: 180px;font-size:14px;" maxlength="50" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="speline" style="width:74px;height:35px;text-align: right;">主关键词：</td>
                                            <td class="speline" style="text-align: left">
                                                <input type="text" class="inputbox" id="f_documentnewsmaintag" name="f_documentnewsmaintag" value="{documentnewsmaintag}" style=" width: 180px;font-size:14px;" maxlength="100" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="speline" style="width:74px;height:35px;text-align: right;">关键词：</td>
                                            <td class="speline" style="text-align: left">
                                                <input type="text" class="inputbox" id="f_documentnewstag" name="f_documentnewstag" value="{documentnewstag}" style=" width: 180px;font-size:14px;" maxlength="1000" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="speline" style="height:35px;text-align: right;">加前缀：</td>
                                            <td class="speline" align="left" style="line-height:20px;">
                                                <cscms id="documentquickcontent" type="list">
                                                    <item>
                                                        <![CDATA[<span style="cursor: pointer;" onclick="insertcontent('{f_documentquickcontent}');">{f_documentquickcontent}</span><br />]]>
                                                    </item>
                                                </cscms>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:35px;">文件上传：</td>
                                            <td align="left">
                                                <input id="fileToUpload" name="fileToUpload" type="file" class="inputbox" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;" /><input id="btnupload" onclick="return ajaxFileUpload()" type="button" value="上传" />   
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:35px;">附加水印：</td>
                                            <td align="left">
                                                <input type="checkbox" id="attachwatermark" name="attachwatermark" /> (只支持jpg或jpeg图片)  
                                            </td>
                                        </tr>
                                    </table>


                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="tabs-2" style=" font-family: 宋体">
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">题图：</td>
                            <td class="speline" style="text-align: left">
                                <input id="titlepic_upload" name="titlepic_upload" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic" style="cursor:pointer">[预览]</span>
                                <div id="dialog_titlepic" title="题图预览（{titlepic}）" style="display:none;">
                                    <div id="pubtable">
                                        <table>
                                            <tr>
                                                <td><img id="img_titlepic" src="{titlepic}" alt="titlepic" /></td>
                                            </tr></table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">题图2：</td>
                            <td class="speline" style="text-align: left">
                                <input id="titlepic_upload2" name="titlepic_upload2" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic2" style="cursor:pointer">[预览]</span>
                                <div id="dialog_titlepic2" title="题图2预览（{titlepic2}）" style="display:none;">
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
                            <td class="speline" style="width:200px;height:35px;text-align: right;">题图3：</td>
                            <td class="speline" style="text-align: left">
                                <input id="titlepic_upload3" name="titlepic_upload3" type="file" class="inputbox" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic3" style="cursor:pointer">[预览]</span>
                                <div id="dialog_titlepic3" title="题图3预览（{titlepic3}）" style="display:none;">
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
                            <td class="speline" style="width:200px;height:35px;text-align: right;">短标题：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_documentnewsshorttitle" name="f_documentnewsshorttitle" value="{documentnewsshorttitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">副标题：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_documentnewssubtitle" name="f_documentnewssubtitle" value="{documentnewssubtitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>

                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">引题：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_documentnewscitetitle" name="f_documentnewscitetitle" value="{documentnewscitetitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>

                        <tr>
                            <td class="speline" style="width:200px;height:65px;text-align: right;">摘要：<br /><br />
                                <input type="button" class="btn4" value="编写" onclick='showModalDialog("/js/plugins/editabstract.html", window, "dialogWidth:850px;dialogHeight:400px;help:no;scroll:no;status:no");'/>&nbsp;
                            </td>
                            <td class="speline" style="text-align: left"><textarea class="inputbox" id="f_documentnewsintro" name="f_documentnewsintro" style=" width: 600px; height: 80px;font-size:14px;">{documentnewsintro}</textarea></td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">直接转向网址：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_directurl" name="f_directurl" value="{directurl}" style=" width: 600px;font-size:14px;" maxlength="200" /> (设置直接转向网址后，文档将直接转向到该网址)</td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-3" style=" font-family: 宋体">
                    <div id="uploader">
                        <p>您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，不能使用组图上传功能</p>
                    </div>
                    <div class="speline" style=" line-height: 30px;"> 使用组图控件展示内容中的图片 
                        <select id="f_ShowPicMethod" name="f_ShowPicMethod">
                            <option value="0" {s_ShowPicMethod_0}>关闭</option>
                            <option value="1" {s_ShowPicMethod_1}>开启</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        附加水印：<input type="checkbox" id="batchattachwatermark" name="batchattachwatermark" /> (只支持jpg或jpeg图片)                         
                    </div>
                </div>
                <div id="tabs-4" style=" font-family: 宋体">

                    <table width="99%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">排序数字：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputnumber" id="f_sort" name="f_sort" value="{sort}" style=" width: 60px;font-size:14px;" maxlength="10" /> (输入数字，越大越靠前)</td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">是否热门：</td>
                            <td class="speline" style="text-align: left">
                                <input type="radio"  name="f_ishot" value="0" {r_ishot_0} /> 否
                                <input type="radio"  name="f_ishot" value="1" {r_ishot_1} /> 是     
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">推荐级别：</td>
                            <td class="speline" style="text-align: left">
                                <input type="radio"  name="f_reclevel" value="0" {r_reclevel_0} /> 0
                                <input type="radio"  name="f_reclevel" value="1" {r_reclevel_1} /> 1
                                <input type="radio"  name="f_reclevel" value="2" {r_reclevel_2} /> 2
                                <input type="radio"  name="f_reclevel" value="3" {r_reclevel_3} /> 3
                                <input type="radio"  name="f_reclevel" value="4" {r_reclevel_4} /> 4
                                <input type="radio"  name="f_reclevel" value="5" {r_reclevel_5} /> 5  
                                <input type="radio"  name="f_reclevel" value="6" {r_reclevel_6} /> 6  
                                <input type="radio"  name="f_reclevel" value="7" {r_reclevel_7} /> 7 
                                <input type="radio"  name="f_reclevel" value="8" {r_reclevel_8} /> 8  
                                <input type="radio"  name="f_reclevel" value="9" {r_reclevel_9} /> 9
                                <input type="radio"  name="f_reclevel" value="10" {r_reclevel_10} /> 10   
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">显示时间：</td>
                            <td class="speline" style="text-align: left"><input type="text" class="inputbox" id="f_showdate" name="f_showdate" value="{showdate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" /> <input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showhour" name="f_showhour" value="{showhour}" maxlength="2" value="00" />:<input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showminute" name="f_showminute" value="{showminute}" maxlength="2" value="00" />:<input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showsecond" name="f_showsecond" value="{showsecond}" maxlength="2" value="00" /> (在文档中显示出来的时间，可任意设置)
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">新闻类型：</td>
                            <td class="speline" style="text-align: left">
                                <select id="f_documentnewstype" name="f_documentnewstype">
                                    <option value="0" {s_documentnewstype_0}>常规新闻</option>
                                    <option value="5" {s_documentnewstype_5}>推送新闻</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">当前状态：</td>
                            <td class="speline" style="text-align: left">
                                <select id="f_state" name="f_state">
                                    <option value="0" {s_state_0}>新稿</option>
                                    <option value="1" {s_state_1}>已编</option>
                                    <option value="2" {s_state_2}>返工</option>
                                    <option value="11" {s_state_11}>一审</option>
                                    <option value="12" {s_state_12}>二审</option>
                                    <option value="13" {s_state_13}>三审</option>
                                    <option value="14" {s_state_14}>终审</option>
                                    <option value="127" {s_state_127}> </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">新闻评论：</td>
                            <td class="speline" style="text-align: left">
                                <select id="f_opencomment" name="f_opencomment">
                                    <option value="40" {s_opencomment_40}>根据频道设置而定</option>
                                    <option value="20" {s_opencomment_20}>允许但需要审核（先发后审）</option>
                                    <option value="10" {s_opencomment_10}>允许但需要审核（先审后发）</option>
                                    <option value="30" {s_opencomment_30}>自由评论</option>
                                    <option value="0" {s_opencomment_0}>不允许</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="speline" style="width:200px;height:35px;text-align: right;">心情表态：</td>
                            <td class="speline" style="text-align: left">
                                <select id="f_closeposition" name="f_closeposition">
                                    <option value="0" {s_closeposition_0}>开启</option>
                                    <option value="1" {s_closeposition_1}>关闭</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="bot_button">
                <div style="padding-top:3px;">
                    <input id="btnConfirm" type="button" class="btn2" tabindex="0" onclick="sub()" value="确认并关闭(s)" accesskey="s" />
                    <input id="btncontinue" type="button" class="btn3" onclick="sub()" value="确认并继续新增(w)" accesskey="w" style=" width: 120px; display: none;" />
                    <input id="btnclose" type="button" class="btn1" tabindex="100" value="取  消" />
                </div>
            </div>
        </form>
    </body>
</html>
