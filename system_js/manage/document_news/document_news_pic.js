/**
 * document news pic
 */


/**
 * 新上传的图片加入图片管理栏
 * @param uploadFilePath 路径
 * @param uploadFileId id
 * @param showInPicSlider 是否加入组图控件
 * @param templateName 样式
 */
function SetNewUploadPic(uploadFilePath,uploadFileId,showInPicSlider,templateName){
    //加入document_news_pic
    var fileName = uploadFilePath.toLowerCase();
    var fileEx = fileName.substr(fileName.lastIndexOf(".")+1);

    var text="<"+"span style='float:left'>在组图控件中隐藏<"+"/span>";
    if(showInPicSlider==1){
        text="<"+"span style='color:green;float:left'>在组图控件中显示<"+"/span>";
    }
    if(fileEx=="jpg"||fileEx=="jpeg"||fileEx=="gif"||fileEx=="bmp"||fileEx=="png"||fileEx=="jpg"){
        var addImgLi="";
        addImgLi+='<li class="li_pic_img_item" id="UploadFileId_'+uploadFileId+'">';
        addImgLi+='<div class="notice" id="notice_'+uploadFileId+'"></div>';
        addImgLi+='<table class="pic_img_container" cellspacing="0"><tr><td align="center" valign="center"><img style="cursor:pointer;" title="点击查看原始图片" class="pic_img" onclick="showOriImg(\'' + uploadFilePath + '\')" src="' + uploadFilePath + '" alt=""/></td></tr></table>';

        addImgLi+='<div class="pic_img_title" style="padding:3px 5px;">';
        addImgLi+='<input class="input_box" idvalue="'+uploadFileId+'" id="pic_title_'+uploadFileId+'" value="" onclick="SetEventForDocumentNewsPicTitle('+uploadFileId+')">';
        addImgLi+='</div>';
        addImgLi+='<div class="pic_img_state" style=";">';
        addImgLi+='<div class="create_pic" style="" idvalue="'+uploadFileId+'" title="'+showInPicSlider+'" id="FormValues_'+uploadFileId+'">'+text+'</div>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/start.jpg" alt="" onclick="ModifyShowInPicSlider(\''+uploadFileId+'\', \'1\',\'create_pic\')"/>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/stop.jpg" alt="" onclick="ModifyShowInPicSlider(\''+uploadFileId+'\', \'0\',\'create_pic\')"/>';
        addImgLi+='<a href="/default.php?secu=manage&mod=upload_file&m=modify&upload_file_id='+uploadFileId+'"><img class="btn_modify" src="/system_template/'+templateName+'/images/manage/edit.gif"title="编辑" /></a>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/delete.jpg" alt="删除" onclick="DeleteDocumentNewsPic(\''+uploadFileId+'\')"/>';
        addImgLi+='<div class="btn_update_title" style="float:right;cursor: pointer;display: none" idvalue="'+uploadFileId+'" title="{f_ShowInPicSlider}" id="update_pic_title_'+uploadFileId+'" onclick="AjaxUpdateDocumentNewsPicTitle('+uploadFileId+')" >点击修改</div>';
        addImgLi+='</div>';
        addImgLi+='</li>';
        $("#new_pic_list").append(addImgLi);
    }
}
/**
 * 汇总上传、删除、修改操作的内容图片到form 准备提交
 *
 */
function SetDocumentNewsPic(){
    var create_pic_list="";
    var modify_pic_list="";
    var delete_pic_list="";
    $(".create_pic").each(function(){
        create_pic_list+=","+$(this).attr("idvalue")+"_"+$(this).attr("title");
    });
    $(".modify_pic").each(function(){
        modify_pic_list+=","+$(this).attr("idvalue")+"_"+$(this).attr("title");
    });
    $(".delete_pic").each(function(){
        delete_pic_list+=","+$(this).attr("idvalue");
    });
    $("#create_pic_list").val(create_pic_list.substr(1));
    $("#modify_pic_list").val(modify_pic_list.substr(1));
    $("#delete_pic_list").val(delete_pic_list.substr(1));
}
/**
 * 修改内容图片是否显示在控件中的状态值 (由于新增文档还未生成DocumentNewsId,此处仅作记录，须点确定后才与其他from参数一起在服务端操作)
 * @param idvalue id
 * @param state 状态
 * @param methodClassName 业务标识 (modify_pic / keep_pic)
 * @return
 */
function ModifyShowInPicSlider(idvalue, state, methodClassName) {
    if(state==0){
        $("#FormValues_"+idvalue).attr("title","0");
        $("#FormValues_"+idvalue).html("<"+"span style='float:left'>在组图控件中隐藏<"+"/span>");
    }else if(state==1){
        $("#FormValues_"+idvalue).attr("title","1");
        $("#FormValues_"+idvalue).html("<"+"span style='color:green;float:left'>在组图控件中显示<"+"/span>");
    }

        $("#FormValues_"+idvalue).attr("class",methodClassName); //设置form操作标识
}

/**
 * 删除内容图片 (由于新增文档还未生成DocumentNewsId,此处仅作记录，须点确定后才与其他from参数一起在服务端操作处理)
 * @param uploadFileId 附件id
 * @return string
 */
function DeleteDocumentNewsPic(uploadFileId){
    var operation=$("#FormValues_"+uploadFileId).attr("class");
    switch(operation){
        case "create_pic":
            $("#FormValues_"+uploadFileId).attr("class","");//新上传未入数据库的不设置操作，取消写入数据库
            $("#UploadFileId_"+uploadFileId).fadeOut();
            break;
        case "keep_pic":
            $("#FormValues_"+uploadFileId).attr("class","delete_pic");//已在数据库的设置删除
            break;
        case "modify_pic":
            $("#FormValues_"+uploadFileId).attr("class","delete_pic");//已在数据库的设置删除
            break;
        default :
            $("#FormValues_"+uploadFileId).attr("class","");
            break;
    }
    $("#notice_"+uploadFileId).show();
    $("#pic_title_"+uploadFileId).css("color","red");
    $("#pic_title_"+uploadFileId).val("须点击下方‘确认并关闭’按钮完成删除");
    $("#pic_title_"+uploadFileId).attr("onclick","");
    $("#update_pic_title_"+uploadFileId).hide();


}


/***
 * 图片点击查看
 * @param imageSrc
 * @param imageWidth
 * @param imageHeight
 */
function loadImageDialog(imageSrc,imageWidth,imageHeight){
    var dialogWidth = imageWidth + 50;
    var dialogHeight = imageHeight + 50;
    $("#dialog_box").dialog({
        width: dialogWidth,
        height: dialogHeight
    });

    var imgHtml = '<' + 'img src="' + imageSrc + '" alt="" />';
    $("#dialog_content").html(imgHtml);
}

/***
 *
 * (查看图片)
 * @constructor
 */
function showOriImg(imagePath) {
    if(imagePath != undefined && imagePath.length>0){
        var image = new Image();
        image.src = imagePath;

        if(window.ActiveXObject) {
            image.onreadystatechange = function() {
                if(image.readyState == "loaded" || image.readyState == "complete") {
                    image.onreadystatechange = null;
                    loadImageDialog(image.src,image.width,image.height);
                }
            }
        } else {
            image.onload = function() {
                image.onload  = null;
                loadImageDialog(image.src,image.width,image.height);
            }
        }



    } else {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("还没有上传题图");
    }
}


/**
 * 设置事件(后台更新图片标题)
 * @int uploadFileId
 * @constructor
 */
function SetEventForDocumentNewsPicTitle(uploadFileId){
    var submitButton=document.getElementById("update_pic_title_"+uploadFileId);
    var notice = document.getElementById("notice_"+uploadFileId);
    submitButton.innerHTML="点击修改";
    submitButton.style.display="block";
    notice.style.display="block";
}
/**
 * 异步修改图片标题
 * @int uploadFileId
 * @constructor
 */
function AjaxUpdateDocumentNewsPicTitle(uploadFileId) {
    var submitButton=document.getElementById("update_pic_title_"+uploadFileId);
    var inputBox=document.getElementById("pic_title_"+uploadFileId);
    var notice = document.getElementById("notice_"+uploadFileId);
    var uploadFileTitle=inputBox.value;
    if(uploadFileId>0){
        $.ajax({
            url: "/default.php?secu=manage&mod=upload_file&m=async_modify_upload_file_title",
            data: {UploadFileId: uploadFileId,UploadFileTitle: uploadFileTitle},
            type: "POST",
            dataType: 'json',
            success: function(result){
                if(result>0){
                    submitButton.innerHTML='<a style="color:green">修改成功</a>';
                }else{
                    submitButton.innerHTML='<a style="color:red">修改失败</a>';
                }
                notice.style.display="none";
            }
        });
    }
}


/**
 * 异步修改内容图片是否显示在控件中的状态值 (只能操作已存在的稿件。新增文档的图片管理，须点确定后才与其他from参数一起在服务端操作)
 * @param idvalue id
 * @param state 状态
 * @param methodClassName 业务标识 (modify_pic / keep_pic)
 * @return
 */

$(function () {
    $(".btn_show_pic").click(function(){
        var documentNewsPicId=$(this).attr("idvalue");
        var uploadFileId=$(this).attr("alt");
        AsyncShowInPicSlider(documentNewsPicId,1,uploadFileId);
    })


    $(".btn_hide_pic").click(function(){
        var documentNewsPicId=$(this).attr("idvalue");
        var uploadFileId=$(this).attr("alt");
        AsyncShowInPicSlider(documentNewsPicId,0,uploadFileId);
    })
});


/**
 * 异步修改图片状态:是否显示在组图控件中 (只能操作已存在的稿件。新增文档的图片管理，须点确定后才与其他from参数一起在服务端操作)
 * @param documentNewsPicId 图片id
 * @param state 状态
 * @param uploadFileId 附件id
 * @return
 */
function AsyncShowInPicSlider(documentNewsPicId, state, uploadFileId) {
    $.ajax({
        url:"/default.php?secu=manage&mod=document_news_pic&m=async_modify_showing_state",
        data:{document_news_pic_id:documentNewsPicId,state:state},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#FormValues_" + uploadFileId).html(FormatSliderState(state));
            }
            else alert("修改失败，请点击下方‘确认并关闭按钮’修改或联系管理员");
        }
    });
}

function FormatSliderState(state){
    switch (state){
        case 0:
            return "<"+"span style='float:left'>在组图控件中隐藏<"+"/span>";
            break;
        case 1:
            return "<"+"span style='color:green;float:left'>在组图控件中显示<"+"/span>";
            break;
        default :
            return "<"+"span style='float:left'>未知<"+"/span>";
            break;
    }
}