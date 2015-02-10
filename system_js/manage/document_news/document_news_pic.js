/**
 * document news pic
 */


/**
 * 新上传的图片加入图片管理栏
 * @param uploadFilePath 路径
 * @param uploadFileId id
 * @param showInPicSlider 是否加入组图控件
 */
function SetNewUploadPic(uploadFilePath,uploadFileId,showInPicSlider,templateName){
    //加入document_news_pic
    var fileName = uploadFilePath.toLowerCase();
    var fileEx = fileName.substr(fileName.lastIndexOf(".")+1);

    var text="在组图控件中隐藏";
    if(showInPicSlider==1){
        text="<"+"span style='color:green'>在组图控件中显示<"+"/span>";
    }
    if(fileEx=="jpg"||fileEx=="jpeg"||fileEx=="gif"||fileEx=="bmp"||fileEx=="png"||fileEx=="jpg"){
        var addImgLi="";
        addImgLi+='<li class="li_pic_img_item" id="UploadFileId_'+uploadFileId+'">';
        addImgLi+='<table class="pic_img_container"><tr><td><img style="cursor:pointer;" title="点击查看原始图片" class="pic_img" onclick="showOriImg(\'' + uploadFilePath + '\')" src="' + uploadFilePath + '" alt=""/></td></tr></table>';
        addImgLi+='<div class="pic_img_state" style=";">';
        addImgLi+='<div class="create_pic" style="" idvalue="'+uploadFileId+'" title="'+showInPicSlider+'" id="FormValues_'+uploadFileId+'">'+text+'</div>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/start.jpg" alt="" onclick="ModifyShowInPicSlider(\''+uploadFileId+'\', \'1\',\'create_pic\')"/>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/stop.jpg" alt="" onclick="ModifyShowInPicSlider(\''+uploadFileId+'\', \'0\',\'create_pic\')"/>';
        addImgLi+='<img class="btn_modify" src="/system_template/'+templateName+'/images/manage/delete.jpg" alt="删除" onclick="DeleteDocumentNewsPic(\''+uploadFileId+'\')"/>';
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
 * 修改内容图片是否显示在控件中的状态值 (本地，点确定后才与其他from参数一起在服务器操作)
 * @param idvalue id
 * @param state 状态
 * @param methodClassName 业务标识 (modify_pic / keep_pic)
 * @return
 */
function ModifyShowInPicSlider(idvalue, state, methodClassName) {
    if(state==0){
        $("#FormValues_"+idvalue).attr("title","0");
        $("#FormValues_"+idvalue).html("在组图控件中隐藏");
    }else if(state==1){
        $("#FormValues_"+idvalue).attr("title","1");
        $("#FormValues_"+idvalue).html("<"+"span style='color:green'>在组图控件中显示<"+"/span>");
    }

        $("#FormValues_"+idvalue).attr("class",methodClassName); //设置form操作标识
}

/**
 * 删除内容图片 (本地，点确定后才与其他from参数一起在服务器操作处理)
 * @param uploadFileId 附件id
 * @return string
 */
function DeleteDocumentNewsPic(uploadFileId){
    var operation=$("#FormValues_"+uploadFileId).attr("class");
    switch(operation){
        case "create_pic":
            $("#FormValues_"+uploadFileId).attr("class","");//新上传未入数据库的不设置操作，取消写入数据库
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
    $("#UploadFileId_"+uploadFileId).fadeOut();
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

