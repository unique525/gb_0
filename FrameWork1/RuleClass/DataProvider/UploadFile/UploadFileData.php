<?php

/**
 * 公共 上传文件 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_UploadFile
 * @author zhangchi
 */
class UploadFileData extends BaseData
{

    /**
     * 站点题图
     */
    const UPLOAD_TABLE_TYPE_SITE = 1;
    /**
     * 频道题图1
     */
    const UPLOAD_TABLE_TYPE_CHANNEL_1 = 5;
    /**
     * 频道题图2
     */
    const UPLOAD_TABLE_TYPE_CHANNEL_2 = 6;
    /**
     * 频道题图3
     */
    const UPLOAD_TABLE_TYPE_CHANNEL_3 = 7;

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
     * 产品题图1
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_1 = 40;

    /**
     * 产品题图2
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_2 = 41;

    /**
     * 产品题图3
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_3 = 42;

    /**
     * 产品题图4
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_TITLE_PIC_4 = 43;
    /**
     * 产品内容图
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_CONTENT = 44;
    /**
     * 产品参数
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PARAM = 45;
    /**
     * 产品参数类型
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE = 46;
    /**
     * 产品参数类型选项
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PARAM_TYPE_OPTION = 47;
    /**
     * 产品组图
     */
    const UPLOAD_TABLE_TYPE_PRODUCT_PIC = 48;


    /**
     * 广告内容图
     */
    const UPLOAD_TABLE_TYPE_AD_CONTENT = 50;
    /**
     * 活动内容图
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_1 = 60;
    /**
     * 活动内容图
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_2 = 61;
    /**
     * 活动内容图
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_TITLE_PIC_3 = 62;
    /**
     * 活动花絮
     */
    const UPLOAD_TABLE_TYPE_ACTIVITY_PIC = 63;


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
     * 论坛版块帖子内容图
     */
    const UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT = 104;
    /**
     * 投票选项图标
     */
    const UPLOAD_TABLE_TYPE_VOTE_SELECT_ITEM = 110;
    /**
     * 考试试题内容图
     */
    const UPLOAD_TABLE_TYPE_EXAM_QUESTION = 120;


    /**
     * 上传文件增加到数据表
     * @param string $uploadFileName 文件名
     * @param int $uploadFileSize 文件大小
     * @param int $uploadFileExtentionName 文件类型（扩展名）
     * @param string $uploadFileOrgName 文件原名称
     * @param string $uploadFilePath 文件路径
     * @param int $tableType 对应表类型
     * @param int $tableId 对应表id
     * @param int $manageUserId 后台管理员id
     * @param int $userId 会员id
     * @param string $uploadFileTitle 文件标题
     * @param string $uploadFileInfo 文件介绍
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 新增的上传文件id
     */
    public function Create(
        $uploadFileName,
        $uploadFileSize,
        $uploadFileExtentionName,
        $uploadFileOrgName,
        $uploadFilePath,
        $tableType,
        $tableId,
        $manageUserId,
        $userId,
        $uploadFileTitle = '',
        $uploadFileInfo = '',
        $isBatchUpload = 0
    )
    {
        $sql = "INSERT INTO " . self::TableName_UploadFile . "
            (UploadFileName,UploadFileSize,UploadFileExtentionName,
            UploadFileOrgName,UploadFilePath,TableType,
            TableId,ManageUserId,UserId,CreateDate,UploadFileTitle,
            UploadFileInfo,IsBatchUpload)

            VALUES

           (:UploadFileName,:UploadFileSize,:UploadFileExtentionName,
           :UploadFileOrgName,:UploadFilePath,:TableType,
           :TableId,:ManageUserId,:UserId,now(),:UploadFileTitle,
           :UploadFileInfo,:IsBatchUpload);";

        $uploadFilePath = str_ireplace("../../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("../", "/", $uploadFilePath);
        $uploadFilePath = str_ireplace("./", "/", $uploadFilePath);

        $uploadFilePath = str_ireplace(DIRECTORY_SEPARATOR, "/", $uploadFilePath);

        $dataProperty = new DataProperty();
        $dataProperty->AddField("UploadFileName", $uploadFileName);
        $dataProperty->AddField("UploadFileSize", $uploadFileSize);
        $dataProperty->AddField("UploadFileExtentionName", $uploadFileExtentionName);
        $dataProperty->AddField("UploadFileOrgName", $uploadFileOrgName);
        $dataProperty->AddField("UploadFilePath", $uploadFilePath);
        $dataProperty->AddField("TableType", $tableType);
        $dataProperty->AddField("TableId", $tableId);
        $dataProperty->AddField("ManageUserId", $manageUserId);
        $dataProperty->AddField("UserId", $userId);
        $dataProperty->AddField("UploadFileTitle", $uploadFileTitle);
        $dataProperty->AddField("UploadFileInfo", $uploadFileInfo);
        $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
        $result = $this->dbOperator->LastInsertId($sql, $dataProperty);
        return $result;
    }

    /**
     * 修改上传文件路径（移动客户端使用）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param int $uploadFileMobilePath 上传文件路径（移动客户端使用）（文件夹+文件名）
     * @return int 操作结果
     */
    public function ModifyUploadFileMobilePath($uploadFileId, $uploadFileMobilePath)
    {
        if ($uploadFileId > 0 && !empty($uploadFileMobilePath)) {
            $sql = "UPDATE " . self::TableName_UploadFile . "
                        SET UploadFileMobilePath=:UploadFileMobilePath
                        WHERE UploadFileId=:UploadFileId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileMobilePath", $uploadFileMobilePath);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 修改上传文件路径（平板客户端使用）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param int $uploadFilePadPath 上传文件路径（平板客户端使用）（文件夹+文件名）
     * @return int 操作结果
     */
    public function ModifyUploadFilePadPath($uploadFileId, $uploadFilePadPath)
    {
        if ($uploadFileId > 0 && !empty($uploadFilePadPath)) {
            $sql = "UPDATE " . self::TableName_UploadFile . "
                        SET UploadFilePadPath=:UploadFilePadPath
                        WHERE UploadFileId=:UploadFileId;";

            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFilePadPath", $uploadFilePadPath);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 修改TableId
     * @param int $uploadFileId 上传文件id
     * @param int $tableId 对应表id
     * @return int 操作结果
     */
    public function ModifyTableId($uploadFileId, $tableId)
    {
        if ($uploadFileId > 0 && $tableId > 0) {
            $sql = "UPDATE " . self::TableName_UploadFile . " SET TableId=:TableId WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("TableId", $tableId);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 修改文件记录“是否是批量上传的文件”字段
     * @param int $uploadFileId 上传文件id
     * @param int $isBatchUpload 是否是批量上传的文件
     * @return int 返回影响的行数
     */
    public function ModifyIsBatchUpload($uploadFileId, $isBatchUpload)
    {
        if ($uploadFileId > 0) {
            $sql = "UPDATE " . self::TableName_UploadFile . " SET IsBatchUpload=:IsBatchUpload WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("IsBatchUpload", $isBatchUpload);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 修改文件大小
     * @param int $uploadFileId 上传文件id
     * @param int $fileSize 文件大小(单位字节 B)
     * @return int 操作结果
     */
    public function ModifyFileSize($uploadFileId, $fileSize)
    {
        if ($uploadFileId > 0 && $fileSize > 0) {
            $sql = "UPDATE " . self::TableName_UploadFile . " SET UploadFileSize=:UploadFileSize WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileSize", $fileSize);
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->dbOperator->Execute($sql, $dataProperty);
            return $result;
        } else {
            return -1;
        }
    }

    /**
     * 取得上传文件名称
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件名称
     */
    public function GetUploadFileName($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_name.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileName
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件扩展名称
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件扩展名称
     */
    public function GetUploadFileExtentionName($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_extention_name.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileExtentionName
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件大小(字节)
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return int 上传文件大小(字节)
     */
    public function GetUploadFileSize($uploadFileId, $withCache)
    {
        $result = -1;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_size.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileSize
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件原始名称
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件原始名称
     */
    public function GetUploadFileOrgName($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_org_name.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileOrgName
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（文件夹+文件名）
     */
    public function GetUploadFilePath($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_path.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFilePath
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（移动客户端使用）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（移动客户端使用）（文件夹+文件名）
     */
    public function GetUploadFileMobilePath($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_mobile_path.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileMobilePath
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（平板客户端使用）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（平板客户端使用）（文件夹+文件名）
     */
    public function GetUploadFilePadPath($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_pad_path.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFilePadPath
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（缩略图1）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（缩略图1）（文件夹+文件名）
     */
    public function GetUploadFileThumbPath1($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_thumb_path1.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileThumbPath1
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（缩略图2）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（缩略图2）（文件夹+文件名）
     */
    public function GetUploadFileThumbPath2($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_thumb_path2.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileThumbPath2
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（缩略图3）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（缩略图3）（文件夹+文件名）
     */
    public function GetUploadFileThumbPath3($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_thumb_path3.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileThumbPath3
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（水印图1）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（水印图1）（文件夹+文件名）
     */
    public function GetUploadFileWatermarkPath1($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_watermark_path1.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileWatermarkPath1
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（水印图2）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（水印图2）（文件夹+文件名）
     */
    public function GetUploadFileWatermarkPath2($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_watermark_path2.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileWatermarkPath2
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（压缩图1，降低质量）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（压缩图1，降低质量）（文件夹+文件名）
     */
    public function GetUploadFileCompressPath1($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_compress_path1.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileCompressPath1
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件路径（压缩图2，降低质量）（文件夹+文件名）
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件路径（压缩图2，降低质量）（文件夹+文件名）
     */
    public function GetUploadFileCompressPath2($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_compress_path2.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileCompressPath2
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件标题
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件标题
     */
    public function GetUploadFileTitle($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_title.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileTitle
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得上传文件简介
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return string 上传文件简介
     */
    public function GetUploadFileIntro($uploadFileId, $withCache)
    {
        $result = "";
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_upload_file_intro.cache_' . $uploadFileId . '';
            $sql = "SELECT UploadFileIntro
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfStringValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }


    /**
     * 取得对应表类型
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return int 对应表类型
     */
    public function GetTableType($uploadFileId, $withCache)
    {
        $result = -1;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_table_type.cache_' . $uploadFileId . '';
            $sql = "SELECT TableType
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得对应表id
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return int 对应表id
     */
    public function GetTableId($uploadFileId, $withCache)
    {
        $result = -1;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_table_id.cache_' . $uploadFileId . '';
            $sql = "SELECT TableId
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得后台管理帐号id
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return int 后台管理帐号id
     */
    public function GetManageUserId($uploadFileId, $withCache)
    {
        $result = -1;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_manage_user_id.cache_' . $uploadFileId . '';
            $sql = "SELECT ManageUserId
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得会员id
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return int 会员id
     */
    public function GetUserId($uploadFileId, $withCache)
    {
        $result = -1;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_user_id.cache_' . $uploadFileId . '';
            $sql = "SELECT UserId
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
        }
        return $result;
    }

    /**
     * 取得是否是批量上传
     * @param int $uploadFileId 上传文件id
     * @param bool $withCache 是否从缓冲中取
     * @return bool 是否是批量上传
     */
    public function GetIsBatchUpload($uploadFileId, $withCache)
    {
        $result = false;
        if ($uploadFileId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'upload_file_data';
            $cacheFile = 'upload_file_get_is_batch_upload.cache_' . $uploadFileId . '';
            $sql = "SELECT IsBatchUpload
                        FROM " . self::TableName_UploadFile . "
                        WHERE UploadFileId=:UploadFileId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("UploadFileId", $uploadFileId);
            $result = $this->GetInfoOfIntValue(
                $sql,
                $dataProperty,
                $withCache,
                $cacheDir,
                $cacheFile
            );
            if (intval($result) > 0) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * 取得一条上传文件记录，返回上传文件对象
     * @param int $uploadFileId 上传文件id
     * @return UploadFile 返回上传文件对象
     */
    public function Fill($uploadFileId)
    {
        $uploadFile = new UploadFile();
        if ($uploadFileId > 0) {

            $sql = "SELECT * FROM " . self::TableName_UploadFile . "
                    WHERE " . self::TableId_UploadFile . "=:" . self::TableId_UploadFile . ";";
            $dataProperty = new DataProperty();
            $dataProperty->AddField(self::TableId_UploadFile, $uploadFileId);

            $result = $this->dbOperator->GetArray($sql, $dataProperty);

            self::FillUploadFile($result, $uploadFile);
        }

        return $uploadFile;
    }

    /**
     * 将数组中的内容填充到对象中
     * @param $arr
     * @param $uploadFile
     */
    public function FillUploadFile($arr, &$uploadFile)
    {
        if (!empty($arr)) {

            if (!empty($arr["UploadFileId"])) {
                $uploadFile->UploadFileId = intval($arr["UploadFileId"]);
            }
            if (!empty($arr["UploadFilePath"])) {
                $uploadFile->UploadFilePath = strval($arr["UploadFilePath"]);
            }

        }
    }

} 