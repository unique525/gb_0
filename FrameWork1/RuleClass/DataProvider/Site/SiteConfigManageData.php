<?php

/**
 * 后台管理 站点配置 后台数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @property int $OpenFtpLog
 * @property string $MailSmtpHost
 * @property string $MailSmtpUserName
 * @property string $MailSmtpPassword
 * @property int $MailSmtpPort
 * @property string $MailFrom
 * @property string $MailReplyTo
 * @property string $MetaGenerator
 * @property string $MetaAuthor
 * @property string $MetaCopyright
 * @property string $MetaApplicationName
 * @property string $MetaMsApplicationTooltip
 * @property int $UserAlbumThumbWidth
 * @property int $UserAlbumToBestMustVoteCount
 * @property int $OpenHomePage
 * @property string $ForumIeTitle
 * @property string $ForumIeKeywords
 * @property string $ForumIeDescription
 * @property string $ForumLogoImage
 * @property string $ForumBackground
 * @property int $OpenRegisterWindow
 * @property string $RegisterWindowContent
 * @property string $ForumTopInfo
 * @property string $ForumAdTopIndex
 * @property string $ForumBotInfo
 * @property string $ForumAdBotIndex
 * @property string $ForumAdTopTopicList
 * @property string $ForumAdBotTopicList
 * @property string $ForumAdTopTopicContent
 * @property string $ForumAdBotTopicContent
 * @property int $ForumNewPostCount
 * @property int $ForumYesterdayPostCount
 * @property int $ForumTopPostCount
 * @property int $ForumTopicCount
 * @property int $ForumReplyCount
 * @property int $ForumPostCount
 * @property int $ForumTopicPageSize
 * @property int $ForumPostPageSize
 * @property int $UserCount
 * @property int $UserNameLength
 * @property int $UserDefaultState
 * @property int $UserRecDefaultState
 * @property int $UserDefaultUserGroupIdForRole
 * @property int $UserCommissionOwn
 * @property int $UserCommissionChild
 * @property int $UserCommissionGrandson
 * @property int $NewRegisterUserId
 * @property string $NewRegisterUserName
 * @property string $NewUserMessageVoice
 * @property int $ForumPicShowMode
 * @property string $ForumMoneyName
 * @property string $ForumCharmName
 * @property string $ForumScoreName
 * @property string $ForumExpName
 * @property string $ForumPointName
 * @property string $ForumPostCountName
 * @property string $ForumCssDefault
 * @property string $ForumCssDefaultWidth
 * @property string $ForumCssDefaultFontSize
 * @property string $PayAlipayPartnerId
 * @property string $PayAlipayKey
 * @property string $PayAlipaySellerEmail
 * @property string $PayQuickMerchantAcctId
 * @property string $PayQuickKey
 * @property string $SmsThirdType
 * @property string $SmsThirdUrl
 * @property string $SmsThirdUserName
 * @property string $SmsThirdPassword
 * @property string $UserSmsMessageContent
 * @property int $DocumentNewsTitlePic1WidthForMobile
 * @property int $DocumentNewsTitlePic1WidthForPad
 * @property int $DocumentNewsTitlePic2WidthForMobile
 * @property int $DocumentNewsTitlePic2WidthForPad
 * @property int $DocumentNewsTitlePic3WidthForMobile
 * @property int $DocumentNewsTitlePic3WidthForPad
 * @author zhangchi
 */
class SiteConfigManageData extends BaseManageData {


    /**
     * @var array string mid Short Message
     */
    private $ArrSiteConfigTypes_1 = array(
        "UserSmsMessageContent",
        "RegisterWindowContent",
        "SchoolIntro",
        "SchoolFeature",
        "SchoolCapable",
        "SchoolService",
        "SchoolLeaderIntro"
    );
    /**
     * @var array text
     */
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
    /**
     * @var array int
     */
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
        "MailSmtpPort",
        "DocumentNewsTitlePic1WidthForMobile",
        "DocumentNewsTitlePic1WidthForPad",
        "DocumentNewsTitlePic2WidthForMobile",
        "DocumentNewsTitlePic2WidthForPad",
        "DocumentNewsTitlePic3WidthForMobile",
        "DocumentNewsTitlePic3WidthForPad"
    );
    /**
     * @var array number
     */
    private $ArrSiteConfigTypes_4 = array(
        "UserCommissionOwn",
        "UserCommissionChild",
        "UserCommissionGrandson"
    );
    private $SiteId = 1;
    
    //公共配置
    private $OpenFtpLog = 0;  //开启FTP传输日志记录
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
    private $UserAlbumToBestMustVoteCount = 35;

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1WidthForMobile()
    {
        return $this->DocumentNewsTitlePic1WidthForMobile;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1WidthForPad()
    {
        return $this->DocumentNewsTitlePic1WidthForPad;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic2WidthForMobile()
    {
        return $this->DocumentNewsTitlePic2WidthForMobile;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic2WidthForPad()
    {
        return $this->DocumentNewsTitlePic2WidthForPad;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic3WidthForMobile()
    {
        return $this->DocumentNewsTitlePic3WidthForMobile;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic3WidthForPad()
    {
        return $this->DocumentNewsTitlePic3WidthForPad;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotIndex()
    {
        return $this->ForumAdBotIndex;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotTopicContent()
    {
        return $this->ForumAdBotTopicContent;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotTopicList()
    {
        return $this->ForumAdBotTopicList;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopIndex()
    {
        return $this->ForumAdTopIndex;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopTopicContent()
    {
        return $this->ForumAdTopTopicContent;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopTopicList()
    {
        return $this->ForumAdTopTopicList;
    }

    /**
     * @return mixed
     */
    public function getForumBackground()
    {
        return $this->ForumBackground;
    }

    /**
     * @return mixed
     */
    public function getForumBotInfo()
    {
        return $this->ForumBotInfo;
    }

    /**
     * @return mixed
     */
    public function getForumCharmName()
    {
        return $this->ForumCharmName;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefault()
    {
        return $this->ForumCssDefault;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefaultFontSize()
    {
        return $this->ForumCssDefaultFontSize;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefaultWidth()
    {
        return $this->ForumCssDefaultWidth;
    }

    /**
     * @return mixed
     */
    public function getForumExpName()
    {
        return $this->ForumExpName;
    }

    /**
     * @return mixed
     */
    public function getForumIeDescription()
    {
        return $this->ForumIeDescription;
    }

    /**
     * @return mixed
     */
    public function getForumIeKeywords()
    {
        return $this->ForumIeKeywords;
    }

    /**
     * @return mixed
     */
    public function getForumIeTitle()
    {
        return $this->ForumIeTitle;
    }

    /**
     * @return mixed
     */
    public function getForumLogoImage()
    {
        return $this->ForumLogoImage;
    }

    /**
     * @return mixed
     */
    public function getForumMoneyName()
    {
        return $this->ForumMoneyName;
    }

    /**
     * @return mixed
     */
    public function getForumNewPostCount()
    {
        return $this->ForumNewPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumPicShowMode()
    {
        return $this->ForumPicShowMode;
    }

    /**
     * @return mixed
     */
    public function getForumPointName()
    {
        return $this->ForumPointName;
    }

    /**
     * @return mixed
     */
    public function getForumPostCount()
    {
        return $this->ForumPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumPostCountName()
    {
        return $this->ForumPostCountName;
    }

    /**
     * @return mixed
     */
    public function getForumPostPageSize()
    {
        return $this->ForumPostPageSize;
    }

    /**
     * @return mixed
     */
    public function getForumReplyCount()
    {
        return $this->ForumReplyCount;
    }

    /**
     * @return mixed
     */
    public function getForumScoreName()
    {
        return $this->ForumScoreName;
    }

    /**
     * @return mixed
     */
    public function getForumTopInfo()
    {
        return $this->ForumTopInfo;
    }

    /**
     * @return mixed
     */
    public function getForumTopPostCount()
    {
        return $this->ForumTopPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumTopicCount()
    {
        return $this->ForumTopicCount;
    }

    /**
     * @return mixed
     */
    public function getForumTopicPageSize()
    {
        return $this->ForumTopicPageSize;
    }

    /**
     * @return mixed
     */
    public function getForumYesterdayPostCount()
    {
        return $this->ForumYesterdayPostCount;
    }

    /**
     * @return mixed
     */
    public function getMailFrom()
    {
        return $this->MailFrom;
    }

    /**
     * @return mixed
     */
    public function getMailReplyTo()
    {
        return $this->MailReplyTo;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpHost()
    {
        return $this->MailSmtpHost;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpPassword()
    {
        return $this->MailSmtpPassword;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpPort()
    {
        return $this->MailSmtpPort;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpUserName()
    {
        return $this->MailSmtpUserName;
    }

    /**
     * @return mixed
     */
    public function getMetaApplicationName()
    {
        return $this->MetaApplicationName;
    }

    /**
     * @return mixed
     */
    public function getMetaAuthor()
    {
        return $this->MetaAuthor;
    }

    /**
     * @return mixed
     */
    public function getMetaCopyright()
    {
        return $this->MetaCopyright;
    }

    /**
     * @return mixed
     */
    public function getMetaGenerator()
    {
        return $this->MetaGenerator;
    }

    /**
     * @return mixed
     */
    public function getMetaMsApplicationTooltip()
    {
        return $this->MetaMsApplicationTooltip;
    }

    /**
     * @return mixed
     */
    public function getNewRegisterUserId()
    {
        return $this->NewRegisterUserId;
    }

    /**
     * @return mixed
     */
    public function getNewRegisterUserName()
    {
        return $this->NewRegisterUserName;
    }

    /**
     * @return mixed
     */
    public function getNewUserMessageVoice()
    {
        return $this->NewUserMessageVoice;
    }

    /**
     * @return mixed
     */
    public function getOpenFtpLog()
    {
        return $this->OpenFtpLog;
    }

    /**
     * @return mixed
     */
    public function getOpenHomePage()
    {
        return $this->OpenHomePage;
    }

    /**
     * @return mixed
     */
    public function getOpenRegisterWindow()
    {
        return $this->OpenRegisterWindow;
    }

    /**
     * @return mixed
     */
    public function getPayAlipayKey()
    {
        return $this->PayAlipayKey;
    }

    /**
     * @return mixed
     */
    public function getPayAlipayPartnerId()
    {
        return $this->PayAlipayPartnerId;
    }

    /**
     * @return mixed
     */
    public function getPayAlipaySellerEmail()
    {
        return $this->PayAlipaySellerEmail;
    }

    /**
     * @return mixed
     */
    public function getPayQuickKey()
    {
        return $this->PayQuickKey;
    }

    /**
     * @return mixed
     */
    public function getPayQuickMerchantAcctId()
    {
        return $this->PayQuickMerchantAcctId;
    }

    /**
     * @return mixed
     */
    public function getRegisterWindowContent()
    {
        return $this->RegisterWindowContent;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdPassword()
    {
        return $this->SmsThirdPassword;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdType()
    {
        return $this->SmsThirdType;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdUrl()
    {
        return $this->SmsThirdUrl;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdUserName()
    {
        return $this->SmsThirdUserName;
    }

    /**
     * @return mixed
     */
    public function getUserAlbumThumbWidth()
    {
        return $this->UserAlbumThumbWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAlbumToBestMustVoteCount()
    {
        return $this->UserAlbumToBestMustVoteCount;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionChild()
    {
        return $this->UserCommissionChild;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionGrandson()
    {
        return $this->UserCommissionGrandson;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionOwn()
    {
        return $this->UserCommissionOwn;
    }

    /**
     * @return mixed
     */
    public function getUserCount()
    {
        return $this->UserCount;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultState()
    {
        return $this->UserDefaultState;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultUserGroupIdForRole()
    {
        return $this->UserDefaultUserGroupIdForRole;
    }

    /**
     * @return mixed
     */
    public function getUserNameLength()
    {
        return $this->UserNameLength;
    }

    /**
     * @return mixed
     */
    public function getUserRecDefaultState()
    {
        return $this->UserRecDefaultState;
    }

    /**
     * @return mixed
     */
    public function getUserSmsMessageContent()
    {
        return $this->UserSmsMessageContent;
    } //会员相册变成精华相册需要的支持票数
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
    ////////////////////////第三方短信网关/////////////////////////////////
    /////////////////////////////////////////////////////////////////
    private $SmsThirdType = ""; //第三方短信网关接口商名称
    private $SmsThirdUrl = ""; //第三方短信网关接口网址
    private $SmsThirdUserName = "";//第三方短信网关接口帐号
    private $SmsThirdPassword = "";//第三方短信网关接口密码
    private $UserSmsMessageContent = "";//默认会员短信内容

    /////////////////////////////////////////////////////////////////
    ////////////////////////资讯/////////////////////////////////
    /////////////////////////////////////////////////////////////////

    private $DocumentNewsTitlePic1WidthForMobile = 0; //为适配手机客户端，资讯题图1的同比缩小宽度值
    private $DocumentNewsTitlePic1WidthForPad = 0; //为适配平板客户端，资讯题图1的同比缩小宽度值
    private $DocumentNewsTitlePic2WidthForMobile = 0; //为适配手机客户端，资讯题图2的同比缩小宽度值
    private $DocumentNewsTitlePic2WidthForPad = 0; //为适配平板客户端，资讯题图2的同比缩小宽度值
    private $DocumentNewsTitlePic3WidthForMobile = 0; //为适配手机客户端，资讯题图3的同比缩小宽度值
    private $DocumentNewsTitlePic3WidthForPad = 0; //为适配平板客户端，资讯题图3的同比缩小宽度值


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
     * @return string 配置值
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
