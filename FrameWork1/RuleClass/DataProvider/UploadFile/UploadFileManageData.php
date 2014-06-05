<?php
/**
 * 后台管理 上传文件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @author zhangchi
 */
class UploadFileManageData extends BaseManageData {

    /**
     * 资讯题图1
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_1 = 10;
    /**
     * 资讯题图2
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_2 = 11;
    /**
     * 资讯题图3
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_3 = 12;
    /**
     * 资讯题图,移动终端使用
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_MOBILE = 13;
    /**
     * 资讯题图,平板电脑使用
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_TITLE_PIC_PAD = 14;
    /**
     * 资讯内容图
     */
    const UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT = 15;
    /**
     * 后台任务内容图
     */
    const UPLOAD_TABLE_TYPE_MANAGE_TASK = 20;
    /**
     * 后台任务回复内容图
     */
    const UPLOAD_TABLE_TYPE_MANAGE_TASK_REPLY = 21;

    /**
     * 咨询问答内容图
     */
    const UPLOAD_TABLE_TYPE_QUESTION = 30;

    /**
     * 产品题图
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC = 40;
    /**
     * 产品内容图
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_CONTENT = 41;
    /**
     * 产品参数
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PARAM_OPTION = 42;
    /**
     * 产品参数类型
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE = 43;


    /**
     * 广告内容图
     */
    const UPLOAD_TABLE_TYPE_AD_CONTENT = 50;
    /**
     * 活动内容图
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC = 60;
    /**
     * 活动花絮
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_PIC = 61;



    /**
     * 会员头像
     */
    const UPLOAD_TABLE_TYPE_USER_AVATAR = 70;

    /**
     * 会员组图标
     */
    const UPLOAD_TABLE_TYPE_USER_GROUP = 71;
    /**
     * 会员相册封面
     */
    const UPLOAD_TABLE_TYPE_USER_ALBUM_COVER = 72;
    /**
     * 会员相册相片
     */
    const UPLOAD_TABLE_TYPE_USER_ALBUM_PIC = 73;
    /**
     * 会员等级图标
     */
    const UPLOAD_TABLE_TYPE_USER_LEVEL = 74;
    /**
     * 会员附件
     */
    const UPLOAD_TABLE_TYPE_USER_ATTACHMENT = 75;
    /**
     * 会员签名内容图
     */
    const UPLOAD_TABLE_TYPE_USER_SIGN = 76;
    /**
     * 会员心情图标
     */
    const UPLOAD_TABLE_TYPE_USER_MOOD = 77;

    /**
     * 友情链接类站点图标
     */
    const UPLOAD_TABLE_TYPE_SITE_LINK = 80;

    /**
     * 自定义页面类内容图
     */
    const UPLOAD_TABLE_TYPE_SITE_CONTENT = 82;
    /**
     * 站点配置内容图
     */
    const UPLOAD_TABLE_TYPE_SITE_CONFIG = 84;

    /**
     * 活动表单类内容图
     */
    const UPLOAD_TABLE_TYPE_CUSTOM_FORM = 90;

    /**
     * 论坛版块图标1
     */
    const UPLOAD_TABLE_TYPE_FORUM_PIC_1 = 100;
    /**
     * 论坛版块图标2
     */
    const UPLOAD_TABLE_TYPE_FORUM_PIC_2 = 101;
    /**
     * 论坛版块图标 移动客户端
     */
    const UPLOAD_TABLE_TYPE_FORUM_PIC_MOBILE = 102;
    /**
     * 论坛版块图标 平板电脑
     */
    const UPLOAD_TABLE_TYPE_FORUM_PIC_PAD = 103;
    /**
     * 论坛版块帖子内容图
     */
    const UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT = 104;
    /**
     * 投票选项图标
     */
    const UPLOAD_TABLE_TYPE_VOTE_Select_ITEM = 110;
    /**
     * 考试试题内容图
     */
    const UPLOAD_TABLE_TYPE_EXAM_QUESTION = 120;

    /**
     * 上传文件增加到数据表
     * @param string $uploadFileName 文件名
     * @param int $uploadFileSize 文件大小
     * @param int $uploadFileType 文件类型（扩展名）
     * @param string $uploadFileOrgName 文件原名称
     * @param string $uploadFilePath 文件路径
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param int $uploadYear 上传时间：年
     * @param int $uploadMonth 上传时间：月
     * @param int $uploadDay 上传时间：日
     * @param int $manageUserId 后台管理员id
     * @param int $userId 会员id
     * @param string $uploadFileTitle 文件标题
     * @param string $uploadFileInfo 文件介绍
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 新增的上传文件id
     */
    public function Create($uploadFileName, $uploadFileSize, $uploadFileType, $uploadFileOrgName, $uploadFilePath, $tableType, $tableId, $uploadYear, $uploadMonth, $uploadDay, $manageUserId, $userId, $uploadFileTitle = '', $uploadFileInfo = '', $isBatchUpload = 0) {
        $sql = "INSERT INTO ".self::TableName_UploadFile."
            (UploadFileName,UploadFileSize,UploadFileType,UploadFileOrgName,UploadFilePath,TableType,TableId,UploadYear,UploadMonth,UploadDay,ManageUserId,UserId,CreateDate,UploadFileTitle,UploadFileInfo,IsBatchUpload) VALUES
           (:UploadFileName,:UploadFileSize,:UploadFileType,:UploadFileOrgName,:UploadFilePath,:TableType,:TableId,:UploadYear,:UploadMonth,:UploadDay,:ManageUserId,:UserId,now(),:UploadFileTitle,:UploadFileInfo,:IsBatchUpload);";

        $uploadFilePath = str_ireplace("../../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("./", "/", $uploadFilePath);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("UploadFileName", $uploadFileName);
        $dataProperty->AddField("UploadFileSize", $uploadFileSize);
        $dataProperty->AddField("UploadFileType", $uploadFileType);
        $dataProperty->AddField("UploadFileOrgName", $uploadFileOrgName);
        $dataProperty->AddField("UploadFilePath", $uploadFilePath);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("UploadYear", $uploadYear);
        $dataProperty->AddField("UploadMonth", $uploadMonth);
        $dataProperty->AddField("UploadDay", $uploadDay);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UploadFileTitle", $uploadFileTitle);
        $dataProperty->AddField("UploadFileInfo", $uploadFileInfo);
        $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改TableId
     * @param int $uploadFileId 上传文件id
     * @param int $tableId 对应表id
     * @return int 操作结果
     */
    public function ModifyTableId($uploadFileId, $tableId) {
        if ($uploadFileId > 0 && $tableId > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET TableId=:TableId WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }

    /**
     * 修改文件记录“是否是批量上传的文件”字段
     * @param int $uploadFileId 上传文件id
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 返回影响的行数
     */
    public function ModifyIsBatchUpload($uploadFileId, $isBatchUpload) {
        if ($uploadFileId > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET IsBatchUpload=:IsBatchUpload WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }

    /**
     * 修改文件大小
     * @param int $uploadFileId 上传文件id
     * @param int $fileSize 文件大小(单位字节 B)
     * @return int 操作结果
     */
    public function ModifyFileSize($uploadFileId, $fileSize) {
        if ($uploadFileId > 0 && $fileSize > 0) {
            $sql = "UPDATE ".self::TableName_UploadFile." SET UploadFileSize=:UploadFileSize WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileSize", $fileSize);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        }else{
            return -1;
        }
    }
}