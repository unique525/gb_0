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
 * 广告内容图
 */
window.UPLOAD_TABLE_TYPE_AD_CONTENT = 50;
/**
 * 活动内容图
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC = 60;
/**
 * 活动花絮
 */
window.UPLOAD_TABLE_TYPE_ACTIVITY_PIC = 61;


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
 * 论坛版块图标1
 */
window.UPLOAD_TABLE_TYPE_FORUM_PIC_1 = 100;
/**
 * 论坛版块图标2
 */
window.UPLOAD_TABLE_TYPE_FORUM_PIC_2 = 101;
/**
 * 论坛版块帖子内容图
 */
window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT = 104;
/**
 * 投票选项图标
 */
window.UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM = 110;
/**
 * 考试试题内容图
 */
window.UPLOAD_TABLE_TYPE_EXAM_QUESTION = 120;




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
