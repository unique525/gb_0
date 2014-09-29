/**
 * 站点题图
 */
window.UPLOAD_TABLE_TYPE_SITE = 1;
/**
 * 频道题图1
 */
window.UPLOAD_TABLE_TYPE_CHANNEL_1 = 5;
/**
 * 频道题图2
 */
window.UPLOAD_TABLE_TYPE_CHANNEL_2 = 6;
/**
 * 频道题图3
 */
window.UPLOAD_TABLE_TYPE_CHANNEL_3 = 7;

/**
 * 资讯题图1
 */
window.UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1 = 10;
/**
 * 资讯题图2
 */
window.UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2 = 11;
/**
 * 资讯题图3
 */
window.UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3 = 12;
/**
 * 资讯内容图
 */
window.UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT = 15;
/**
 * 后台任务内容图
 */
window.UPLOAD_TABLE_TYPE_MANAGE_TASK = 20;
/**
 * 后台任务回复内容图
 */
window.UPLOAD_TABLE_TYPE_MANAGE_TASK_REPLY = 21;

/**
 * 咨询问答内容图
 */
window.UPLOAD_TABLE_TYPE_QUESTION = 30;

/**
 * 产品题图1
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_1 = 40;

/**
 * 产品题图2
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_2 = 41;

/**
 * 产品题图3
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_3 = 42;

/**
 * 产品题图4
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_4 = 43;
/**
 * 产品内容图
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_CONTENT = 44;
/**
 * 产品参数
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_PARAM = 45;
/**
 * 产品参数类型
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE = 46;
/**
 * 产品参数类型选项
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE_OPTION = 47;
/**
 * 产品组图
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_PIC = 48;
/**
 * 产品品牌题图
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_BRAND = 49;
/**
 * 产品简介内容图片
 */
window.UPLOAD_TABLE_TYPE_PRODUCT_BRAND_INTRO = 50;


/**
 * 活动题图1
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_1 = 60;
/**
 * 活动题图2
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_2 = 61;
/**
 * 活动题图3
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_3 = 62;
/**
 * 活动花絮
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_PIC = 63;


/**
 * 会员头像
 */
window.UPLOAD_TABLE_TYPE_USER_AVATAR = 70;

/**
 * 会员组图标
 */
window.UPLOAD_TABLE_TYPE_USER_GROUP = 71;
/**
 * 会员相册封面
 */
window.UPLOAD_TABLE_TYPE_USER_ALBUM_COVER = 72;
/**
 * 会员相册相片
 */
window.UPLOAD_TABLE_TYPE_USER_ALBUM_PIC = 73;
/**
 * 会员等级图标
 */
window.UPLOAD_TABLE_TYPE_USER_LEVEL = 74;
/**
 * 会员附件
 */
window.UPLOAD_TABLE_TYPE_USER_ATTACHMENT = 75;
/**
 * 会员签名内容图
 */
window.UPLOAD_TABLE_TYPE_USER_SIGN = 76;
/**
 * 会员心情图标
 */
window.UPLOAD_TABLE_TYPE_USER_MOOD = 77;

/**
 * 友情链接类站点图标
 */
window.UPLOAD_TABLE_TYPE_SITE_LINK = 80;

/**
 * 自定义页面类内容图
 */
window.UPLOAD_TABLE_TYPE_SITE_CONTENT = 82;
/**
 * 站点配置内容图
 */
window.UPLOAD_TABLE_TYPE_SITE_CONFIG = 84;

/**
 * 活动表单类内容图
 */
window.UPLOAD_TABLE_TYPE_CUSTOM_FORM = 90;

/**
 * 投票选项图标
 */
window.UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM = 110;
/**
 * 考试试题内容图
 */
window.UPLOAD_TABLE_TYPE_EXAM_QUESTION = 120;

/**
 * 分类信息 题图1
 */
window.UPLOAD_TABLE_TYPE_INFORMATION_TITLE_PIC_1 = 130;
/**
 * 分类信息 内容图
 */
window.UPLOAD_TABLE_TYPE_INFORMATION_CONTENT = 135;


/**
 * 广告内容图
 */
window.UPLOAD_TABLE_TYPE_SITE_AD_CONTENT = 150;



/**
 * 论坛版块图标1
 */
window.UPLOAD_TABLE_TYPE_FORUM_PIC_1 = 200;
/**
 * 论坛版块图标2
 */
window.UPLOAD_TABLE_TYPE_FORUM_PIC_2 = 201;
/**
 * 论坛版块帖子内容图
 */
window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT = 204;
/**
 * 论坛 顶部信息内容图
 */
window.UPLOAD_TABLE_TYPE_FORUM_TOP_INFO_CONTENT = 205;
/**
 * 论坛 底部信息内容图
 */
window.UPLOAD_TABLE_TYPE_FORUM_BOT_INFO_CONTENT = 206;

/**
 * 论坛 LOGO图
 */
window.UPLOAD_TABLE_TYPE_FORUM_LOGO = 207;
/**
 * 论坛 背景图
 */
window.UPLOAD_TABLE_TYPE_FORUM_BACKGROUND_PIC = 208;

/**
 * 格式化上传文件的返回结果信息
 * @param {int} resultMessage 错误编码
 * @returns {string} 错误提示
 * @constructor
 */
function FormatResultMessage(resultMessage){
    var result = "";
    switch (resultMessage){
        case 115100:
            result = "上传文件预检查：成功";
            break;
        case 115101:
            result = "上传文件结果：没有错误";
            break;
        case -115100:
            result = "上传文件结果：未操作";
            break;
        case -115121:
            result = "上传文件结果：$_FILE为空";
            break;
        case -115120:
            result = "上传文件结果：PHP temp文件夹未设置";
            break;
        case -115101:
            result = "上传文件结果：文件太大";
            break;
        case -115102:
            result = "上传文件结果：文件太大，超出了HTML表单的限制";
            break;
        case -115103:
            result = "上传文件结果：文件中只有一部分内容完成了上传";
            break;
        case -115104:
            result = "上传文件结果：没有找到要上传的文件";
            break;
        case -115105:
            result = "上传文件结果：服务器临时文件夹丢失";
            break;
        case -115106:
            result = "上传文件结果： 文件写入到临时文件夹出错";
            break;
        case -115107:
            result = "上传文件结果：文件夹没有写入权限";
            break;
        case -115108:
            result = "上传文件结果：扩展使文件上传停止";
            break;
        case -115109:
            result = "上传文件结果：没有可以显示的错误信息";
            break;
        case -115110:
            result = "上传文件结果：文件类型错误，不允许此类文件上传";
            break;
        case -115111:
            result = "上传文件结果：生成上传文件路径和文件名时出错";
            break;
        case -115112:
            result = "上传文件结果：移动上传文件到目标路径时失败";
            break;
    }


    return result;

}

/**
 * ajax上传
 * @param {string} fileElementId 上传文件控件name
 * @param {int} tableType 表类型
 * @param {int} tableId 表id
 * @param {object} editor 编辑控制对象
 * @param {object} fUploadFile 存储上传文件id列表的控件对象
 * @param {int} attachWatermark 是否加水印
 * @param {string} loadingImageId loading图的id
 * @param {string} inputTextId 传入要设置结果值的input控件id
 */
function AjaxFileUpload(fileElementId,tableType,tableId,editor,fUploadFile,attachWatermark,loadingImageId,inputTextId)
{
    if(loadingImageId == undefined || loadingImageId == null){
        loadingImageId = "loading";
    }
    $(document).ajaxStart(function() {
        $( "#"+loadingImageId ).show();
    });
    $(document).ajaxComplete(function() {
        $( "#"+loadingImageId ).hide();
    });


    $.ajaxFileUpload({
        url:'/default.php?mod=upload_file&a=async_upload&file_element_name='+fileElementId+'&table_type='+tableType+'&table_id='+tableId+'&attach_watermark='+attachWatermark,
        secureUri:false,
        fileElementId:fileElementId,
        dataType: 'json',
        success: function (data, status)
        {
            if(typeof(data.error) != 'undefined')
            {
                if(parseInt(data.error)>0){ //ok

                    if(fUploadFile != undefined && fUploadFile != null){
                        var uploadFiles = fUploadFile.val();
                        uploadFiles = uploadFiles + "," + data.upload_file_id;
                        fUploadFile.val(uploadFiles);
                    }



                    if(editor != undefined && editor != null){

                        var uploadFilePath = UploadFileFormatHtml(data.upload_file_path);

                        editor.pasteHTML("<br /><br />"+uploadFilePath);

                    }

                    if(inputTextId != undefined && inputTextId != null){
                        $( "#"+inputTextId ).val(uploadFilePath);
                    }

                }else{

                    alert(FormatResultMessage(parseInt(data.error)));

                }
            }
        },
        error: function (data, status, e)
        {
            alert(e);
        }
    });
}
function UploadFileFormatHtml(fileName){
    fileName = fileName.toLowerCase();
    var fileEx = fileName.substr(fileName.lastIndexOf(".")+1);
    var url = '';
    switch(fileEx){
        case "jpg":
            url =  '<img src="'+fileName+'" />';
            break;
        case "jpeg":
            url =  '<img src="'+fileName+'" />';
            break;
        case "gif":
            url =  '<img src="'+fileName+'" />';
            break;
        case "bmp":
            url =  '<img src="'+fileName+'" />';
            break;
        case "png":
            url =  '<img src="'+fileName+'" />';
            break;
        case "swf":
            url = '';
            //return '<embed src="'+ filename + '" id="' + filename + '_SWF" width="200" height="100" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
            url += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="400" height="400">';
            url += '<param name="movie" value="' + fileName + '">';
            url += '</object>';
            break;
        case "flv":
            url = '';
            url += '<scr'+'ipt type="text/javascript" src="/public_js/jwplayer.js"></scr'+'ipt>';
            url += '<div id="mediaspace"></div>';
            url += '<scr'+'ipt type="text/javascript">';
            url += 'jwplayer("mediaspace").setup({';
            url += '"flashplayer": "/public_js/jwplayer.swf",';
            url += 'type:"http",';
            url += '"file": "'+fileName+'",';
            url += '"image": "",';
            url += '"streamer": "start",';
            url += '"autostart": "true",';
            url += '"controlbar": "bottom",';
            url += '"width": "500",';
            url += '"height": "430"';
            url += '});';
            url += '</scr'+'ipt>';
            break;
        case "mp4":
            url = '';
            url += '<scr'+'ipt type="text/javascript" src="/public_js/jwplayer.js"></scr'+'ipt>';
            url += '<div id="mediaspace"></div>';
            url += '<scr'+'ipt type="text/javascript">';
            url += 'jwplayer("mediaspace").setup({';
            url += '"flashplayer": "/public_js/jwplayer.swf",';
            url += 'type:"http",';
            url += '"file": "'+fileName+'",';
            url += '"image": "",';
            url += '"streamer": "start",';
            url += '"autostart": "true",';
            url += '"controlbar": "bottom",';
            url += '"width": "500",';
            url += '"height": "430"';
            url += '});';
            url += '</scr'+'ipt>';
            break;
        case "wmv":
            url = '';
            url += '<object classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" type="application/x-oleobject" width="400" height="300" id="MediaPlayer">';
            url += '<param name="URL" value="' + fileName + '">';
            url += '<param name="UIMode" value="full">';
            url += '<param name="AutoStart" value="true">';
            url += '<param name="Enabled" value="true">';
            url += '<param name="enableContextMenu" value="true">';
            url += '</object>';
            break;
        case "rmvb":
            url = '';
            url += '<object width="400" height="300" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">';
            url += '<param name="CONTROLS" value="ImageWindow">';
            url += '<param name="CONSOLE" value="Video">';
            url += '<param name="CENTER" value="TRUE">';
            url += '<param name="MAINTAINSPECT"  value="TRUE">';
            url += '</object>'; //定义播放界面
            url += '<object width="400" height="30" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">';
            url += '<param name="CONTROLS" value="StatusBar">';
            url += '<param name="CONSOLE" value="Video">';
            url += '</object>'; //定义状态栏
            url += '<object width="400" height="30"  classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">';
            url += '<param name="CONTROLS" value="ControlPanel">';
            url += '<param name="CONSOLE" value="Video">';
            url += '<param name="SRC" value="' + fileName + '>';
            url += '<param name="AUTOSTART" value="true">';
            url += '<param name="PREFETCH" value="0">';
            url += '<param name="NUMLOOP" value="0">';
            url += '</object>';
            break;
        case "rm":
            url = '';
            url += "<object width=\"400\" height=\"300\" classid=\"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA\">";
            url += "<param name=\"CONTROLS\" value=\"ImageWindow\">";
            url += "<param name=\"CONSOLE\" value=\"Video\">";
            url += '<param name="CENTER" value="TRUE">';
            url += '<param name="MAINTAINSPECT"  value="TRUE">';
            url += '</object>'; //定义播放界面
            url += '<object width="400" height="30" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">';
            url += '<param name="CONTROLS" value="StatusBar">';
            url += '<param name="CONSOLE" value="Video">';
            url += '</object>'; //定义状态栏
            url += '<object width="400" height="30"  classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA">';
            url += '<param name="CONTROLS" value="ControlPanel">';
            url += '<param name="CONSOLE" value="Video">';
            url += '<param name="SRC" value="' + fileName + '>';
            url += '<param name="AUTOSTART" value="true">';
            url += '<param name="PREFETCH" value="0">';
            url += "<param name=\"NUMLOOP\" value=\"0\">";
            url += "</object>";
            break;
        default:
            url = fileName;
            break;
    }
    return url;
}
