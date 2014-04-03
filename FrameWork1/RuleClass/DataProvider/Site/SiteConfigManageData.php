<?php

/**
 * 后台管理 站点配置 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @author zhangchi
 */
class SiteConfigManageData extends BaseManageData {


    //string mid Short Message
    private $ArrSiteConfigTypes_1 = array(
        "UserSmsMessageContent",
        "RegisterWindowContent",
        "SchoolIntro",
        "SchoolFeature",
        "SchoolCapable",
        "SchoolService",
        "SchoolLeaderIntro"
    );
    //text
    private $ArrSiteConfigTypes_2 = array(
        "ForumTopInfo",
        "ForumAdTopIndex",
        "ForumBotInfo",
        "ForumAdBotIndex",
        "ForumAdTopTopicList",
        "ForumAdBotTopicList",
        "ForumAdTopTopicContent",
        "ForumAdBotTopicContent"
    );
    //int
    private $ArrSiteConfigTypes_3 = array(
        "OpenHomePage",
        "OpenRegisterWindow",
        "ForumNewPostCount",
        "ForumYesterdayPostCount",
        "ForumTopPostCount",
        "ForumReplyCount",
        "ForumPostCount",
        "ForumTopicPageSize",
        "ForumPostPageSize",
        "UserCount",
        "NewRegisterUserId",
        "ForumPicShowMode",
        "OpenFtpLog",
        "UserAlbumThumbWidth",
        "UserDefaultUserGroupIdForRole",
        "UserAlbumToBestMustVoteCount",
        "UserDefaultState",
        "UserRecDefaultState",
        "MailSmtpPort"
    );
    //number
    private $ArrSiteConfigTypes_4 = array(
        "UserCommissionOwn",
        "UserCommissionChild",
        "UserCommissionGrandson"
    );
    private $SiteId = 1;
    
    //公共配置
    private $OpenFtpLog = 0; //开启FTP传输日志记录
    private $MailSmtpHost = ""; // SMTP 服务器 
    private $MailSmtpUserName = "";// SMTP服务器用户名  
    private $MailSmtpPassword = "";// SMTP服务器密码 
    private $MailSmtpPort = 465;// SMTP服务器的端口号   
    private $MailFrom = "";//发件人地址
    private $MailReplyTo = "";//邮件回复地址

    private $MetaGenerator = "SenseCMS";
    private $MetaAuthor = "Sense Inc.";
    private $MetaCopyright = "2013 Sense Inc.";
    private $MetaApplicationName = "SenseCMS";
    private $MetaMsApplicationTooltip = "SenseCMS";

    /////////////////////////////////////////////////////////////////
    ////////////////////////会员相册相关/////////////////////////////////
    /////////////////////////////////////////////////////////////////   
    private $UserAlbumThumbWidth = 0; //会员相册图片缩略图宽度
    /**
     * 会员相册变成精华相册需要的支持票数
     * @var int 会员相册变成精华相册需要的支持票数
     */
    private $UserAlbumToBestMustVoteCount = 35; //会员相册变成精华相册需要的支持票数
    //
    //论坛相关
    private $OpenHomePage = 0; //开启门户站点首页
    private $ForumIeTitle = ''; //论坛的IE标题
    private $ForumIeKeywords = ''; //论坛的IE keywords
    private $ForumIeDescription = ''; //论坛的IE Description
    private $ForumLogoImage = '';//论坛LOGO图片网址
    private $ForumBackground = ''; //论坛背景图片网址
    private $OpenRegisterWindow = 0; //是否开启注册提示窗口
    private $RegisterWindowContent = ''; //注册提示窗口文字内容
    private $ForumTopInfo = ''; //论坛顶部信息
    private $ForumAdTopIndex = ''; //论坛首页顶部广告
    private $ForumBotInfo = ''; //论坛底部信息
    private $ForumAdBotIndex = ''; //论坛首页底部广告
    
    private $ForumAdTopTopicList = ''; //论坛帖子列表页顶部广告
    private $ForumAdBotTopicList = ''; //论坛帖子列表页底部广告
    private $ForumAdTopTopicContent = ''; //论坛帖子内容页顶部广告
    private $ForumAdBotTopicContent = ''; //论坛帖子内容页底部广告

    private $ForumNewPostCount = 0; //今日发帖数
    private $ForumYesterdayPostCount = 0; //昨日发帖数
    private $ForumTopPostCount = 0; //最高日发帖数
    private $ForumTopicCount = 0; //主题总数
    private $ForumReplyCount = 0; //回复总数
    private $ForumPostCount = 0; //帖子总数
    private $ForumTopicPageSize = 0; //主题列表每页记录数
    private $ForumPostPageSize = 0; //帖子列表每页记录数
    private $UserCount = 0; //会员总数
    private $UserNameLength = 50; //注册会员名的最大长度
    private $UserDefaultState = 0; //注册会员时，State初始状态值
    private $UserRecDefaultState = 0; //推荐会员时，State初始状态值
    private $UserDefaultUserGroupIdForRole = 0; //默认的会员role表中的UserGroupId
    private $UserCommissionOwn = 0; //本人的默认提成比率 
    private $UserCommissionChild = 0; //本人的下一级默认提成比率 
    private $UserCommissionGrandson = 0; //本人的下两级默认提成比率  
 
    private $NewRegisterUserId = 0; //新注册的会员Id
    private $NewRegisterUserName = ''; //新注册的会员名
    private $NewUserMessageVoice = ''; //新短消息提示音文件网址
    private $ForumPicShowMode = 0; //论坛首页版块图标的位置
    private $ForumMoneyName = ''; //论坛中金钱的别名
    private $ForumCharmName = ''; //论坛中魅力的别名
    private $ForumScoreName = ''; //论坛中积分的别名
    private $ForumExpName = '';   //论坛中经验的别名
    private $ForumPointName = ''; //论坛中点券的别名
    private $ForumPostCountName = ''; //论坛中会员发帖数的别名
    private $ForumCssDefault = ''; //论坛默认的样式文件
    private $ForumCssDefaultWidth = ''; //论坛默认的样式宽度文件
    private $ForumCssDefaultFontSize = ''; //论坛默认的样式字体大小文件
    /////////////////////////////////////////////////////////////////
    ////////////////////////支付相关/////////////////////////////////
    /////////////////////////////////////////////////////////////////
    private $PayAlipayPartnerId = ''; //支付宝的合作者身份(Partner ID)
    private $PayAlipayKey = ''; //支付宝的合作者key
    private $PayAlipaySellerEmail = ''; //支付宝的合作者邮箱
    private $PayQuickMerchantAcctId = ''; //快钱人民币网关账户号
    private $PayQuickKey = ''; //快钱人民币网关密钥
    /////////////////////////////////////////////////////////////////
    ////////////////////////教育机构/////////////////////////////////
    /////////////////////////////////////////////////////////////////
    private $SchoolName = ''; //学校名称
    private $SchoolIntro = ''; //学校简介
    private $SchoolAddress = ''; //学校地址
    private $SchoolUrl = ''; //学校网址
    private $SchoolIdeal = ''; //办学理念
    private $SchoolGrade = ''; //办学层次
    private $SchoolFeature = ''; //办学特色
    private $SchoolCapable = ''; //师资力量
    private $SchoolService = ''; //主要课程及收费标准
    private $SchoolQueryPhone = ''; //咨询电话
    private $SchoolLeaderIntro = ''; //校长介绍
    private $SchoolContactPerson = ''; //学校联系人
    private $SchoolTopPic = ''; //学校顶部图片
    private $SchoolQR = ''; //学校二维码图片

    /////////////////////////////////////////////////////////////////
    ////////////////////////第三方短信网关/////////////////////////////////
    /////////////////////////////////////////////////////////////////
    private $SmsThirdType = ""; //第三方短信网关接口商名称
    private $SmsThirdUrl = ""; //第三方短信网关接口网址
    private $SmsThirdUserName = "";//第三方短信网关接口帐号
    private $SmsThirdPassword = "";//第三方短信网关接口密码
    private $UserSmsMessageContent = "";//默认会员短信内容
    
    /**
     * 构造函数
     * @param int $siteId 站点id，每个配置都是站点下的配置
     */

    public function __construct($siteId) {
        $this->SiteId = $siteId;
    }

    /**
     * get 配置值
     * @param string $siteConfigName 配置名称
     * @return type 配置值
     */
    public function __get($siteConfigName) {
        $siteConfigType = 0;
        $defaultValue = '';

        if (in_array($siteConfigName, $this->ArrSiteConfigTypes_1)) {
            $siteConfigType = 1;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_2)) {
            $siteConfigType = 2;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_3)) {
            $siteConfigType = 3;
            $defaultValue = 0;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_4)) {
            $siteConfigType = 4;
            $defaultValue = 0;
        }

        return self::GetValue($this->SiteId, $siteConfigName, $siteConfigType, $defaultValue);
    }

    /**
     * 设置配置值
     * @param string $siteConfigName 配置名称
     * @param string $fieldValue 配置值
     */
    public function __set($siteConfigName, $fieldValue) {
        $siteConfigType = 0;
        if (in_array($siteConfigName, $this->ArrSiteConfigTypes_1)) {
            $siteConfigType = 1;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_2)) {
            $siteConfigType = 2;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_3)) {
            $siteConfigType = 3;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_4)) {
            $siteConfigType = 4;
        }
        self::SetValue($this->SiteId, $siteConfigName, $fieldValue, $siteConfigType);
    }

    /**
     * 设置配置值
     * @param int $siteId 站点id
     * @param string $siteConfigName 配置名称
     * @param string $fieldValue 配置值
     * @param int $siteConfigType 配置类型（0:普通string,1:中长度string,2:text,3:int,4:number）
     */
    public function SetValue($siteId, $siteConfigName, $fieldValue, $siteConfigType = 0) {
        switch ($siteConfigType) {
            case 0:
                $fieldName = "StringNorValue";
                if (empty($fieldValue)) {
                    $fieldValue = "";
                }
                break;
            case 1:
                $fieldName = "StringMidValue";
                if (empty($fieldValue)) {
                    $fieldValue = "";
                }
                break;
            case 2:
                $fieldName = "TextValue";
                if (empty($fieldValue)) {
                    $fieldValue = "";
                }
                break;
            case 3:
                $fieldName = "IntValue";
                if (empty($fieldValue)) {
                    $fieldValue = 0;
                }
                break;
            case 4:
                $fieldName = "NumValue";
                if (empty($fieldValue)) {
                    $fieldValue = 0;
                }
                break;
            default:
                $fieldName = "StringNorValue";
                if (empty($fieldValue)) {
                    $fieldValue = "";
                }
                break;
        }
        $sql = "SELECT count(*) FROM ".self::TableName_SiteConfig." WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $dataProperty->AddField("SiteConfigName", $siteConfigName);
        $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
        if ($hasCount > 0) { //已存在相关配置记录
            $sql = "UPDATE ".self::TableName_SiteConfig." SET " . $fieldName . "=:FieldValue,SiteConfigType=:SiteConfigType WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";
            $dataProperty->AddField("FieldValue", $fieldValue);
            $dataProperty->AddField("SiteConfigType", $siteConfigType);
            $this->dbOperator->Execute($sql, $dataProperty);
        } else {
            $sql = "INSERT INTO ".self::TableName_SiteConfig." (SiteID,SiteConfigName," . $fieldName . ",SiteConfigType) VALUES (:SiteId,:SiteConfigName,:FieldValue,:SiteConfigType);";
            $dataProperty->AddField("FieldValue", $fieldValue);
            $dataProperty->AddField("SiteConfigType", $siteConfigType);
            $this->dbOperator->Execute($sql, $dataProperty);
        }
        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data'.DIRECTORY_SEPARATOR. $siteId;
        $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
        DataCache::Set($cacheDir, $cacheFile, $fieldValue);
    }

    /**
     * 取得配置值（已缓冲）
     * @param int $siteId 站点id
     * @param string $siteConfigName 配置名称
     * @param int $siteConfigType 配置类型（0:普通string,1:中长度string,2:text,3:int,4:number）
     * @param mixed $defaultValue 默认值
     * @return mixed 配置值
     */
    private function GetValue($siteId, $siteConfigName, $siteConfigType = 0, $defaultValue = null) {
        if (intval($siteId) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data'.DIRECTORY_SEPARATOR. $siteId;
            $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
            if (strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0) {
                switch ($siteConfigType) {
                    case 0: //varchar 200
                        $fieldName = "StringNorValue";
                        break;
                    case 1: //varchar 2000
                        $fieldName = "StringMidValue";
                        break;
                    case 2:
                        $fieldName = "TextValue";
                        break;
                    case 3:
                        $fieldName = "IntValue";
                        break;
                    case 4:
                        $fieldName = "NumValue";
                        break;
                    default:
                        $fieldName = "StringNorValue";
                        break;
                }

                $sql = "SELECT " . $fieldName . " FROM ".self::TableName_SiteConfig." WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";

                $dataProperty = new DataProperty();
                $dataProperty->AddField("SiteId", $siteId);
                $dataProperty->AddField("SiteConfigName", $siteConfigName);
                $result = $this->dbOperator->GetString($sql, $dataProperty);
                if ($result == FALSE) {
                    self::SetValue($siteId, $siteConfigName, $defaultValue, $siteConfigType);
                    $result = $defaultValue;
                } else {
                    DataCache::Set($cacheDir, $cacheFile, $result);
                }
            } else {
                $result = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);
            }
            return $result;
        } else {
            return $defaultValue;
        }
    }

    /**
     * 返回某一站点下所有配置项列表
     * @param int $siteId 站点id
     * @return array 配置列表
     */
    public function GetList($siteId) {
        $sql = "SELECT * FROM ".self::TableName_SiteConfig." WHERE SiteId=:SiteId;";
        $dataProperty = new DataProperty();
        $dataProperty->AddField("SiteId", $siteId);
        $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
        return $result;
    }
}

?>
