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
 * @property string $ForumLogoImageUploadFileId
 * @property string $ForumBackgroundUploadFileId
 * @property string $ForumPostContentWatermarkUploadFileId
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
 *
 * @property int $UserOrderFirstSubPrice
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
 * @property int $ProductSendPriceMode
 * @property float $ProductSendPriceFreeLimit
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
 *
 * @property string $NewspaperArticlePicWatermarkUploadFileId
 *
 * @property int $DocumentNewsTitlePic1MobileWidth
 * @property int $DocumentNewsTitlePic1PadWidth
 * @property int $DocumentNewsTitlePic2MobileWidth
 * @property int $DocumentNewsTitlePic2PadWidth
 * @property int $DocumentNewsTitlePic3MobileWidth
 * @property int $DocumentNewsTitlePic3PadWidth
 *
 * @property string $DocumentNewsContentPicWatermarkUploadFileId
 * @property int $DocumentNewsTitlePic1CompressWidth
 * @property int $DocumentNewsTitlePic1CompressHeight
 * @property int $DocumentNewsTitlePic2CompressWidth
 * @property int $DocumentNewsTitlePic2CompressHeight
 *
 * @property string WeiXinAppId
 * @property string WeiXinAppSecret
 *
 * @property string WeiXinAccessToken
 * @property string WeiXinAccessTokenGetTime
 * @property string WeiXinRefreshToken
 * @property string WeiXinRefreshTokenGetTime
 *
 * @property string WeiXinAccessTokenOauth2
 * @property string WeiXinAccessTokenGetTimeOauth2
 * @property string WeiXinRefreshTokenOauth2
 * @property string WeiXinRefreshTokenGetTimeOauth2
 *
 * @property string WeiXinJsApiTicket
 * @property string WeiXinJsApiTicketGetTime
 *
 * @property string MobSmsCheckUrl
 * @property string MobAppKey
 * @property string MobAppSecret
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
        "SchoolLeaderIntro",
        "WeiXinAccessToken",
        "WeiXinRefreshToken",
        "WeiXinAccessTokenOauth2",
        "WeiXinRefreshTokenOauth2"
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

        "UserOrderFirstSubPrice",

        "MailSmtpPort",

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
        "ProductSendPriceMode",

        "InformationTitlePic1MobileWidth",
        "InformationTitlePic1PadWidth",

        "ActivityTitlePic1MobileWidth",
        "ActivityTitlePic1PadWidth",
        "ActivityTitlePic2MobileWidth",
        "ActivityTitlePic2PadWidth",
        "ActivityTitlePic3MobileWidth",
        "ActivityTitlePic3PadWidth",

        "DocumentNewsTitlePic1MobileWidth",
        "DocumentNewsTitlePic1PadWidth",
        "DocumentNewsTitlePic2MobileWidth",
        "DocumentNewsTitlePic2PadWidth",
        "DocumentNewsTitlePic3MobileWidth",
        "DocumentNewsTitlePic3PadWidth",
        "DocumentNewsTitlePic1CompressWidth",
        "DocumentNewsTitlePic1CompressHeight",
        "DocumentNewsTitlePic2CompressWidth",
        "DocumentNewsTitlePic2CompressHeight"

);

    /**
     * @var array number
     */
    private $ArrSiteConfigTypes_4 = array(
        "UserCommissionOwn",
        "UserCommissionChild",
        "UserCommissionGrandson",
        "ProductSendPriceFreeLimit"
    );

    /**
     * @var array upload file id
     */
    private $ArrSiteConfigTypes_5 = array(
        "UserDefaultMaleAvatar",
        "UserDefaultFemaleAvatar",
        "ForumLogoImageUploadFileId",
        "ForumBackgroundUploadFileId",
        "ForumPostContentWatermarkUploadFileId",
        "NewspaperArticlePicWatermarkUploadFileId",
        "DocumentNewsContentPicWatermarkUploadFileId"
    );

    private $SiteId = 0;

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

    /**
     * 微信APP ID
     * @var string
     */
    private $WeiXinAppId = "";
    /**
     * 微信AppSecret
     * @var string
     */
    private $WeiXinAppSecret = "";

    /**
     * 微信AccessToken
     * @var string
     */
    private $WeiXinAccessToken = "";
    /**
     * 微信AccessToken获取时间
     * @var string
     */
    private $WeiXinAccessTokenGetTime = "";
    /**
     * 微信RefreshToken
     * @var string
     */
    private $WeiXinRefreshToken = "";
    /**
     * 微信RefreshToken获取时间
     * @var string
     */
    private $WeiXinRefreshTokenGetTime = "";


    /**
     * 微信AccessTokenOauth2
     * @var string
     */
    private $WeiXinAccessTokenOauth2 = "";
    /**
     * 微信AccessToken获取时间Oauth2
     * @var string
     */
    private $WeiXinAccessTokenGetTimeOauth2 = "";
    /**
     * 微信RefreshTokenOauth2
     * @var string
     */
    private $WeiXinRefreshTokenOauth2 = "";
    /**
     * 微信RefreshToken获取时间Oauth2
     * @var string
     */
    private $WeiXinRefreshTokenGetTimeOauth2 = "";

    /**
     * 微信WeiXinJsApiTicket
     * @var string
     */
    private $WeiXinJsApiTicket = "";

    /**
     * 微信WeiXinJsApiTicket获取时间
     * @var string
     */
    private $WeiXinJsApiTicketGetTime = "";

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
    private $ForumLogoImageUploadFileId = 0; //论坛LOGO图片id
    private $ForumBackgroundUploadFileId = 0; //论坛背景图片id
    private $ForumPostContentWatermarkUploadFileId = 0; //论坛帖子内容水印图id
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

    private $UserOrderFirstSubPrice = 0; //会员订单第一次下单优惠的金额

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

    private $DocumentNewsTitlePic1MobileWidth = 0; //为适配手机客户端，资讯题图1的同比缩小宽度值
    private $DocumentNewsTitlePic1PadWidth = 0; //为适配平板客户端，资讯题图1的同比缩小宽度值
    private $DocumentNewsTitlePic2MobileWidth = 0; //为适配手机客户端，资讯题图2的同比缩小宽度值
    private $DocumentNewsTitlePic2PadWidth = 0; //为适配平板客户端，资讯题图2的同比缩小宽度值
    private $DocumentNewsTitlePic3MobileWidth = 0; //为适配手机客户端，资讯题图3的同比缩小宽度值
    private $DocumentNewsTitlePic3PadWidth = 0; //为适配平板客户端，资讯题图3的同比缩小宽度值
    private $DocumentNewsContentPicWatermarkUploadFileId = 0;

    private $DocumentNewsTitlePic1CompressWidth = 0; //资讯题图1压缩图宽度值
    private $DocumentNewsTitlePic1CompressHeight = 0; //资讯题图1压缩图高度值

    private $DocumentNewsTitlePic2CompressWidth = 0; //资讯题图2压缩图宽度值
    private $DocumentNewsTitlePic2CompressHeight = 0;//资讯题图2压缩图高度值

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

    /**
     * 发货费用模式
     *   （0）全场免费
     *   （1）达到某金额免费
     *   （2）所有运费累加，并计算续重费，然后客服手动修改运费
     *   （3）取最高的运费，并计算最高项的续重费
     * @var int
     */
    private $ProductSendPriceMode = 0;
    /**
     * 发货费用模式（1）,达到某金额免费
     * @var float
     */
    private $ProductSendPriceFreeLimit = 0;


    private $InformationTitlePic1MobileWidth = 0; //为适配手机客户端，分类信息题图的同比缩小宽度值
    private $InformationTitlePic1PadWidth = 0; //为适配平板客户端，分类信息题图的同比缩小宽度值

    private $ActivityTitlePic1MobileWidth = 0; //为适配手机客户端，活动题图1的同比缩小宽度值
    private $ActivityTitlePic1PadWidth = 0;    //为适配平板客户端，活动题图1的同比缩小宽度值
    private $ActivityTitlePic2MobileWidth = 0; //为适配手机客户端，活动题图2的同比缩小宽度值
    private $ActivityTitlePic2PadWidth = 0;    //为适配平板客户端，活动题图2的同比缩小宽度值
    private $ActivityTitlePic3MobileWidth = 0; //为适配手机客户端，活动题图3的同比缩小宽度值
    private $ActivityTitlePic3PadWidth = 0;    //为适配平板客户端，活动题图3的同比缩小宽度值

    private $NewspaperArticlePicWatermarkUploadFileId = 0;//报纸文章附件上传的图片中的水印图


    /**
     * MOB的短信验证请求网址
     * @var string
     */
    private $MobSmsCheckUrl = "https://webapi.sms.mob.com/sms/verify";
    /**
     * MOB的APP KEY
     * @var string
     */
    private $MobAppKey = "";
    /**
     * MOB的APP SECRET
     * @var string
     */
    private $MobAppSecret = "";

    /**
     * @return string
     */
    public function getMobSmsCheckUrl()
    {
        return $this->MobSmsCheckUrl;
    }

    /**
     * @param string $MobSmsCheckUrl
     */
    public function setMobSmsCheckUrl($MobSmsCheckUrl)
    {
        $this->MobSmsCheckUrl = $MobSmsCheckUrl;
    }

    /**
     * @return string
     */
    public function getMobAppKey()
    {
        return $this->MobAppKey;
    }

    /**
     * @param string $MobAppKey
     */
    public function setMobAppKey($MobAppKey)
    {
        $this->MobAppKey = $MobAppKey;
    }

    /**
     * @return string
     */
    public function getMobAppSecret()
    {
        return $this->MobAppSecret;
    }

    /**
     * @param string $MobAppSecret
     */
    public function setMobAppSecret($MobAppSecret)
    {
        $this->MobAppSecret = $MobAppSecret;
    }




    /**
     * @param mixed $WeiXinAppId
     */
    public function setWeiXinAppId($WeiXinAppId)
    {
        $this->WeiXinAppId = $WeiXinAppId;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAppId()
    {
        return $this->WeiXinAppId;
    }

    /**
     * @param mixed $WeiXinAppSecret
     */
    public function setWeiXinAppSecret($WeiXinAppSecret)
    {
        $this->WeiXinAppSecret = $WeiXinAppSecret;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAppSecret()
    {
        return $this->WeiXinAppSecret;
    }

    /**
     * @param mixed $WeiXinAccessToken
     */
    public function setWeiXinAccessToken($WeiXinAccessToken)
    {
        $this->WeiXinAccessToken = $WeiXinAccessToken;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAccessToken()
    {
        return $this->WeiXinAccessToken;
    }

    /**
     * @param mixed $WeiXinAccessTokenGetTime
     */
    public function setWeiXinAccessTokenGetTime($WeiXinAccessTokenGetTime)
    {
        $this->WeiXinAccessTokenGetTime = $WeiXinAccessTokenGetTime;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAccessTokenGetTime()
    {
        return $this->WeiXinAccessTokenGetTime;
    }

    /**
     * @param mixed $WeiXinRefreshToken
     */
    public function setWeiXinRefreshToken($WeiXinRefreshToken)
    {
        $this->WeiXinRefreshToken = $WeiXinRefreshToken;
    }

    /**
     * @return mixed
     */
    public function getWeiXinRefreshToken()
    {
        return $this->WeiXinRefreshToken;
    }

    /**
     * @param mixed $WeiXinRefreshTokenGetTime
     */
    public function setWeiXinRefreshTokenGetTime($WeiXinRefreshTokenGetTime)
    {
        $this->WeiXinRefreshTokenGetTime = $WeiXinRefreshTokenGetTime;
    }

    /**
     * @return mixed
     */
    public function getWeiXinRefreshTokenGetTime()
    {
        return $this->WeiXinRefreshTokenGetTime;
    }

    /**
     * @param mixed $WeiXinAccessTokenGetTimeOauth2
     */
    public function setWeiXinAccessTokenGetTimeOauth2($WeiXinAccessTokenGetTimeOauth2)
    {
        $this->WeiXinAccessTokenGetTimeOauth2 = $WeiXinAccessTokenGetTimeOauth2;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAccessTokenGetTimeOauth2()
    {
        return $this->WeiXinAccessTokenGetTimeOauth2;
    }

    /**
     * @param mixed $WeiXinAccessTokenOauth2
     */
    public function setWeiXinAccessTokenOauth2($WeiXinAccessTokenOauth2)
    {
        $this->WeiXinAccessTokenOauth2 = $WeiXinAccessTokenOauth2;
    }

    /**
     * @return mixed
     */
    public function getWeiXinAccessTokenOauth2()
    {
        return $this->WeiXinAccessTokenOauth2;
    }

    /**
     * @param mixed $WeiXinRefreshTokenGetTimeOauth2
     */
    public function setWeiXinRefreshTokenGetTimeOauth2($WeiXinRefreshTokenGetTimeOauth2)
    {
        $this->WeiXinRefreshTokenGetTimeOauth2 = $WeiXinRefreshTokenGetTimeOauth2;
    }

    /**
     * @return mixed
     */
    public function getWeiXinRefreshTokenGetTimeOauth2()
    {
        return $this->WeiXinRefreshTokenGetTimeOauth2;
    }

    /**
     * @param mixed $WeiXinRefreshTokenOauth2
     */
    public function setWeiXinRefreshTokenOauth2($WeiXinRefreshTokenOauth2)
    {
        $this->WeiXinRefreshTokenOauth2 = $WeiXinRefreshTokenOauth2;
    }

    /**
     * @return mixed
     */
    public function getWeiXinRefreshTokenOauth2()
    {
        return $this->WeiXinRefreshTokenOauth2;
    }

    /**
     * @param mixed $WeiXinJsApiTicket
     */
    public function setWeiXinJsApiTicket($WeiXinJsApiTicket)
    {
        $this->WeiXinJsApiTicket = $WeiXinJsApiTicket;
    }

    /**
     * @return mixed
     */
    public function getWeiXinJsApiTicket()
    {
        return $this->WeiXinJsApiTicket;
    }

    /**
     * @param mixed $WeiXinJsApiTicketGetTime
     */
    public function setWeiXinJsApiTicketGetTime($WeiXinJsApiTicketGetTime)
    {
        $this->WeiXinJsApiTicketGetTime = $WeiXinJsApiTicketGetTime;
    }

    /**
     * @return mixed
     */
    public function getWeiXinJsApiTicketGetTime()
    {
        return $this->WeiXinJsApiTicketGetTime;
    }

    /**
     * @return int
     */
    public function getDocumentNewsTitlePic2MobileWidth()
    {
        return $this->DocumentNewsTitlePic2MobileWidth;
    }

    /**
     * @param int $DocumentNewsTitlePic2MobileWidth
     */
    public function setDocumentNewsTitlePic2MobileWidth($DocumentNewsTitlePic2MobileWidth)
    {
        $this->DocumentNewsTitlePic2MobileWidth = $DocumentNewsTitlePic2MobileWidth;
    }

    /**
     * @return int
     */
    public function getDocumentNewsTitlePic2PadWidth()
    {
        return $this->DocumentNewsTitlePic2PadWidth;
    }

    /**
     * @param int $DocumentNewsTitlePic2PadWidth
     */
    public function setDocumentNewsTitlePic2PadWidth($DocumentNewsTitlePic2PadWidth)
    {
        $this->DocumentNewsTitlePic2PadWidth = $DocumentNewsTitlePic2PadWidth;
    }

    /**
     * @return int
     */
    public function getDocumentNewsTitlePic3MobileWidth()
    {
        return $this->DocumentNewsTitlePic3MobileWidth;
    }

    /**
     * @param int $DocumentNewsTitlePic3MobileWidth
     */
    public function setDocumentNewsTitlePic3MobileWidth($DocumentNewsTitlePic3MobileWidth)
    {
        $this->DocumentNewsTitlePic3MobileWidth = $DocumentNewsTitlePic3MobileWidth;
    }

    /**
     * @return int
     */
    public function getDocumentNewsTitlePic3PadWidth()
    {
        return $this->DocumentNewsTitlePic3PadWidth;
    }

    /**
     * @param int $DocumentNewsTitlePic3PadWidth
     */
    public function setDocumentNewsTitlePic3PadWidth($DocumentNewsTitlePic3PadWidth)
    {
        $this->DocumentNewsTitlePic3PadWidth = $DocumentNewsTitlePic3PadWidth;
    }






    /**
     * @param mixed $DocumentNewsTitlePic1CompressHeight
     */
    public function setDocumentNewsTitlePic1CompressHeight($DocumentNewsTitlePic1CompressHeight)
    {
        $this->DocumentNewsTitlePic1CompressHeight = $DocumentNewsTitlePic1CompressHeight;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1CompressHeight()
    {
        return $this->DocumentNewsTitlePic1CompressHeight;
    }

    /**
     * @param mixed $DocumentNewsTitlePic1CompressWidth
     */
    public function setDocumentNewsTitlePic1CompressWidth($DocumentNewsTitlePic1CompressWidth)
    {
        $this->DocumentNewsTitlePic1CompressWidth = $DocumentNewsTitlePic1CompressWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1CompressWidth()
    {
        return $this->DocumentNewsTitlePic1CompressWidth;
    }


    /**
     * @param mixed $DocumentNewsTitlePic2CompressHeight
     */
    public function setDocumentNewsTitlePic2CompressHeight($DocumentNewsTitlePic2CompressHeight)
    {
        $this->DocumentNewsTitlePic2CompressHeight = $DocumentNewsTitlePic2CompressHeight;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic2CompressHeight()
    {
        return $this->DocumentNewsTitlePic2CompressHeight;
    }

    /**
     * @param mixed $DocumentNewsTitlePic2CompressWidth
     */
    public function setDocumentNewsTitlePic2CompressWidth($DocumentNewsTitlePic2CompressWidth)
    {
        $this->DocumentNewsTitlePic2CompressWidth = $DocumentNewsTitlePic2CompressWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic2CompressWidth()
    {
        return $this->DocumentNewsTitlePic2CompressWidth;
    }



    /**
     * @param mixed $ActivityTitlePic1MobileWidth
     */
    public function setActivityTitlePic1MobileWidth($ActivityTitlePic1MobileWidth)
    {
        $this->ActivityTitlePic1MobileWidth = $ActivityTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic1MobileWidth()
    {
        return $this->ActivityTitlePic1MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic1PadWidth
     */
    public function setActivityTitlePic1PadWidth($ActivityTitlePic1PadWidth)
    {
        $this->ActivityTitlePic1PadWidth = $ActivityTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic1PadWidth()
    {
        return $this->ActivityTitlePic1PadWidth;
    }

    /**
     * @param mixed $ActivityTitlePic2MobileWidth
     */
    public function setActivityTitlePic2MobileWidth($ActivityTitlePic2MobileWidth)
    {
        $this->ActivityTitlePic2MobileWidth = $ActivityTitlePic2MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic2MobileWidth()
    {
        return $this->ActivityTitlePic2MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic2PadWidth
     */
    public function setActivityTitlePic2PadWidth($ActivityTitlePic2PadWidth)
    {
        $this->ActivityTitlePic2PadWidth = $ActivityTitlePic2PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic2PadWidth()
    {
        return $this->ActivityTitlePic2PadWidth;
    }

    /**
     * @param mixed $ActivityTitlePic3MobileWidth
     */
    public function setActivityTitlePic3MobileWidth($ActivityTitlePic3MobileWidth)
    {
        $this->ActivityTitlePic3MobileWidth = $ActivityTitlePic3MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic3MobileWidth()
    {
        return $this->ActivityTitlePic3MobileWidth;
    }

    /**
     * @param mixed $ActivityTitlePic3PadWidth
     */
    public function setActivityTitlePic3PadWidth($ActivityTitlePic3PadWidth)
    {
        $this->ActivityTitlePic3PadWidth = $ActivityTitlePic3PadWidth;
    }

    /**
     * @return mixed
     */
    public function getActivityTitlePic3PadWidth()
    {
        return $this->ActivityTitlePic3PadWidth;
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
     * @param mixed $DocumentNewsContentPicWatermarkUploadFileId
     */
    public function setDocumentNewsContentPicWatermarkUploadFileId($DocumentNewsContentPicWatermarkUploadFileId)
    {
        $this->DocumentNewsContentPicWatermarkUploadFileId = $DocumentNewsContentPicWatermarkUploadFileId;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsContentPicWatermarkUploadFileId()
    {
        return $this->DocumentNewsContentPicWatermarkUploadFileId;
    }

    /**
     * @param mixed $DocumentNewsTitlePic1MobileWidth
     */
    public function setDocumentNewsTitlePic1MobileWidth($DocumentNewsTitlePic1MobileWidth)
    {
        $this->DocumentNewsTitlePic1MobileWidth = $DocumentNewsTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1MobileWidth()
    {
        return $this->DocumentNewsTitlePic1MobileWidth;
    }

    /**
     * @param mixed $DocumentNewsTitlePic1PadWidth
     */
    public function setDocumentNewsTitlePic1PadWidth($DocumentNewsTitlePic1PadWidth)
    {
        $this->DocumentNewsTitlePic1PadWidth = $DocumentNewsTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getDocumentNewsTitlePic1PadWidth()
    {
        return $this->DocumentNewsTitlePic1PadWidth;
    }

    /**
     * @param mixed $ForumAdBotIndex
     */
    public function setForumAdBotIndex($ForumAdBotIndex)
    {
        $this->ForumAdBotIndex = $ForumAdBotIndex;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotIndex()
    {
        return $this->ForumAdBotIndex;
    }

    /**
     * @param mixed $ForumAdBotTopicList
     */
    public function setForumAdBotTopicList($ForumAdBotTopicList)
    {
        $this->ForumAdBotTopicList = $ForumAdBotTopicList;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotTopicList()
    {
        return $this->ForumAdBotTopicList;
    }

    /**
     * @param mixed $ForumAdTopIndex
     */
    public function setForumAdTopIndex($ForumAdTopIndex)
    {
        $this->ForumAdTopIndex = $ForumAdTopIndex;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopIndex()
    {
        return $this->ForumAdTopIndex;
    }

    /**
     * @param mixed $ForumAdTopTopicContent
     */
    public function setForumAdTopTopicContent($ForumAdTopTopicContent)
    {
        $this->ForumAdTopTopicContent = $ForumAdTopTopicContent;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopTopicContent()
    {
        return $this->ForumAdTopTopicContent;
    }

    /**
     * @param mixed $ForumAdBotTopicContent
     */
    public function setForumAdBotTopicContent($ForumAdBotTopicContent)
    {
        $this->ForumAdBotTopicContent = $ForumAdBotTopicContent;
    }

    /**
     * @return mixed
     */
    public function getForumAdBotTopicContent()
    {
        return $this->ForumAdBotTopicContent;
    }

    /**
     * @param mixed $ForumAdTopTopicList
     */
    public function setForumAdTopTopicList($ForumAdTopTopicList)
    {
        $this->ForumAdTopTopicList = $ForumAdTopTopicList;
    }

    /**
     * @return mixed
     */
    public function getForumAdTopTopicList()
    {
        return $this->ForumAdTopTopicList;
    }

    /**
     * @param mixed $ForumBackgroundUploadFileId
     */
    public function setForumBackgroundUploadFileId($ForumBackgroundUploadFileId)
    {
        $this->ForumBackgroundUploadFileId = $ForumBackgroundUploadFileId;
    }

    /**
     * @return mixed
     */
    public function getForumBackgroundUploadFileId()
    {
        return $this->ForumBackgroundUploadFileId;
    }

    /**
     * @param mixed $ForumPostContentWatermarkUploadFileId
     */
    public function setForumPostContentWatermarkUploadFileId($ForumPostContentWatermarkUploadFileId)
    {
        $this->ForumPostContentWatermarkUploadFileId = $ForumPostContentWatermarkUploadFileId;
    }

    /**
     * @return mixed
     */
    public function getForumPostContentWatermarkUploadFileId()
    {
        return $this->ForumPostContentWatermarkUploadFileId;
    }



    /**
     * @param mixed $ForumBotInfo
     */
    public function setForumBotInfo($ForumBotInfo)
    {
        $this->ForumBotInfo = $ForumBotInfo;
    }

    /**
     * @return mixed
     */
    public function getForumBotInfo()
    {
        return $this->ForumBotInfo;
    }

    /**
     * @param mixed $ForumCharmName
     */
    public function setForumCharmName($ForumCharmName)
    {
        $this->ForumCharmName = $ForumCharmName;
    }

    /**
     * @return mixed
     */
    public function getForumCharmName()
    {
        return $this->ForumCharmName;
    }

    /**
     * @param mixed $ForumCssDefault
     */
    public function setForumCssDefault($ForumCssDefault)
    {
        $this->ForumCssDefault = $ForumCssDefault;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefault()
    {
        return $this->ForumCssDefault;
    }

    /**
     * @param mixed $ForumCssDefaultFontSize
     */
    public function setForumCssDefaultFontSize($ForumCssDefaultFontSize)
    {
        $this->ForumCssDefaultFontSize = $ForumCssDefaultFontSize;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefaultFontSize()
    {
        return $this->ForumCssDefaultFontSize;
    }

    /**
     * @param mixed $ForumCssDefaultWidth
     */
    public function setForumCssDefaultWidth($ForumCssDefaultWidth)
    {
        $this->ForumCssDefaultWidth = $ForumCssDefaultWidth;
    }

    /**
     * @return mixed
     */
    public function getForumCssDefaultWidth()
    {
        return $this->ForumCssDefaultWidth;
    }

    /**
     * @param mixed $ForumExpName
     */
    public function setForumExpName($ForumExpName)
    {
        $this->ForumExpName = $ForumExpName;
    }

    /**
     * @return mixed
     */
    public function getForumExpName()
    {
        return $this->ForumExpName;
    }

    /**
     * @param mixed $ForumIeDescription
     */
    public function setForumIeDescription($ForumIeDescription)
    {
        $this->ForumIeDescription = $ForumIeDescription;
    }

    /**
     * @return mixed
     */
    public function getForumIeDescription()
    {
        return $this->ForumIeDescription;
    }

    /**
     * @param mixed $ForumIeKeywords
     */
    public function setForumIeKeywords($ForumIeKeywords)
    {
        $this->ForumIeKeywords = $ForumIeKeywords;
    }

    /**
     * @return mixed
     */
    public function getForumIeKeywords()
    {
        return $this->ForumIeKeywords;
    }

    /**
     * @param mixed $ForumIeTitle
     */
    public function setForumIeTitle($ForumIeTitle)
    {
        $this->ForumIeTitle = $ForumIeTitle;
    }

    /**
     * @return mixed
     */
    public function getForumIeTitle()
    {
        return $this->ForumIeTitle;
    }

    /**
     * @param mixed $ForumLogoImageUploadFileId
     */
    public function setForumLogoImageUploadFileId($ForumLogoImageUploadFileId)
    {
        $this->ForumLogoImageUploadFileId = $ForumLogoImageUploadFileId;
    }

    /**
     * @return mixed
     */
    public function getForumLogoImageUploadFileId()
    {
        return $this->ForumLogoImageUploadFileId;
    }

    /**
     * @param mixed $ForumMoneyName
     */
    public function setForumMoneyName($ForumMoneyName)
    {
        $this->ForumMoneyName = $ForumMoneyName;
    }

    /**
     * @return mixed
     */
    public function getForumMoneyName()
    {
        return $this->ForumMoneyName;
    }

    /**
     * @param mixed $ForumNewPostCount
     */
    public function setForumNewPostCount($ForumNewPostCount)
    {
        $this->ForumNewPostCount = $ForumNewPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumNewPostCount()
    {
        return $this->ForumNewPostCount;
    }

    /**
     * @param mixed $ForumPicMobileWidth
     */
    public function setForumPicMobileWidth($ForumPicMobileWidth)
    {
        $this->ForumPicMobileWidth = $ForumPicMobileWidth;
    }

    /**
     * @return mixed
     */
    public function getForumPicMobileWidth()
    {
        return $this->ForumPicMobileWidth;
    }

    /**
     * @param mixed $ForumPicPadWidth
     */
    public function setForumPicPadWidth($ForumPicPadWidth)
    {
        $this->ForumPicPadWidth = $ForumPicPadWidth;
    }

    /**
     * @return mixed
     */
    public function getForumPicPadWidth()
    {
        return $this->ForumPicPadWidth;
    }

    /**
     * @param mixed $ForumPicShowMode
     */
    public function setForumPicShowMode($ForumPicShowMode)
    {
        $this->ForumPicShowMode = $ForumPicShowMode;
    }

    /**
     * @return mixed
     */
    public function getForumPicShowMode()
    {
        return $this->ForumPicShowMode;
    }

    /**
     * @param mixed $ForumPointName
     */
    public function setForumPointName($ForumPointName)
    {
        $this->ForumPointName = $ForumPointName;
    }

    /**
     * @return mixed
     */
    public function getForumPointName()
    {
        return $this->ForumPointName;
    }

    /**
     * @param mixed $ForumPostCount
     */
    public function setForumPostCount($ForumPostCount)
    {
        $this->ForumPostCount = $ForumPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumPostCount()
    {
        return $this->ForumPostCount;
    }

    /**
     * @param mixed $ForumPostCountName
     */
    public function setForumPostCountName($ForumPostCountName)
    {
        $this->ForumPostCountName = $ForumPostCountName;
    }

    /**
     * @return mixed
     */
    public function getForumPostCountName()
    {
        return $this->ForumPostCountName;
    }

    /**
     * @param mixed $ForumPostPageSize
     */
    public function setForumPostPageSize($ForumPostPageSize)
    {
        $this->ForumPostPageSize = $ForumPostPageSize;
    }

    /**
     * @return mixed
     */
    public function getForumPostPageSize()
    {
        return $this->ForumPostPageSize;
    }

    /**
     * @param mixed $ForumReplyCount
     */
    public function setForumReplyCount($ForumReplyCount)
    {
        $this->ForumReplyCount = $ForumReplyCount;
    }

    /**
     * @return mixed
     */
    public function getForumReplyCount()
    {
        return $this->ForumReplyCount;
    }

    /**
     * @param mixed $ForumScoreName
     */
    public function setForumScoreName($ForumScoreName)
    {
        $this->ForumScoreName = $ForumScoreName;
    }

    /**
     * @return mixed
     */
    public function getForumScoreName()
    {
        return $this->ForumScoreName;
    }

    /**
     * @param mixed $ForumTopInfo
     */
    public function setForumTopInfo($ForumTopInfo)
    {
        $this->ForumTopInfo = $ForumTopInfo;
    }

    /**
     * @return mixed
     */
    public function getForumTopInfo()
    {
        return $this->ForumTopInfo;
    }

    /**
     * @param mixed $ForumTopPostCount
     */
    public function setForumTopPostCount($ForumTopPostCount)
    {
        $this->ForumTopPostCount = $ForumTopPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumTopPostCount()
    {
        return $this->ForumTopPostCount;
    }

    /**
     * @param mixed $ForumTopicCount
     */
    public function setForumTopicCount($ForumTopicCount)
    {
        $this->ForumTopicCount = $ForumTopicCount;
    }

    /**
     * @return mixed
     */
    public function getForumTopicCount()
    {
        return $this->ForumTopicCount;
    }

    /**
     * @param mixed $ForumTopicPageSize
     */
    public function setForumTopicPageSize($ForumTopicPageSize)
    {
        $this->ForumTopicPageSize = $ForumTopicPageSize;
    }

    /**
     * @return mixed
     */
    public function getForumTopicPageSize()
    {
        return $this->ForumTopicPageSize;
    }

    /**
     * @param mixed $ForumYesterdayPostCount
     */
    public function setForumYesterdayPostCount($ForumYesterdayPostCount)
    {
        $this->ForumYesterdayPostCount = $ForumYesterdayPostCount;
    }

    /**
     * @return mixed
     */
    public function getForumYesterdayPostCount()
    {
        return $this->ForumYesterdayPostCount;
    }

    /**
     * @param mixed $InformationTitlePic1MobileWidth
     */
    public function setInformationTitlePic1MobileWidth($InformationTitlePic1MobileWidth)
    {
        $this->InformationTitlePic1MobileWidth = $InformationTitlePic1MobileWidth;
    }

    /**
     * @return mixed
     */
    public function getInformationTitlePic1MobileWidth()
    {
        return $this->InformationTitlePic1MobileWidth;
    }

    /**
     * @param mixed $InformationTitlePic1PadWidth
     */
    public function setInformationTitlePic1PadWidth($InformationTitlePic1PadWidth)
    {
        $this->InformationTitlePic1PadWidth = $InformationTitlePic1PadWidth;
    }

    /**
     * @return mixed
     */
    public function getInformationTitlePic1PadWidth()
    {
        return $this->InformationTitlePic1PadWidth;
    }

    /**
     * @param mixed $MailFrom
     */
    public function setMailFrom($MailFrom)
    {
        $this->MailFrom = $MailFrom;
    }

    /**
     * @return mixed
     */
    public function getMailFrom()
    {
        return $this->MailFrom;
    }

    /**
     * @param mixed $MailReplyTo
     */
    public function setMailReplyTo($MailReplyTo)
    {
        $this->MailReplyTo = $MailReplyTo;
    }

    /**
     * @return mixed
     */
    public function getMailReplyTo()
    {
        return $this->MailReplyTo;
    }

    /**
     * @param mixed $MailSmtpHost
     */
    public function setMailSmtpHost($MailSmtpHost)
    {
        $this->MailSmtpHost = $MailSmtpHost;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpHost()
    {
        return $this->MailSmtpHost;
    }

    /**
     * @param mixed $MailSmtpPassword
     */
    public function setMailSmtpPassword($MailSmtpPassword)
    {
        $this->MailSmtpPassword = $MailSmtpPassword;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpPassword()
    {
        return $this->MailSmtpPassword;
    }

    /**
     * @param mixed $MailSmtpPort
     */
    public function setMailSmtpPort($MailSmtpPort)
    {
        $this->MailSmtpPort = $MailSmtpPort;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpPort()
    {
        return $this->MailSmtpPort;
    }

    /**
     * @param mixed $MailSmtpUserName
     */
    public function setMailSmtpUserName($MailSmtpUserName)
    {
        $this->MailSmtpUserName = $MailSmtpUserName;
    }

    /**
     * @return mixed
     */
    public function getMailSmtpUserName()
    {
        return $this->MailSmtpUserName;
    }

    /**
     * @param mixed $MetaApplicationName
     */
    public function setMetaApplicationName($MetaApplicationName)
    {
        $this->MetaApplicationName = $MetaApplicationName;
    }

    /**
     * @return mixed
     */
    public function getMetaApplicationName()
    {
        return $this->MetaApplicationName;
    }

    /**
     * @param mixed $MetaAuthor
     */
    public function setMetaAuthor($MetaAuthor)
    {
        $this->MetaAuthor = $MetaAuthor;
    }

    /**
     * @return mixed
     */
    public function getMetaAuthor()
    {
        return $this->MetaAuthor;
    }

    /**
     * @param mixed $MetaCopyright
     */
    public function setMetaCopyright($MetaCopyright)
    {
        $this->MetaCopyright = $MetaCopyright;
    }

    /**
     * @return mixed
     */
    public function getMetaCopyright()
    {
        return $this->MetaCopyright;
    }

    /**
     * @param mixed $MetaGenerator
     */
    public function setMetaGenerator($MetaGenerator)
    {
        $this->MetaGenerator = $MetaGenerator;
    }

    /**
     * @return mixed
     */
    public function getMetaGenerator()
    {
        return $this->MetaGenerator;
    }

    /**
     * @param mixed $MetaMsApplicationTooltip
     */
    public function setMetaMsApplicationTooltip($MetaMsApplicationTooltip)
    {
        $this->MetaMsApplicationTooltip = $MetaMsApplicationTooltip;
    }

    /**
     * @return mixed
     */
    public function getMetaMsApplicationTooltip()
    {
        return $this->MetaMsApplicationTooltip;
    }

    /**
     * @param mixed $NewRegisterUserId
     */
    public function setNewRegisterUserId($NewRegisterUserId)
    {
        $this->NewRegisterUserId = $NewRegisterUserId;
    }

    /**
     * @return mixed
     */
    public function getNewRegisterUserId()
    {
        return $this->NewRegisterUserId;
    }

    /**
     * @param mixed $NewRegisterUserName
     */
    public function setNewRegisterUserName($NewRegisterUserName)
    {
        $this->NewRegisterUserName = $NewRegisterUserName;
    }

    /**
     * @return mixed
     */
    public function getNewRegisterUserName()
    {
        return $this->NewRegisterUserName;
    }

    /**
     * @param mixed $NewUserMessageVoice
     */
    public function setNewUserMessageVoice($NewUserMessageVoice)
    {
        $this->NewUserMessageVoice = $NewUserMessageVoice;
    }

    /**
     * @return mixed
     */
    public function getNewUserMessageVoice()
    {
        return $this->NewUserMessageVoice;
    }

    /**
     * @param mixed $NewspaperArticlePicWatermarkUploadFileId
     */
    public function setNewspaperArticlePicWatermarkUploadFileId($NewspaperArticlePicWatermarkUploadFileId)
    {
        $this->NewspaperArticlePicWatermarkUploadFileId = $NewspaperArticlePicWatermarkUploadFileId;
    }

    /**
     * @return mixed
     */
    public function getNewspaperArticlePicWatermarkUploadFileId()
    {
        return $this->NewspaperArticlePicWatermarkUploadFileId;
    }

    /**
     * @param mixed $OpenFtpLog
     */
    public function setOpenFtpLog($OpenFtpLog)
    {
        $this->OpenFtpLog = $OpenFtpLog;
    }

    /**
     * @return mixed
     */
    public function getOpenFtpLog()
    {
        return $this->OpenFtpLog;
    }

    /**
     * @param mixed $OpenHomePage
     */
    public function setOpenHomePage($OpenHomePage)
    {
        $this->OpenHomePage = $OpenHomePage;
    }

    /**
     * @return mixed
     */
    public function getOpenHomePage()
    {
        return $this->OpenHomePage;
    }

    /**
     * @param mixed $OpenRegisterWindow
     */
    public function setOpenRegisterWindow($OpenRegisterWindow)
    {
        $this->OpenRegisterWindow = $OpenRegisterWindow;
    }

    /**
     * @return mixed
     */
    public function getOpenRegisterWindow()
    {
        return $this->OpenRegisterWindow;
    }

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
     * @param mixed $PayAlipayKey
     */
    public function setPayAlipayKey($PayAlipayKey)
    {
        $this->PayAlipayKey = $PayAlipayKey;
    }

    /**
     * @return mixed
     */
    public function getPayAlipayKey()
    {
        return $this->PayAlipayKey;
    }

    /**
     * @param mixed $PayAlipayPartnerId
     */
    public function setPayAlipayPartnerId($PayAlipayPartnerId)
    {
        $this->PayAlipayPartnerId = $PayAlipayPartnerId;
    }

    /**
     * @return mixed
     */
    public function getPayAlipayPartnerId()
    {
        return $this->PayAlipayPartnerId;
    }

    /**
     * @param mixed $PayAlipaySellerEmail
     */
    public function setPayAlipaySellerEmail($PayAlipaySellerEmail)
    {
        $this->PayAlipaySellerEmail = $PayAlipaySellerEmail;
    }

    /**
     * @return mixed
     */
    public function getPayAlipaySellerEmail()
    {
        return $this->PayAlipaySellerEmail;
    }

    /**
     * @param mixed $PayQuickKey
     */
    public function setPayQuickKey($PayQuickKey)
    {
        $this->PayQuickKey = $PayQuickKey;
    }

    /**
     * @return mixed
     */
    public function getPayQuickKey()
    {
        return $this->PayQuickKey;
    }

    /**
     * @param mixed $PayQuickMerchantAcctId
     */
    public function setPayQuickMerchantAcctId($PayQuickMerchantAcctId)
    {
        $this->PayQuickMerchantAcctId = $PayQuickMerchantAcctId;
    }

    /**
     * @return mixed
     */
    public function getPayQuickMerchantAcctId()
    {
        return $this->PayQuickMerchantAcctId;
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
     * @param mixed $ProductSendPriceFreeLimit
     */
    public function setProductSendPriceFreeLimit($ProductSendPriceFreeLimit)
    {
        $this->ProductSendPriceFreeLimit = $ProductSendPriceFreeLimit;
    }

    /**
     * @return mixed
     */
    public function getProductSendPriceFreeLimit()
    {
        return $this->ProductSendPriceFreeLimit;
    }

    /**
     * @param mixed $ProductSendPriceMode
     */
    public function setProductSendPriceMode($ProductSendPriceMode)
    {
        $this->ProductSendPriceMode = $ProductSendPriceMode;
    }

    /**
     * @return mixed
     */
    public function getProductSendPriceMode()
    {
        return $this->ProductSendPriceMode;
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
     * @param mixed $RegisterWindowContent
     */
    public function setRegisterWindowContent($RegisterWindowContent)
    {
        $this->RegisterWindowContent = $RegisterWindowContent;
    }

    /**
     * @return mixed
     */
    public function getRegisterWindowContent()
    {
        return $this->RegisterWindowContent;
    }

    /**
     * @param mixed $SmsThirdPassword
     */
    public function setSmsThirdPassword($SmsThirdPassword)
    {
        $this->SmsThirdPassword = $SmsThirdPassword;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdPassword()
    {
        return $this->SmsThirdPassword;
    }

    /**
     * @param mixed $SmsThirdType
     */
    public function setSmsThirdType($SmsThirdType)
    {
        $this->SmsThirdType = $SmsThirdType;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdType()
    {
        return $this->SmsThirdType;
    }

    /**
     * @param mixed $SmsThirdUrl
     */
    public function setSmsThirdUrl($SmsThirdUrl)
    {
        $this->SmsThirdUrl = $SmsThirdUrl;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdUrl()
    {
        return $this->SmsThirdUrl;
    }

    /**
     * @param mixed $SmsThirdUserName
     */
    public function setSmsThirdUserName($SmsThirdUserName)
    {
        $this->SmsThirdUserName = $SmsThirdUserName;
    }

    /**
     * @return mixed
     */
    public function getSmsThirdUserName()
    {
        return $this->SmsThirdUserName;
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
     * @param mixed $UserAlbumThumbWidth
     */
    public function setUserAlbumThumbWidth($UserAlbumThumbWidth)
    {
        $this->UserAlbumThumbWidth = $UserAlbumThumbWidth;
    }

    /**
     * @return mixed
     */
    public function getUserAlbumThumbWidth()
    {
        return $this->UserAlbumThumbWidth;
    }

    /**
     * @param mixed $UserAlbumToBestMustVoteCount
     */
    public function setUserAlbumToBestMustVoteCount($UserAlbumToBestMustVoteCount)
    {
        $this->UserAlbumToBestMustVoteCount = $UserAlbumToBestMustVoteCount;
    }

    /**
     * @return mixed
     */
    public function getUserAlbumToBestMustVoteCount()
    {
        return $this->UserAlbumToBestMustVoteCount;
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
     * @param mixed $UserCommissionChild
     */
    public function setUserCommissionChild($UserCommissionChild)
    {
        $this->UserCommissionChild = $UserCommissionChild;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionChild()
    {
        return $this->UserCommissionChild;
    }

    /**
     * @param mixed $UserCommissionGrandson
     */
    public function setUserCommissionGrandson($UserCommissionGrandson)
    {
        $this->UserCommissionGrandson = $UserCommissionGrandson;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionGrandson()
    {
        return $this->UserCommissionGrandson;
    }

    /**
     * @param mixed $UserCommissionOwn
     */
    public function setUserCommissionOwn($UserCommissionOwn)
    {
        $this->UserCommissionOwn = $UserCommissionOwn;
    }

    /**
     * @return mixed
     */
    public function getUserCommissionOwn()
    {
        return $this->UserCommissionOwn;
    }

    /**
     * @param mixed $UserCount
     */
    public function setUserCount($UserCount)
    {
        $this->UserCount = $UserCount;
    }

    /**
     * @return mixed
     */
    public function getUserCount()
    {
        return $this->UserCount;
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
     * @param mixed $UserDefaultState
     */
    public function setUserDefaultState($UserDefaultState)
    {
        $this->UserDefaultState = $UserDefaultState;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultState()
    {
        return $this->UserDefaultState;
    }

    /**
     * @param mixed $UserDefaultUserGroupIdForRole
     */
    public function setUserDefaultUserGroupIdForRole($UserDefaultUserGroupIdForRole)
    {
        $this->UserDefaultUserGroupIdForRole = $UserDefaultUserGroupIdForRole;
    }

    /**
     * @return mixed
     */
    public function getUserDefaultUserGroupIdForRole()
    {
        return $this->UserDefaultUserGroupIdForRole;
    }

    /**
     * @param mixed $UserNameLength
     */
    public function setUserNameLength($UserNameLength)
    {
        $this->UserNameLength = $UserNameLength;
    }

    /**
     * @return mixed
     */
    public function getUserNameLength()
    {
        return $this->UserNameLength;
    }

    /**
     * @param mixed $UserOrderFirstSubPrice
     */
    public function setUserOrderFirstSubPrice($UserOrderFirstSubPrice)
    {
        $this->UserOrderFirstSubPrice = $UserOrderFirstSubPrice;
    }

    /**
     * @return mixed
     */
    public function getUserOrderFirstSubPrice()
    {
        return $this->UserOrderFirstSubPrice;
    }

    /**
     * @param mixed $UserRecDefaultState
     */
    public function setUserRecDefaultState($UserRecDefaultState)
    {
        $this->UserRecDefaultState = $UserRecDefaultState;
    }

    /**
     * @return mixed
     */
    public function getUserRecDefaultState()
    {
        return $this->UserRecDefaultState;
    }

    /**
     * @param mixed $UserSmsMessageContent
     */
    public function setUserSmsMessageContent($UserSmsMessageContent)
    {
        $this->UserSmsMessageContent = $UserSmsMessageContent;
    }

    /**
     * @return mixed
     */
    public function getUserSmsMessageContent()
    {
        return $this->UserSmsMessageContent;
    }//资讯内容上传的图片中的水印图文件id




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
        $defaultValue = '';
        return self::GetValue($this->SiteId, $siteConfigName, $defaultValue);
    }

    /**
     * 设置配置值
     * @param string $siteConfigName 配置名称
     * @param string $fieldValue 配置值
     */
    public function __set($siteConfigName, $fieldValue)
    {
        self::SetValue($this->SiteId, $siteConfigName, $fieldValue);
    }

    /**
     * 设置配置值
     * @param int $siteId 站点id
     * @param string $siteConfigName 配置名称
     * @param string $fieldValue 配置值
     */
    public function SetValue($siteId, $siteConfigName, $fieldValue)
    {
        $siteConfigType = self::GetSiteConfigType($siteConfigName);
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
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_config_data' . DIRECTORY_SEPARATOR . $siteId;
            $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
            parent::AddCache($cacheDir, $cacheFile, $fieldValue, 3600);
        }
    }

    private function GetSiteConfigType($siteConfigName){
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
        return $siteConfigType;
    }

    /**
     * 取得配置值（已缓冲）
     * @param int $siteId 站点id
     * @param string $siteConfigName 配置名称
     * @param mixed $defaultValue 默认值
     * @return mixed 配置值
     */
    private function GetValue($siteId, $siteConfigName, $defaultValue = null)
    {
        if (intval($siteId) > 0) {
            $siteConfigType = self::GetSiteConfigType($siteConfigName);
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_config_data' . DIRECTORY_SEPARATOR . $siteId;
            $cacheFile = 'site_config.cache_' . strtolower($siteConfigName);
            if (strlen(parent::GetCache($cacheDir, $cacheFile)) <= 0) {
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
                    parent::AddCache($cacheDir, $cacheFile, $result, 60);
                }
            } else {
                $result = parent::GetCache($cacheDir, $cacheFile);
            }
            return $result;
        } else {
            return $defaultValue;
        }
    }


    /**
     * 返回某一站点下所有配置项列表
     * @param int $siteId 站点id
     * @param bool $withCache 是否缓存，默认true
     * @return array 配置列表
     */
    public function GetList($siteId, $withCache = true)
    {
        if ($siteId > 0) {
            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'site_config_data';
            $cacheFile = 'site_config_get_list.cache_' . $siteId;
            $sql = "SELECT * FROM " . self::TableName_SiteConfig . " WHERE SiteId=:SiteId;";
            $dataProperty = new DataProperty();
            $dataProperty->AddField("SiteId", $siteId);
            return $this->GetInfoOfArrayList($sql, $dataProperty, $withCache, $cacheDir, $cacheFile);
        } else {
            return null;
        }
    }
} 