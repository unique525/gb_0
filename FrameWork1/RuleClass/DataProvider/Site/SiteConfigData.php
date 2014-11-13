<?php

/**
 * 公共 站点配置 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_Site
 * @property int $OpenUrlReWrite
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
 * @property int ForumPicMobileWidth
 * @property int ForumPicPadWidth
 * @property int $UserCount
 * @property int $UserAlbumThumbHeight
 * @property int $UserNameLength
 * @property int $UserDefaultState
 * @property int $UserRecDefaultState
 * @property int $UserDefaultUserGroupIdForRole
 * @property int $UserCommissionOwn
 * @property int $UserCommissionChild
 * @property int $UserCommissionGrandson
 * @property int $UserDefaultMaleAvatar
 * @property int $UserDefaultFemaleAvatar
 *
 *
 * @property int $UserAvatarMaxWidth
 * @property int $UserAvatarMaxHeight
 * @property int $UserAvatarMinWidth
 * @property int $UserAvatarMinHeight
 * @property int $UserAvatarBigWidth
 * @property int $UserAvatarBigHeight
 * @property int $UserAvatarSmallWidth
 * @property int $UserAvatarSmallHeight
 *
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
 * @property int $DocumentNewsTitlePicMobileWidth
 * @property int $DocumentNewsTitlePicPadWidth
 * @property int $ChannelTitlePic1MobileWidth
 * @property int $ChannelTitlePic1PadWidth
 * @property int $ChannelTitlePic2MobileWidth
 * @property int $ChannelTitlePic2PadWidth
 * @property int $ChannelTitlePic3MobileWidth
 * @property int $ChannelTitlePic3PadWidth
 *
 * @property int $ProductTitlePic1MobileWidth
 * @property int $ProductTitlePic1PadWidth
 * @property int $ProductTitlePic1Thumb1Width
 * @property int $ProductTitlePic1Thumb2Width
 * @property int $ProductTitlePic1Thumb3Width
 *
 * @property int $ProductPicMobileWidth
 * @property int $ProductPicPadWidth
 * @property int $ProductPicThumb1Width
 * @property int $ProductPicThumb2Width
 * @property int $ProductPicThumb3Width
 * @property int $ProductPicWatermark1Width
 * @property int $ProductPicWatermark2Width
 * @property int $ProductPicCompress1Width
 * @property int $ProductPicCompress2Width
 *
 *
 * @property int $InformationTitlePic1MobileWidth
 * @property int $InformationTitlePic1PadWidth
 *
 * @property int $ActivityTitlePic1MobileWidth
 * @property int $ActivityTitlePic1PadWidth
 * @property int $ActivityTitlePic2MobileWidth
 * @property int $ActivityTitlePic2PadWidth
 * @property int $ActivityTitlePic3MobileWidth
 * @property int $ActivityTitlePic3PadWidth
 *
 * @author zhangchi
 */
class SiteConfigData extends BaseData {


    /**
     * 短字符串
     */
    const SITE_CONFIG_TYPE_STRING_200 = 0;

    /**
     * 长字符串
     */
    const SITE_CONFIG_TYPE_STRING_2000 = 1;

    /**
     * TEXT
     */
    const SITE_CONFIG_TYPE_TEXT = 2;

    /**
     * INT
     */
    const SITE_CONFIG_TYPE_INT = 3;

    /**
     * NUMBER
     */
    const SITE_CONFIG_TYPE_NUMBER = 4;

    /**
     * UPLOAD FILE ID
     */
    const SITE_CONFIG_TYPE_UPLOAD_FILE_ID = 5;

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
        "OpenUrlReWrite",
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
        "ForumPicMobileWidth",
        "ForumPicPadWidth",
        "OpenFtpLog",
        "UserAlbumThumbWidth",
        "UserAlbumThumbHeight",
        "UserDefaultUserGroupIdForRole",
        "UserAlbumToBestMustVoteCount",
        "UserDefaultState",
        "UserRecDefaultState",

        "UserAvatarMaxWidth",
        "UserAvatarMaxHeight",
        "UserAvatarMinWidth",
        "UserAvatarMinHeight",
        "UserAvatarBigWidth",
        "UserAvatarBigHeight",
        "UserAvatarSmallWidth",
        "UserAvatarSmallHeight",

        "MailSmtpPort",
        "DocumentNewsTitlePicMobileWidth",
        "DocumentNewsTitlePicPadWidth",

        "ChannelTitlePic1MobileWidth",
        "ChannelTitlePic1PadWidth",
        "ChannelTitlePic2MobileWidth",
        "ChannelTitlePic2PadWidth",
        "ChannelTitlePic3MobileWidth",
        "ChannelTitlePic3PadWidth",

        "ProductTitlePic1MobileWidth",
        "ProductTitlePic1PadWidth",

        "ProductPicMobileWidth",
        "ProductPicPadWidth",
        "ProductPicThumb1Width",
        "ProductPicThumb2Width",
        "ProductPicThumb3Width",
        "ProductPicWatermark1Width",
        "ProductPicWatermark2Width",
        "ProductPicCompress1Width",
        "ProductPicCompress2Width",

        "InformationTitlePic1MobileWidth",
        "InformationTitlePic1PadWidth",

        "ActivityTitlePic1MobileWidth",
        "ActivityTitlePic1PadWidth",
        "ActivityTitlePic2MobileWidth",
        "ActivityTitlePic2PadWidth",
        "ActivityTitlePic3MobileWidth",
        "ActivityTitlePic3PadWidth"

);

    /**
     * @var array number
     */
    private $ArrSiteConfigTypes_4 = array(
        "UserCommissionOwn",
        "UserCommissionChild",
        "UserCommissionGrandson"
    );

    /**
     * @var array upload file id
     */
    private $ArrSiteConfigTypes_5 = array(
        "UserDefaultMaleAvatar",
        "UserDefaultFemaleAvatar"
    );

    private $SiteId = 1;

    //公共配置
    private $OpenUrlReWrite = 0; //开启URL重写功能
    private $OpenFtpLog = 0; //开启FTP传输日志记录
    private $MailSmtpHost = ""; // SMTP 服务器
    private $MailSmtpUserName = ""; // SMTP服务器用户名
    private $MailSmtpPassword = ""; // SMTP服务器密码
    private $MailSmtpPort = 465; // SMTP服务器的端口号
    private $MailFrom = ""; //发件人地址
    private $MailReplyTo = ""; //邮件回复地址

    private $MetaGenerator = "SenseCMS";
    private $MetaAuthor = "Sense Inc.";
    private $MetaCopyright = "2013 Sense Inc.";
    private $MetaApplicationName = "SenseCMS";
    private $MetaMsApplicationTooltip = "SenseCMS";

    /////////////////////////////////////////////////////////////////
    ////////////////////////会员相册相关/////////////////////////////////
    /////////////////////////////////////////////////////////////////
    private $UserAlbumThumbWidth = 0; //会员相册图片缩略图宽度
    private $UserAlbumThumbHeight = 0;//会员相册图片缩略图高度
    private $UserAlbumToBestMustVoteCount = 35; //会员相册变成精华相册需要的支持票数
    //
    //论坛相关
    private $OpenHomePage = 0; //开启门户站点首页
    private $ForumIeTitle = ''; //论坛的IE标题
    private $ForumIeKeywords = ''; //论坛的IE keywords
    private $ForumIeDescription = ''; //论坛的IE Description
    private $ForumLogoImage = ''; //论坛LOGO图片网址
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
    private $UserDefaultMaleAvatar = 0;//站点默认男性用户头像 保存UploadFileId
    private $UserDefaultFemaleAvatar = 0; //站点默认女性用户头像 保存UploadFileId

    private $UserAvatarMaxWidth = 0;  //会员头像最大宽度
    private $UserAvatarMaxHeight = 0; //会员头像最大高度
    private $UserAvatarMinWidth = 0;  //会员头像最小宽度
    private $UserAvatarMinHeight = 0; //会员头像最小高度
    private $UserAvatarBigWidth = 0;  //会员大头像宽度
    private $UserAvatarBigHeight = 0;//会员大头像高度
    private $UserAvatarSmallWidth = 0;  //会员小头像宽度
    private $UserAvatarSmallHeight = 0; //会员小头像高度


    private $NewRegisterUserId = 0; //新注册的会员Id
    private $NewRegisterUserName = ''; //新注册的会员名
    private $NewUserMessageVoice = ''; //新短消息提示音文件网址
    private $ForumPicShowMode = 0; //论坛首页版块图标的位置
    private $ForumMoneyName = ''; //论坛中金钱的别名
    private $ForumCharmName = ''; //论坛中魅力的别名
    private $ForumScoreName = ''; //论坛中积分的别名
    private $ForumExpName = ''; //论坛中经验的别名
    private $ForumPointName = ''; //论坛中点券的别名
    private $ForumPostCountName = ''; //论坛中会员发帖数的别名
    private $ForumCssDefault = ''; //论坛默认的样式文件
    private $ForumCssDefaultWidth = ''; //论坛默认的样式宽度文件
    private $ForumCssDefaultFontSize = ''; //论坛默认的样式字体大小文件
    private $ForumPicMobileWidth = 0; //论坛图标 移动客户端用
    private $ForumPicPadWidth = 0; //论坛图标 平板电脑用
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
    private $SmsThirdUserName = ""; //第三方短信网关接口帐号
    private $SmsThirdPassword = ""; //第三方短信网关接口密码
    private $UserSmsMessageContent = ""; //默认会员短信内容

    /////////////////////////////////////////////////////////////////
    ////////////////////////资讯/////////////////////////////////
    /////////////////////////////////////////////////////////////////

    private $DocumentNewsTitlePicMobileWidth = 0; //为适配手机客户端，资讯题图的同比缩小宽度值
    private $DocumentNewsTitlePicPadWidth = 0; //为适配平板客户端，资讯题图的同比缩小宽度值

    private $ChannelTitlePic1MobileWidth = 0;  //为适配手机客户端，频道题图1的同比缩小宽度值
    private $ChannelTitlePic1PadWidth = 0;     //为适配平板客户端，频道题图1的同比缩小宽度值
    private $ChannelTitlePic2MobileWidth = 0;  //为适配手机客户端，频道题图2的同比缩小宽度值
    private $ChannelTitlePic2PadWidth = 0;     //为适配平板客户端，频道题图2的同比缩小宽度值
    private $ChannelTitlePic3MobileWidth = 0;  //为适配手机客户端，频道题图3的同比缩小宽度值
    private $ChannelTitlePic3PadWidth = 0;     //为适配平板客户端，频道题图3的同比缩小宽度值

    private $ProductTitlePic1MobileWidth = 0;  //为适配手机客户端，产品题图1的同比缩小宽度值
    private $ProductTitlePic1PadWidth = 0;  //为适配平板客户端，产品题图1的同比缩小宽度值

    private $ProductPicMobileWidth = 0;  //为适配手机客户端，产品图片的同比缩小宽度值
    private $ProductPicPadWidth = 0;     //为适配平板客户端，产品图片的同比缩小宽度值
    private $ProductPicThumb1Width = 0;  //产品图片的缩略图1宽度值
    private $ProductPicThumb2Width = 0;  //产品图片的缩略图2宽度值
    private $ProductPicThumb3Width = 0;  //产品图片的缩略图3宽度值
    private $ProductPicWatermark1Width = 0;  //产品图片的水印图1宽度值
    private $ProductPicWatermark2Width = 0;  //产品图片的水印图2宽度值
    private $ProductPicCompress1Width = 0;  //产品图片的压缩图1宽度值
    private $ProductPicCompress2Width = 0;  //产品图片的压缩图2宽度值

    private $InformationTitlePic1MobileWidth = 0; //为适配手机客户端，分类信息题图的同比缩小宽度值
    private $InformationTitlePic1PadWidth = 0; //为适配平板客户端，分类信息题图的同比缩小宽度值

    private $ActivityTitlePic1MobileWidth = 0; //为适配手机客户端，活动题图1的同比缩小宽度值
    private $ActivityTitlePic1PadWidth = 0;    //为适配平板客户端，活动题图1的同比缩小宽度值
    private $ActivityTitlePic2MobileWidth = 0; //为适配手机客户端，活动题图2的同比缩小宽度值
    private $ActivityTitlePic2PadWidth = 0;    //为适配平板客户端，活动题图2的同比缩小宽度值
    private $ActivityTitlePic3MobileWidth = 0; //为适配手机客户端，活动题图3的同比缩小宽度值
    private $ActivityTitlePic3PadWidth = 0;

    /**
     * @param mixed $UserAvatarMaxHeight
     */
    public function setUserAvatarMaxHeight($UserAvatarMaxHeight)
    {
        $this->UserAvatarMaxHeight = $UserAvatarMaxHeight;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarMaxHeight()
    {
        return $this->UserAvatarMaxHeight;
    }

    /**
     * @param mixed $UserAvatarMaxWidth
     */
    public function setUserAvatarMaxWidth($UserAvatarMaxWidth)
    {
        $this->UserAvatarMaxWidth = $UserAvatarMaxWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarMaxWidth()
    {
        return $this->UserAvatarMaxWidth;
    }

    /**
     * @param mixed $UserAvatarMinHeight
     */
    public function setUserAvatarMinHeight($UserAvatarMinHeight)
    {
        $this->UserAvatarMinHeight = $UserAvatarMinHeight;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarMinHeight()
    {
        return $this->UserAvatarMinHeight;
    }

    /**
     * @param mixed $UserAvatarMinWidth
     */
    public function setUserAvatarMinWidth($UserAvatarMinWidth)
    {
        $this->UserAvatarMinWidth = $UserAvatarMinWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarMinWidth()
    {
        return $this->UserAvatarMinWidth;
    } //为适配平板客户端，活动题图3的同比缩小宽度值





    /**
     * @param mixed $OpenUrlReWrite
     */
    public function setOpenUrlReWrite($OpenUrlReWrite)
    {
        $this->OpenUrlReWrite = $OpenUrlReWrite;
    }

    /**
     * @return mixed
     */
    public function getOpenUrlReWrite()
    {
        return $this->OpenUrlReWrite;
    }

    /**
     * @param mixed $ProductTitlePic1Thumb1Width
     */
    public function setProductTitlePic1Thumb1Width($ProductTitlePic1Thumb1Width)
    {
        $this->ProductTitlePic1Thumb1Width = $ProductTitlePic1Thumb1Width;
    }

    /**
     * @return mixed
     */
    public function getProductTitlePic1Thumb1Width()
    {
        return $this->ProductTitlePic1Thumb1Width;
    }

    /**
     * @param mixed $ProductTitlePic1Thumb2Width
     */
    public function setProductTitlePic1Thumb2Width($ProductTitlePic1Thumb2Width)
    {
        $this->ProductTitlePic1Thumb2Width = $ProductTitlePic1Thumb2Width;
    }

    /**
     * @return mixed
     */
    public function getProductTitlePic1Thumb2Width()
    {
        return $this->ProductTitlePic1Thumb2Width;
    }

    /**
     * @param mixed $ProductTitlePic1Thumb3Width
     */
    public function setProductTitlePic1Thumb3Width($ProductTitlePic1Thumb3Width)
    {
        $this->ProductTitlePic1Thumb3Width = $ProductTitlePic1Thumb3Width;
    }

    /**
     * @return mixed
     */
    public function getProductTitlePic1Thumb3Width()
    {
        return $this->ProductTitlePic1Thumb3Width;
    }



    /**
     * @param mixed $ProductTitlePic1MobileWidth
     */
    public function setProductTitlePic1MobileWidth($ProductTitlePic1MobileWidth)
    {
        $this->ProductTitlePic1MobileWidth = $ProductTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getProductTitlePic1MobileWidth()
    {
        return $this->ProductTitlePic1MobileWidth;
    }

    /**
     * @param mixed $ProductTitlePic1PadWidth
     */
    public function setProductTitlePic1PadWidth($ProductTitlePic1PadWidth)
    {
        $this->ProductTitlePic1PadWidth = $ProductTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getProductTitlePic1PadWidth()
    {
        return $this->ProductTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePicMobileWidth()
    {
        return $this->DocumentNewsTitlePicMobileWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePicPadWidth()
    {
        return $this->DocumentNewsTitlePicPadWidth;
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
    public function getForumPicMobileWidth()
    {
        return $this->ForumPicMobileWidth;
    }

    /**
     * @return mixed
     */
    public function getForumPicPadWidth()
    {
        return $this->ForumPicPadWidth;
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
     * @param mixed $UserAlbumThumbHeight
     */
    public function setUserAlbumThumbHeight($UserAlbumThumbHeight)
    {
        $this->UserAlbumThumbHeight = $UserAlbumThumbHeight;
    }

    /**
     * @return mixed
     */
    public function getUserAlbumThumbHeight()
    {
        return $this->UserAlbumThumbHeight;
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
     * @param mixed $UserDefaultMaleAvatar
     */
    public function setUserDefaultMaleAvatar($UserDefaultMaleAvatar)
    {
        $this->UserDefaultMaleAvatar = $UserDefaultMaleAvatar;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultMaleAvatar()
    {
        return $this->UserDefaultMaleAvatar;
    }

    /**
     * @param mixed $UserDefaultFemaleAvatar
     */
    public function setUserDefaultFemaleAvatar($UserDefaultFemaleAvatar)
    {
        $this->UserDefaultFemaleAvatar = $UserDefaultFemaleAvatar;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultFemaleAvatar()
    {
        return $this->UserDefaultFemaleAvatar;
    }

    /**
     * @param mixed $UserAvatarBigHeight
     */
    public function setUserAvatarBigHeight($UserAvatarBigHeight)
    {
        $this->UserAvatarBigHeight = $UserAvatarBigHeight;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarBigHeight()
    {
        return $this->UserAvatarBigHeight;
    }

    /**
     * @param mixed $UserAvatarBigWidth
     */
    public function setUserAvatarBigWidth($UserAvatarBigWidth)
    {
        $this->UserAvatarBigWidth = $UserAvatarBigWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarBigWidth()
    {
        return $this->UserAvatarBigWidth;
    }

    /**
     * @param mixed $UserAvatarSmallHeight
     */
    public function setUserAvatarSmallHeight($UserAvatarSmallHeight)
    {
        $this->UserAvatarSmallHeight = $UserAvatarSmallHeight;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarSmallHeight()
    {
        return $this->UserAvatarSmallHeight;
    }

    /**
     * @param mixed $UserAvatarSmallWidth
     */
    public function setUserAvatarSmallWidth($UserAvatarSmallWidth)
    {
        $this->UserAvatarSmallWidth = $UserAvatarSmallWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAvatarSmallWidth()
    {
        return $this->UserAvatarSmallWidth;
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
    }

    /**
     * @param mixed $ProductPicCompress1Width
     */
    public function setProductPicCompress1Width($ProductPicCompress1Width)
    {
        $this->ProductPicCompress1Width = $ProductPicCompress1Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicCompress1Width()
    {
        return $this->ProductPicCompress1Width;
    }

    /**
     * @param mixed $ProductPicCompress2Width
     */
    public function setProductPicCompress2Width($ProductPicCompress2Width)
    {
        $this->ProductPicCompress2Width = $ProductPicCompress2Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicCompress2Width()
    {
        return $this->ProductPicCompress2Width;
    }

    /**
     * @param mixed $ProductPicMobileWidth
     */
    public function setProductPicMobileWidth($ProductPicMobileWidth)
    {
        $this->ProductPicMobileWidth = $ProductPicMobileWidth;
    }

    /**
     * @return mixed
     */
    public function getProductPicMobileWidth()
    {
        return $this->ProductPicMobileWidth;
    }

    /**
     * @param mixed $ProductPicPadWidth
     */
    public function setProductPicPadWidth($ProductPicPadWidth)
    {
        $this->ProductPicPadWidth = $ProductPicPadWidth;
    }

    /**
     * @return mixed
     */
    public function getProductPicPadWidth()
    {
        return $this->ProductPicPadWidth;
    }

    /**
     * @param mixed $ProductPicThumb1Width
     */
    public function setProductPicThumb1Width($ProductPicThumb1Width)
    {
        $this->ProductPicThumb1Width = $ProductPicThumb1Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicThumb1Width()
    {
        return $this->ProductPicThumb1Width;
    }

    /**
     * @param mixed $ProductPicThumb2Width
     */
    public function setProductPicThumb2Width($ProductPicThumb2Width)
    {
        $this->ProductPicThumb2Width = $ProductPicThumb2Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicThumb2Width()
    {
        return $this->ProductPicThumb2Width;
    }

    /**
     * @param mixed $ProductPicThumb3Width
     */
    public function setProductPicThumb3Width($ProductPicThumb3Width)
    {
        $this->ProductPicThumb3Width = $ProductPicThumb3Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicThumb3Width()
    {
        return $this->ProductPicThumb3Width;
    }

    /**
     * @param mixed $ProductPicWatermark1Width
     */
    public function setProductPicWatermark1Width($ProductPicWatermark1Width)
    {
        $this->ProductPicWatermark1Width = $ProductPicWatermark1Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicWatermark1Width()
    {
        return $this->ProductPicWatermark1Width;
    }

    /**
     * @param mixed $ProductPicWatermark2Width
     */
    public function setProductPicWatermark2Width($ProductPicWatermark2Width)
    {
        $this->ProductPicWatermark2Width = $ProductPicWatermark2Width;
    }

    /**
     * @return mixed
     */
    public function getProductPicWatermark2Width()
    {
        return $this->ProductPicWatermark2Width;
    }

    /**
     * @param mixed $ChannelTitlePic1MobileWidth
     */
    public function setChannelTitlePic1MobileWidth($ChannelTitlePic1MobileWidth)
    {
        $this->ChannelTitlePic1MobileWidth = $ChannelTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic1MobileWidth()
    {
        return $this->ChannelTitlePic1MobileWidth;
    }

    /**
     * @param mixed $ChannelTitlePic1PadWidth
     */
    public function setChannelTitlePic1PadWidth($ChannelTitlePic1PadWidth)
    {
        $this->ChannelTitlePic1PadWidth = $ChannelTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic1PadWidth()
    {
        return $this->ChannelTitlePic1PadWidth;
    }

    /**
     * @param mixed $ChannelTitlePic2MobileWidth
     */
    public function setChannelTitlePic2MobileWidth($ChannelTitlePic2MobileWidth)
    {
        $this->ChannelTitlePic2MobileWidth = $ChannelTitlePic2MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic2MobileWidth()
    {
        return $this->ChannelTitlePic2MobileWidth;
    }

    /**
     * @param mixed $ChannelTitlePic2PadWidth
     */
    public function setChannelTitlePic2PadWidth($ChannelTitlePic2PadWidth)
    {
        $this->ChannelTitlePic2PadWidth = $ChannelTitlePic2PadWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic2PadWidth()
    {
        return $this->ChannelTitlePic2PadWidth;
    }

    /**
     * @param mixed $ChannelTitlePic3MobileWidth
     */
    public function setChannelTitlePic3MobileWidth($ChannelTitlePic3MobileWidth)
    {
        $this->ChannelTitlePic3MobileWidth = $ChannelTitlePic3MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic3MobileWidth()
    {
        return $this->ChannelTitlePic3MobileWidth;
    }

    /**
     * @param mixed $ChannelTitlePic3PadWidth
     */
    public function setChannelTitlePic3PadWidth($ChannelTitlePic3PadWidth)
    {
        $this->ChannelTitlePic3PadWidth = $ChannelTitlePic3PadWidth;
    }

    /**
     * @return mixed
     */
    public function getChannelTitlePic3PadWidth()
    {
        return $this->ChannelTitlePic3PadWidth;
    }

    /**
     * @param mixed $InformationTitlePic1MobileWidth
     */
    public function setInformationTitlePic1MobileWidth($InformationTitlePic1MobileWidth)
    {
        $this->$InformationTitlePic1MobileWidth = $InformationTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getInformationTitlePic1MobileWidth()
    {
        return $this->$InformationTitlePic1MobileWidth;
    }

    /**
     * @param mixed $InformationTitlePic1PadWidth
     */
    public function setInformationTitlePic1PadWidth($InformationTitlePic1PadWidth)
    {
        $this->$InformationTitlePic1PadWidth = $InformationTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getInformationTitlePic1PadWidth()
    {
        return $this->$InformationTitlePic1PadWidth;
    }

    /**
     * @param mixed $ActivityTitlePic1MobileWidth
     */
    public function setActivityTitlePic1MobileWidth($ActivityTitlePic1MobileWidth)
    {
        $this->$ActivityTitlePic1MobileWidth = $ActivityTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic1MobileWidth()
    {
        return $this->$ActivityTitlePic1MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic1PadWidth
     */
    public function setActivityTitlePic1PadWidth($ActivityTitlePic1PadWidth)
    {
        $this->$ActivityTitlePic1PadWidth = $ActivityTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic1PadWidth()
    {
        return $this->$ActivityTitlePic1PadWidth;
    }
    /**
     * @param mixed $ActivityTitlePic2MobileWidth
     */
    public function setActivityTitlePic2MobileWidth($ActivityTitlePic2MobileWidth)
    {
        $this->$ActivityTitlePic2MobileWidth = $ActivityTitlePic2MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic2MobileWidth()
    {
        return $this->$ActivityTitlePic2MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic2PadWidth
     */
    public function setActivityTitlePic2PadWidth($ActivityTitlePic2PadWidth)
    {
        $this->$ActivityTitlePic2PadWidth = $ActivityTitlePic2PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic2PadWidth()
    {
        return $this->$ActivityTitlePic2PadWidth;
    }
    /**
     * @param mixed $ActivityTitlePic3MobileWidth
     */
    public function setActivityTitlePic3MobileWidth($ActivityTitlePic3MobileWidth)
    {
        $this->$ActivityTitlePic3MobileWidth = $ActivityTitlePic3MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic3MobileWidth()
    {
        return $this->$ActivityTitlePic3MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic3PadWidth
     */
    public function setActivityTitlePic3PadWidth($ActivityTitlePic3PadWidth)
    {
        $this->$ActivityTitlePic3PadWidth = $ActivityTitlePic3PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic3PadWidth()
    {
        return $this->$ActivityTitlePic3PadWidth;
    }


    protected $dbOperator = null;
    /**
     * 构造函数
     * @param int $siteId 站点id，每个配置都是站点下的配置
     */
    public function __construct($siteId)
    {
        $this->dbOperator = DbOperator::getInstance();
        $this->SiteId = $siteId;
    }

    /**
     * get 配置值
     * @param string $siteConfigName 配置名称
     * @return string 配置值
     */
    public function __get($siteConfigName)
    {
        $siteConfigType = self::SITE_CONFIG_TYPE_STRING_200;
        $defaultValue = '';

        if (in_array($siteConfigName, $this->ArrSiteConfigTypes_1)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_STRING_2000;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_2)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_TEXT;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_3)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_INT;
            $defaultValue = 0;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_4)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_NUMBER;
            $defaultValue = 0;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_5)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_UPLOAD_FILE_ID;
            $defaultValue = 0;
        }

        return self::GetValue($this->SiteId, $siteConfigName, $siteConfigType, $defaultValue);
    }

    /**
     * 设置配置值
     * @param string $siteConfigName 配置名称
     * @param string $fieldValue 配置值
     */
    public function __set($siteConfigName, $fieldValue)
    {
        $siteConfigType = self::SITE_CONFIG_TYPE_STRING_200;
        if (in_array($siteConfigName, $this->ArrSiteConfigTypes_1)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_STRING_2000;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_2)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_TEXT;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_3)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_INT;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_4)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_NUMBER;
        } else if (in_array($siteConfigName, $this->ArrSiteConfigTypes_5)) {
            $siteConfigType = self::SITE_CONFIG_TYPE_UPLOAD_FILE_ID;
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
    public function SetValue($siteId, $siteConfigName, $fieldValue, $siteConfigType = 0)
    {
        if(intval($siteId>0)){
            switch ($siteConfigType) {
                case self::SITE_CONFIG_TYPE_STRING_200:
                    $fieldName = "StringNorValue";
                    if (empty($fieldValue)) {
                        $fieldValue = "";
                    }
                    break;
                case self::SITE_CONFIG_TYPE_STRING_2000:
                    $fieldName = "StringMidValue";
                    if (empty($fieldValue)) {
                        $fieldValue = "";
                    }
                    break;
                case self::SITE_CONFIG_TYPE_TEXT:
                    $fieldName = "TextValue";
                    if (empty($fieldValue)) {
                        $fieldValue = "";
                    }
                    break;
                case self::SITE_CONFIG_TYPE_INT:
                    $fieldName = "IntValue";
                    if (empty($fieldValue)) {
                        $fieldValue = 0;
                    }
                    break;
                case self::SITE_CONFIG_TYPE_NUMBER:
                    $fieldName = "NumValue";
                    if (empty($fieldValue)) {
                        $fieldValue = 0;
                    }
                    break;
                case self::SITE_CONFIG_TYPE_UPLOAD_FILE_ID:
                    $fieldName = "UploadFileId";
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
            $sql = "SELECT count(*) FROM " . self::TableName_SiteConfig . " WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $dataProperty->AddField("SiteConfigName", $siteConfigName);
            $hasCount = $this->dbOperator->GetInt($sql, $dataProperty);
            if ($hasCount > 0) { //已存在相关配置记录
                $sql = "UPDATE " . self::TableName_SiteConfig . " SET " . $fieldName . "=:FieldValue,SiteConfigType=:SiteConfigType WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";
                $dataProperty->AddField("FieldValue", $fieldValue);
                $dataProperty->AddField("SiteConfigType", $siteConfigType);
                $this->dbOperator->Execute($sql, $dataProperty);
            } else {
                $sql = "INSERT INTO " . self::TableName_SiteConfig . " (SiteId,SiteConfigName," . $fieldName . ",SiteConfigType) VALUES (:SiteId,:SiteConfigName,:FieldValue,:SiteConfigType);";
                $dataProperty->AddField("FieldValue", $fieldValue);
                $dataProperty->AddField("SiteConfigType", $siteConfigType);
                $this->dbOperator->Execute($sql, $dataProperty);
            }
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data' . DIRECTORY_SEPARATOR . $siteId;
            $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
            DataCache::Set($cacheDir, $cacheFile, $fieldValue);
        }
    }


    /**
     * 取得配置值（已缓冲）
     * @param int $siteId 站点id
     * @param string $siteConfigName 配置名称
     * @param int $siteConfigType 配置类型（0:普通string,1:中长度string,2:text,3:int,4:number）
     * @param mixed $defaultValue 默认值
     * @return mixed 配置值
     */
    private function GetValue($siteId, $siteConfigName, $siteConfigType = 0, $defaultValue = null)
    {
        if (intval($siteId) > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_data' . DIRECTORY_SEPARATOR . $siteId;
            $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
            if (strlen(DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile)) <= 0) {
                switch ($siteConfigType) {
                    case self::SITE_CONFIG_TYPE_STRING_200: //varchar 200
                        $fieldName = "StringNorValue";
                        break;
                    case self::SITE_CONFIG_TYPE_STRING_2000: //varchar 2000
                        $fieldName = "StringMidValue";
                        break;
                    case self::SITE_CONFIG_TYPE_TEXT:
                        $fieldName = "TextValue";
                        break;
                    case self::SITE_CONFIG_TYPE_INT:
                        $fieldName = "IntValue";
                        break;
                    case self::SITE_CONFIG_TYPE_NUMBER:
                        $fieldName = "NumValue";
                        break;
                    case self::SITE_CONFIG_TYPE_UPLOAD_FILE_ID:
                        $fieldName = "UploadFileId";
                        break;
                    default:
                        $fieldName = "StringNorValue";
                        break;
                }

                $sql = "SELECT " . $fieldName . " FROM " . self::TableName_SiteConfig . " WHERE SiteId=:SiteId AND SiteConfigName=:SiteConfigName;";

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
    public function GetList($siteId)
    {
        if ($siteId > 0) {
            $sql = "SELECT * FROM " . self::TableName_SiteConfig . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            $result = $this->dbOperator->GetArrayList($sql, $dataProperty);
            return $result;
        } else {
            return null;
        }
    }
} 