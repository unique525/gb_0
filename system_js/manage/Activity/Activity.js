
$(function(){
    $("#f_createdate").datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 3,
        showButtonPanel: true
    });

var today = new Date();
var s_year = today.getFullYear();
var s_month = today.getMonth()+1;
if(parseInt(s_month,10) <10){
    s_month = "0" + s_month;
    }else{
    s_month = s_month;
    }
var s_day = today.getDate()<10?"0"+today.getDate():today.getDate();

    //报名开始时间
    var s_applybegindateday = s_day;
    var s_applybegindatemontht = parseInt(s_month,10)+1;
    s_applybegindatemontht = parseInt(s_applybegindatemontht,10);
    var s_applybegindateyear = s_year;
    if(s_applybegindatemontht >12){
        s_applybegindatemontht = s_applybegindatemontht - 12;
        s_applybegindateyear = parseInt(s_applybegindateyear,10) + 1 ;
        }
    if(parseInt(s_applybegindatemontht,10) <10){
            s_applybegindatemontht = "0" + s_applybegindatemontht;
            }


        //报名截止时间
        var s_applyenddateday = s_day;
        var s_applyenddatemontht = parseInt(s_month,10)+2;
        s_applyenddatemontht = parseInt(s_applyenddatemontht,10);
        var s_applyenddateyear = s_year;
        if(s_applyenddatemontht >12){
            s_applyenddatemontht = s_applyenddatemontht - 12;
            s_applyenddateyear = parseInt(s_applyenddateyear,10) + 1 ;
            }
        if(parseInt(s_applyenddatemontht,10) < 10){
                s_applyenddatemontht = "0" + s_applyenddatemontht;
                }




            //活动开始时间(集合时间)
            var s_begindateday = s_day;
            var s_begindatemontht = parseInt(s_month,10)+2;
            s_begindatemontht = parseInt(s_begindatemontht,10);
            var s_begindateyear = s_year;
            if(s_begindatemontht >12){
                s_begindatemontht = s_begindatemontht - 12;
                s_begindateyear = parseInt(s_begindateyear,10) + 1 ;
                }
            if(parseInt(s_begindatemontht,10) <10){
                    s_begindatemontht = "0" + s_begindatemontht;
                    }

                //活动结束时间
                var s_endday = s_day;
                var s_endmontht = parseInt(s_month,10)+3;
                s_endmontht = parseInt(s_endmontht,10);
                var s_endyear = s_year;
                if(s_endmontht >12){
                    s_endmontht = s_endmontht - 12;
                    s_endyear = parseInt(s_endyear,10) + 1 ;
                    }
                if(parseInt(s_endmontht,10) <10){
                        s_endmontht = "0" + s_endmontht;
                        }

                    var s_date = today.getFullYear()+"-"+s_month+"-"+today.getDate();

                    var s_hour = today.getHours()<10?"0"+today.getHours():today.getHours();
                        var s_minute = today.getMinutes()<10?"0"+today.getMinutes():today.getMinutes();
                            var s_second = today.getSeconds()<10?"0"+today.getSeconds():today.getSeconds();

                                if($("#f_begindate").val() == "" && $("#f_begindate").val() != 'undefined'){
                    $("#f_begindate").val(s_year + "-"+s_month+"-"+s_day);
                                }
                                if($("#f_enddate").val() == "" && $("#f_enddate").val() != 'undefined'){
                    $("#f_enddate").val(s_endyear + "-"+s_endmontht+"-"+s_endday);
                                }
                                //报名开始时间
                                if($("#f_applybegindate").val() == "" && $("#f_applybegindate").val() != 'undefined'){
                    $("#f_applybegindate").val(s_applybegindateyear + "-" + s_applybegindatemontht + "-" + s_applybegindateday);
                                }
                                //报名截止时间
                                if($("#f_applyenddate").val() == "" && $("#f_applyenddate").val() != 'undefined'){
                    $("#f_applyenddate").val(s_applyenddateyear + "-" + s_applyenddatemontht + "-" + s_applyenddateday);
                                }
                                if($("#f_createdate").val() == "" && $("#f_createdate").val() != 'undefined'){
                    $("#f_createdate").val(s_year + "-"+s_month+"-"+s_day + " " + s_hour + ":"+s_minute+":"+s_second );
                                }

                                if($("#begin_showhour").val() == "" && $("#begin_showhour").val() != 'undefined'){
                    $("#begin_showhour").val(s_hour);
                }
                if($("#begin_showminute").val() == "" && $("#begin_showminute").val() != 'undefined'){
                    $("#begin_showminute").val(s_hour);
                }
                if($("#begin_showsecond").val() == "" && $("#begin_showsecond").val() != 'undefined'){
                    $("#begin_showsecond").val(s_hour);
                }

                if($("#end_showhour").val() == "" && $("#end_showhour").val() != 'undefined'){
                    $("#end_showhour").val(s_hour);
                }
                if($("#end_showminute").val() == "" && $("#end_showminute").val() != 'undefined'){
                    $("#end_showminute").val(s_hour);
                }
                if($("#end_showsecond").val() == "" && $("#end_showsecond").val() != 'undefined'){
                    $("#end_showsecond").val(s_hour);
                }

                if($("#applybegin_showhour").val() == "" && $("#applybegin_showhour").val() != 'undefined'){
                    $("#applybegin_showhour").val(s_hour);
                }
                if($("#applybegin_showminute").val() == "" && $("#applybegin_showminute").val() != 'undefined'){
                    $("#applybegin_showminute").val(s_hour);
                }
                if($("#applybegin_showsecond").val() == "" && $("#applybegin_showsecond").val() != 'undefined'){
                    $("#applybegin_showsecond").val(s_hour);
                }

                if($("#apply_showhour").val() == "" && $("#apply_showhour").val() != 'undefined'){
                    $("#apply_showhour").val(s_hour);
                }
                if($("#apply_showminute").val() == "" && $("#apply_showminute").val() != 'undefined'){
                    $("#apply_showminute").val(s_hour);
                }
                if($("#apply_showsecond").val() == "" && $("#apply_showsecond").val() != 'undefined'){
                    $("#apply_showsecond").val(s_hour);
                }

                $('#tabs').tabs();
                if(Request["id"] == undefined){

                    $("#btncontinue").css("display","inline");

                }
                var window_h = Request["height"];
                if(!window_h || window_h<=0){
                    window_h = 600;
                }
                $("#f_activitycontent").css("height", window_h - 205);
                $("#tabs-1").css("height", window_h - 650);
                $("#tabs-2").css("height", window_h + 360);
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

                $("#btnclose").click(function() {
                    var tab = parseInt(Request['tab'])-1;
                    self.parent.$('#tabs').tabs('remove',tab);
                });

                //组图上传

                $("#uploader").plupload({
                    // General settings
                    runtimes : 'flash,silverlight,html5,gears,html4',
                    url : '{rootpath}/common/index.php?a=uploadbatch&cid={cid}&type=7',
                    //url : '{rootpath}/js/plupload/examples/upload.php',
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
                        {title : "Image files", extensions : "jpg,gif,png"},
                        {title : "Zip files", extensions : "zip,avi"}
                    ],

                    // Flash settings
                    flash_swf_url : '../js/plupload/js/plupload.flash.swf',

                    // Silverlight settings
                    silverlight_xap_url : '../js/plupload/js/plupload.silverlight.xap',

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
                                        var htmlContent = tinyMCE.get('f_activitycontent').getContent();
                                        htmlContent = htmlContent + outfile;
                                        tinyMCE.get('f_activitycontent').setContent(htmlContent);
                                    });
                                }
                            }
                        }
                    }
                });

            });

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

                $.ajaxFileUpload({
                    url:'{f_funcurl}/index.php?a=common&f=upload&cid={cid}&type=7&returntype=0',
                    secureuri:false,
                    fileElementId:'fileToUpload',
                    dataType: 'json',
                    success: function (data, status)
                    {
                        if(typeof(data.error) != 'undefined')
                        {
                            if(data.error != ''){
                                alert(data.error);
                            }
                            else{
                                var uploadurl = "{f_funcurl}" + data.fileurl;
                                //alert(data["fileurl"]);
                                $('#f_titlepic').val(uploadurl);

                            }
                        }
                    },
                    error: function (data, status, e)
                    {
                        alert("err");
                        alert(e);
                    }
                })
                return false;

            }

            function insertcontent(_value){
                var today = new Date();
                var month = today.getMonth()+1;
                var day = today.getDate();
                _value = _value.replace("{month}",month);
                _value = _value.replace("{day}",day);
                var htmlContent = tinyMCE.get('f_activitycontent').getContent();
                htmlContent = _value + htmlContent;
                tinyMCE.get('f_activitycontent').setContent(htmlContent);
            }


            function sub()
            {
                var cansub = 1;
                if($('#f_activitysubject').val() == ''){
                    alert("请输入标题");
                    $('#f_activitysubject').focus();
                    cansub = 0;
                }
                if($('#province').val() == ''){
                    alert("请选择省份");
                    cansub = 0;
                }
                $('#f_province').val($('#province').val());
                if($('#city').val() == ''){
                    alert("请选择城市");
                    cansub = 0;
                }
                if($('#f_activityclassid').val() == ''){
                    alert("请选择活动分类");
                    cansub = 0;
                }

                if($('#f_activityaddress').val() == ''){
                    alert('请输入活动地点！');
                    $('#f_activityaddress').focus();
                    cansub = 0;
                    return;
                }
                if($('#f_assemblyaddress').val() == ''){
                    alert('请输入集合地点！');
                    $('#f_assemblyaddress').focus();
                    cansub = 0;
                    return;
                }
                /**
                if($('#f_usercountlimit').val() == ''){
                    alert('请输入人数限制！');
                    $('#f_usercountlimit').focus();
                    cansub = 0;
                    return;
                }
                if($('#f_activityfee').val() == ''){
                    alert('请输入活动费用！');
                    $('#f_activityfee').focus();
                    cansub = 0;
                    return;
                }
                 ***/
                var begindate = $("#f_begindate").val()+$('#begin_showhour').val()+$('#begin_showminute').val()+$('#begin_showsecond').val();
                var enddate = $("#f_enddate").val()+$('#end_showhour').val()+$('#end_showminute').val()+$('#end_showsecond').val();
                var applybegindate = $("#f_applybegindate").val()+$('#applybegin_showhour').val()+$('#applybegin_showminute').val()+$('#applybegin_showsecond').val();
                var applyenddate = $("#f_applyenddate").val()+$('#apply_showhour').val()+$('#apply_showminute').val()+$('#apply_showsecond').val();

                if(begindate >  enddate ){
                    alert('开始时间(集合时间)不能大于活动结束时间！ 开始时间为：'+ $("#f_begindate").val()+" "+$('#begin_showhour').val()+"时"+$('#begin_showminute').val()+"分"+$('#begin_showsecond').val() + " 活动结束时间为：" + $("#f_enddate").val()+" "+$('#end_showhour').val()+"时"+$('#end_showminute').val()+"分"+$('#end_showsecond').val());
                    cansub = 0;
                    return;
                }

                if(applyenddate >  enddate ){
                    alert('报名截止时间不能大于活动结束时间！ 报名截止时间为：'+ $("#f_applybegindate").val()+" "+$('#applybegin_showhour').val()+"时"+$('#applybegin_showminute').val()+"分"+$('#applybegin_showsecond').val() + " 活动结束时间为：" + $("#f_enddate").val()+" "+$('#end_showhour').val()+"时"+$('#end_showminute').val()+"分"+$('#end_showsecond').val());
                    cansub = 0;
                    return;
                }

                if( applybegindate >=  applyenddate ){
                    alert('报名开始时间不能大于报名截止时间！ 报名开始时间：'+ $("#f_applybegindate").val()+" "+$('#applybegin_showhour').val()+"时"+$('#applybegin_showminute').val()+"分"+$('#applybegin_showsecond').val()+ " 报名截止时间：" + $("#f_applyenddate").val()+" "+$('#apply_showhour').val()+"时"+$('#apply_showminute').val()+"分"+$('#apply_showsecond').val());
                    cansub = 0;
                    return;
                }

                //                if($("#f_begindate").val() >=  $("#f_enddate").val()  ){
                //                    alert('开始时间(集合时间)不能大于活动结束时间！ 开始时间为：'+ $("#f_begindate").val()+ " 活动结束时间为：" + $("#f_enddate").val());
                //                    cansub = 0;
                //                    return;
                //                }
                //
                //                if($("#f_applyenddate").val() >=  $("#f_enddate").val()  ){
                //                    alert('报名截止时间不能大于活动结束时间！ 报名截止时间为：'+ $("#f_applyenddate").val()+ " 活动结束时间为：" + $("#f_enddate").val());
                //                    cansub = 0;
                //                    return;
                //                }
                //
                //                if( $("#f_applybegindate").val() >=  $("#f_applyenddate").val() ){
                //                    alert('报名开始时间不能大于报名截止时间！ 报名开始时间：'+ $("#f_applybegindate").val()+ " 报名截止时间：" + $("#f_applyenddate").val());
                //                    cansub = 0;
                //                    return;
                //                }

                if(cansub == 1){
                    $("#f_begindate").val($('#f_begindate').val() + " " + $('#begin_showhour').val() + ":"+$('#begin_showminute').val()+":"+$('#begin_showsecond').val());
                    $("#f_enddate").val($('#f_enddate').val() + " " + $('#end_showhour').val() + ":"+$('#end_showminute').val()+":"+$('#end_showsecond').val());
                    $("#f_applybegindate").val($('#f_applybegindate').val() + " " + $('#applybegin_showhour').val() + ":"+$('#applybegin_showminute').val()+":"+$('#applybegin_showsecond').val());
                    $("#f_applyenddate").val($('#f_applyenddate').val() + " " + $('#apply_showhour').val() + ":"+$('#apply_showminute').val()+":"+$('#apply_showsecond').val());
                    $('#f_province').val($('#province').val());
                    $('#f_city').val($('#city').val());
                    $('#mainform').submit();
                }
            }
