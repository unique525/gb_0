<!DOCTYPE html>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
        <script type="text/javascript" src="/system_js/color_picker.js"></script>
        <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
        <script type="text/javascript">
        <!--
        var editor;
            var batchAttachWatermark = "0";

            $(function(){

                var editorHeight = $(window).height() - 200;
                editorHeight = parseInt(editorHeight);
                editor = $('#f_DocumentNewsContent').xheditor({
                    tools:'full',
                    height:editorHeight,
                    upImgUrl:"upload.php",
                    upImgExt:"jpg,jpeg,gif,png"
                });

                $('#tabs').tabs();
                $("#f_ShowDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 3,
                    showButtonPanel: true
                });


                $("#preview_title_pic1").click(function () {
                    var imgTitlePic1 = "{TitlePic1}";
                    if (imgTitlePic1 !== '') {
                        var imageOfTitlePic1 = new Image();
                        imageOfTitlePic1.src = imgTitlePic1;
                        $("#dialog_box").dialog({
                            width: imageOfTitlePic1.width + 30,
                            height: imageOfTitlePic1.height + 30
                        });
                        var imgHtml = '<' + 'img src="' + imgTitlePic1 + '" alt="" />';
                        $("#dialog_content").html(imgHtml);

                    }
                    else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有上传题图1");
                    }
                });
                
                $("#preview_title_pic2").click(function () {
                    var imgTitlePic2 = "{TitlePic2}";
                    if (imgTitlePic2 !== '') {
                        var imageOfTitlePic2 = new Image();
                        imageOfTitlePic2.src = imgTitlePic2;
                        $("#dialog_box").dialog({
                            width: imageOfTitlePic2.width + 30,
                            height: imageOfTitlePic2.height + 30
                        });
                        var imgHtml = '<' + 'img src="' + imgTitlePic2 + '" alt="" />';
                        $("#dialog_content").html(imgHtml);
                    }
                    else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有上传题图2");
                    }
                });

                $("#preview_title_pic3").click(function () {
                    var imgTitlePic3 = "{TitlePic2}";
                    if (imgTitlePic3 !== '') {
                        var imageOfTitlePic3 = new Image();
                        imageOfTitlePic3.src = imgTitlePic3;
                        $("#dialog_box").dialog({
                            width: imageOfTitlePic3.width + 30,
                            height: imageOfTitlePic3.height + 30
                        });
                        var imgHtml = '<' + 'img src="' + imgTitlePic3 + '" alt="" />';
                        $("#dialog_content").html(imgHtml);
                    }
                    else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有上传题图2");
                    }
                });

                ///////////////////加粗/////////////////////////
                var cbTitleBold = $("#cbTitleBold");
                cbTitleBold.click(function() {
                    ChangeToBold();
                });
                //
                //加载BOLD
                var bold = $("#f_DocumentNewsTitleBold").val();
                if(bold == "bold"){
                    cbTitleBold.attr('checked',true);
                    ChangeToBold();
                }

                ///////////////////
                //加载TITLE COLOR
                var titleColor = $("#f_DocumentNewsTitleColor").val();
                if(titleColor.length>=3){
                    $("#f_DocumentNewsTitle").css("color",titleColor);
                }

                var btnSetSourceName = $(".btnSetSourceName");
                btnSetSourceName.css("cursor","pointer");
                btnSetSourceName.click(function(){
                    $("#f_SourceName").val($(this).text());
                });

                var btnUploadToContent = $("#btnUploadToContent");
                btnUploadToContent.click(function(){
                    AjaxFileUpload();
                });

                /**
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
                */
               
            });
            
            function ChangeToBold(){
                
                if($("#cbTitleBold").attr('checked')){
                    $("#f_DocumentNewsTitleBold").val("bold");
                    $("#f_DocumentNewsTitle").css("font-weight","bold");
                }else{
                    $("#f_DocumentNewsTitleBold").val("normal");
                    $("#f_DocumentNewsTitle").css("font-weight","normal");
                }
                
            }
            
            /**
             * ajax上传
             */
            function AjaxFileUpload()
            {
                $(document).ajaxStart(function() {
                    $( "#loading" ).show();
                });
                $(document).ajaxComplete(function() {
                    $( "#loading" ).hide();
                });

                var attachwatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
                    attachwatermark = 1;
                }

                $.ajaxFileUpload({
                    url:'/default.php?secu=manage&mod=upload&m=async_upload&table_type=1&attachwatermark='+attachwatermark,
                    secureUri:false,
                    fileElementId:'file_upload',
                    dataType: 'json',
                    success: function (data, status)
                    {

                        if(typeof(data.error) != 'undefined')
                        {
                            if(data.error != ''){
                                //$("#resulttable").html(data.error);
                                //$("#dialog_resultbox").dialog({
                                //});
                                alert(data.error);
                            }
                            else{
                                alert(data.result);
                                /**
                                var uploadfiles = $("#f_uploadfiles").val();
                                uploadfiles = uploadfiles + "," + data.fileid;
                                $("#f_uploadfiles").val(uploadfiles);
                                //tinyMCE.get('f_documentnewscontent').restoreSelection();
                                var contentfile = tofile_html(data.msg);                                
                                tinyMCE.get('f_documentnewscontent').execCommand('mceInsertContent', false, contentfile);
                                //var htmlContent = tinyMCE.get('f_documentnewscontent').getContent();
                                //htmlContent = htmlContent + data.msg;
                                //tinyMCE.get('f_documentnewscontent').setContent(htmlContent);
                                 */
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


            function submitForm()
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
        -->
        </script>
    </head>
    <body>
    {common_body_deal}
        <form id="mainForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=document_news&m={method}&id={id}&channel_id={ChannelId}" method="post">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>
            <div id="tabs" style="margin-left:4px;">
                <ul>
                    <li><a href="#tabs-1">文档内容</a></li>
                    <li><a href="#tabs-2">文档参数</a></li>
                    <li><a href="#tabs-3">批量上传</a></li>
                    <li><a href="#tabs-4">其他属性</a></li>
                </ul>
                <div id="tabs-1">
                    <div class="spe_line" style="line-height: 40px;text-align: left;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style=" width: 60px;"><label for="f_DocumentNewsTitle">标题：</label></td>
                                <td style=" width: 600px;">
                                    <input type="text" class="iColorPicker input_box" id="f_DocumentNewsTitle" name="f_DocumentNewsTitle" value="{DocumentNewsTitle}" style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200" />
                                    <input type="hidden" id="f_DocumentNewsTitleColor" name="f_DocumentNewsTitleColor" value="{DocumentNewsTitleColor}"  />
                                    <input type="hidden" id="f_DocumentNewsTitleBold" name="f_DocumentNewsTitleBold" value="{DocumentNewsTitleBold}"  />
                                    <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                                    <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                                    <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}" />
                                </td>
                                <td>
                                    <input type="checkbox" id="cbTitleBold" /> <label for="cbTitleBold">加粗</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style=" margin-top:3px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="75%"><label for="f_DocumentNewsContent"></label><textarea class="mceEditor" id="f_DocumentNewsContent" name="f_DocumentNewsContent" style=" width: 100%;">{DocumentNewsContent}</textarea></td>
                                <td style="vertical-align:top;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                                        <tr>
                                            <td style="width:74px;height:35px;text-align: right;"><label for="f_SourceName">来源：</label></td>
                                            <td style="text-align: left; line-height:180%;">
                                                <input type="text" class="input_box" id="f_SourceName" name="f_SourceName" value="{SourceName}" style=" width:60%;font-size:14px; margin-top: 4px;" maxlength="50" />&nbsp;<span id="select_source">[选择来源]</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="spe_line" style="width:74px;height:35px;text-align: right;">常用来源：</td>
                                            <td class="spe_line" style="text-align: left; line-height:180%;">
                                                <icms id="source_common_list" type="list">
                                                    <item>
                                                        <![CDATA[<span class="btnSetSourceName">{f_SourceName}</span><br />]]>
                                                    </item>
                                                </icms>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_Author">作者：</label></td>
                                            <td class="spe_line" style="text-align: left">
                                                <input type="text" class="input_box" id="f_Author" name="f_Author" value="{Author}" style="width:95%;font-size:14px;" maxlength="50" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_DocumentNewsMainTag">主关键词：</label></td>
                                            <td class="spe_line" style="text-align: left">
                                                <input type="text" class="input_box" id="f_DocumentNewsMainTag" name="f_DocumentNewsMainTag" value="{DocumentNewsMainTag}" style="width:95%;font-size:14px;" maxlength="100" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_DocumentNewsTag">关键词：</label></td>
                                            <td class="spe_line" style="text-align: left">
                                                <input type="text" class="input_box" id="f_DocumentNewsTag" name="f_DocumentNewsTag" value="{DocumentNewsTag}" style="width:95%;font-size:14px;" maxlength="200" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="spe_line" style="height:35px;text-align: right;">加前缀：</td>
                                            <td class="spe_line" align="left" style="line-height:20px;">
                                                <icms id="quick_add_pre" type="list">
                                                    <item>
                                                        <![CDATA[<span style="cursor: pointer;" onclick="insertcontent('{f_documentquickcontent}');">{f_documentquickcontent}</span><br />]]>
                                                    </item>
                                                </icms>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height:35px;">文件上传：</td>
                                            <td align="left">
                                                <input id="file_upload_to_content" name="file_upload_to_content" type="file" class="input_box" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="/system_template/common/images/loading1.gif" style="display:none;" /><input id="btnUploadToContent" type="button" value="上传" />
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
                <div id="tabs-2">
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图1：</td>
                            <td class="spe_line" style="text-align: left">
                                <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                                       style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic1"
                                                                                                      style="cursor:pointer">[预览]</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图2：</td>
                            <td class="spe_line" style="text-align: left">
                                <input id="titlepic_upload2" name="titlepic_upload2" type="file" class="input_box" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic2" style="cursor:pointer">[预览]</span>
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
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图3：</td>
                            <td class="spe_line" style="text-align: left">
                                <input id="titlepic_upload3" name="titlepic_upload3" type="file" class="input_box" style="width:610px; background: #ffffff; margin-top: 3px;" /> <span id="preview_titlepic3" style="cursor:pointer">[预览]</span>
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
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">短标题：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_documentnewsshorttitle" name="f_documentnewsshorttitle" value="{documentnewsshorttitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">副标题：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_documentnewssubtitle" name="f_documentnewssubtitle" value="{documentnewssubtitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>

                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">引题：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_documentnewscitetitle" name="f_documentnewscitetitle" value="{documentnewscitetitle}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>

                        <tr>
                            <td class="spe_line" style="width:200px;height:65px;text-align: right;">摘要：<br /><br />
                                <input type="button" class="btn4" value="编写" onclick='showModalDialog("/js/plugins/editabstract.html", window, "dialogWidth:850px;dialogHeight:400px;help:no;scroll:no;status:no");'/>&nbsp;
                            </td>
                            <td class="spe_line" style="text-align: left"><textarea class="input_box" id="f_documentnewsintro" name="f_documentnewsintro" style=" width: 600px; height: 80px;font-size:14px;">{documentnewsintro}</textarea></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">直接转向网址：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_directurl" name="f_directurl" value="{directurl}" style=" width: 600px;font-size:14px;" maxlength="200" /> (设置直接转向网址后，文档将直接转向到该网址)</td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-3">
                    <div id="uploader">
                        <p>您的浏览器不支持 Flash, Silverlight, Gears, BrowserPlus 或 HTML5，不能使用组图上传功能</p>
                    </div>
                    <div class="spe_line" style=" line-height: 30px;"> 使用组图控件展示内容中的图片 
                        <select id="f_ShowPicMethod" name="f_ShowPicMethod">
                            <option value="0" {s_ShowPicMethod_0}>关闭</option>
                            <option value="1" {s_ShowPicMethod_1}>开启</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        附加水印：<input type="checkbox" id="batchattachwatermark" name="batchattachwatermark" /> (只支持jpg或jpeg图片)                         
                    </div>
                </div>
                <div id="tabs-4">

                    <table width="99%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">排序数字：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="inputnumber" id="f_sort" name="f_sort" value="{sort}" style=" width: 60px;font-size:14px;" maxlength="10" /> (输入数字，越大越靠前)</td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">是否热门：</td>
                            <td class="spe_line" style="text-align: left">
                                <input type="radio"  name="f_ishot" value="0" {r_ishot_0} /> 否
                                <input type="radio"  name="f_ishot" value="1" {r_ishot_1} /> 是     
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">推荐级别：</td>
                            <td class="spe_line" style="text-align: left">
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
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">显示时间：</td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_showdate" name="f_showdate" value="{showdate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" /> <input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showhour" name="f_showhour" value="{showhour}" maxlength="2" value="00" />:<input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showminute" name="f_showminute" value="{showminute}" maxlength="2" value="00" />:<input type="text" class="inputnumber" style=" width:20px;font-size:14px;" id="f_showsecond" name="f_showsecond" value="{showsecond}" maxlength="2" value="00" /> (在文档中显示出来的时间，可任意设置)
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">新闻类型：</td>
                            <td class="spe_line" style="text-align: left">
                                <select id="f_documentnewstype" name="f_documentnewstype">
                                    <option value="0" {s_documentnewstype_0}>常规新闻</option>
                                    <option value="5" {s_documentnewstype_5}>推送新闻</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">当前状态：</td>
                            <td class="spe_line" style="text-align: left">
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
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">新闻评论：</td>
                            <td class="spe_line" style="text-align: left">
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
                            <td class="spe_line" style="width:200px;height:35px;text-align: right;">心情表态：</td>
                            <td class="spe_line" style="text-align: left">
                                <select id="f_closeposition" name="f_closeposition">
                                    <option value="0" {s_closeposition_0}>开启</option>
                                    <option value="1" {s_closeposition_1}>关闭</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        </tr>
        </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续"
                                                                                                    type="button"
                                                                                                    onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        </form>
    </body>
</html>
