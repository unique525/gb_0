<?php
/**
 * 公共 会员权限 数据类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_DataProvider_User
 * @author zhangchi
 */
class UserPopedomData {

    /**
     * 允许发表主题
     * @var bool
     */
    const ForumAllowPostTopic = "ForumAllowPostTopic"; //允许发表主题
    /**
     * 允许发表回复
     * @var bool
     */
    private $ForumAllowPostReply; //允许发表回复
    /**
     * 发表主题允许的最大字符数
     * @var int
     */
    private $ForumPostTopicMaxContentCount; //发表主题允许的最大字符数
    /**
     * 发表回复允许的最大字符数
     * @var int
     */
    private $ForumPostReplyMaxContentCount; //发表回复允许的最大字符数
    /**
     * 发表主题允许的最少字符数
     * @var int
     */
    private $ForumPostTopicMinContentCount; //发表主题允许的最少字符数
    /**
     * 发表回复允许的最少字符数
     * @var int
     */
    private $ForumPostReplyMinContentCount; //发表回复允许的最少字符数
    /**
     * 允许发布高级主题(如回复可见,积分可见等形式的主题)
     * @var bool
     */
    private $ForumAllowPostAdvancedTopicAccess; //允许发布高级主题(如回复可见,积分可见等形式的主题)
    /**
     * 允许发布高级主题标题(如多种颜色,加粗的主题)
     * @var bool
     */
    private $ForumAllowPostAdvancedTopicTitle; //允许发布高级主题标题(如多种颜色,加粗的主题)
    /**
     * 允许发布多媒体主题(如视频、Flash、框架页面等)
     * @var bool
     */
    private $ForumAllowPostMediaTopic; //允许发布多媒体主题(如视频、Flash、框架页面等)
    /**
     * 允许发布多媒体回复(如视频、Flash、框架页面等)
     * @var bool
     */
    private $ForumAllowPostMediaReply; //允许发布多媒体回复(如视频、Flash、框架页面等)
    /**
     * 编辑自己的帖子
     * @var bool
     */
    private $ForumEditSelfPost; //编辑自己的帖子
    /**
     * 编辑其他人的帖子
     * @var bool
     */
    private $ForumEditOtherPost; //编辑其他人的帖子
    /**
     * 删除自己的帖子
     * @var bool
     */
    const ForumDeleteSelfPost = "ForumDeleteSelfPost"; //删除自己的帖子
    const ForumDeleteOtherPost = "ForumDeleteOtherPost"; //删除其他人的帖子
    private $ForumForbidOtherEditMyTopic; //禁止其他人编辑自己的主题(一般只有管理员有此权限)
    private $ForumForbidOtherEditMyReply; //禁止其他人编辑自己的回复(一般只有管理员有此权限)
    private $ForumForbidOtherDeleteMyTopic; //禁止其他人删除自己的主题(一般只有管理员有此权限)
    private $ForumForbidOtherDeleteMyReply; //禁止其他人删除自己的回复(一般只有管理员有此权限)
    private $ForumSetSelfTopicLock; //将自己的主题设为锁定状态
    private $ForumSetSelfTopicBanReply; //将自己的主题设为禁止回复状态
    private $ForumSetOtherTopicLock; //将其他人的主题设为锁定状态
    private $ForumSetOtherTopicBanReply; //将其他人的主题设为禁止回复状态
    private $ForumPostAddMoney; //在帖子中给帖主增加{ForumMoneyName}
    private $ForumPostAddMoneyForSelf; //是否允许给自己增加{ForumMoneyName}
    private $ForumPostAddMoneyLimit; //每日限额{ForumMoneyName}
    private $ForumPostAddCharm; //在帖子中给帖主增加{ForumCharmName}
    private $ForumPostAddCharmForSelf; //是否允许给自己增加{ForumCharmName}
    private $ForumPostAddCharmLimit; //每日限额{ForumCharmName}
    private $ForumPostAddScore; //在帖子中给帖主增加{ForumScoreName}
    private $ForumPostAddScoreForSelf; //是否允许给自己增加{ForumScoreName}
    private $ForumPostAddScoreLimit; //每日限额{ForumScoreName}
    private $ForumPostAddExp; //在帖子中给帖主增加{ForumExpName}
    private $ForumPostAddExpForSelf; //是否允许给自己增加{ForumExpName}
    private $ForumPostAddExpLimit; //每日限额{ForumExpName}

    /**
     * 将主题设为版块置顶
     */
    const ForumPostSetBoardTop = "ForumPostSetBoardTop";
    /**
     * 将主题设为分区版块置顶
     */
    const ForumPostSetRegionTop = "ForumPostSetRegionTop";
    /**
     * 将主题设为全部版块置顶
     */
    const ForumPostSetAllTop = "ForumPostSetAllTop";
    private $ForumMoveTopic; //移动主题(可以移动自己或他人的主题，一般只有版主以上身份有此权限)
    private $ForumCopyTopic; //复制主题(可以复制自己或他人的主题，一般只有版主以上身份有此权限)
    private $ForumSetBestTopic; //将主题设为精华主题
    private $ForumSetRecTopic; //将主题设为推荐主题
    private $ForumPostAllowUpload; //允许在帖子中上传文件
    private $ForumPostAllowUploadType; //帖子中允许上传的文件类型
    private $ForumPostMaxUploadSize; //帖子中允许上传的文件大小（KB）
    private $ForumPostMaxUploadPerDay; //每日最大可上传的文件数
    private $ForumPostMaxUploadPerPost; //每个帖子最大可上传的文件数
    private $ForumAllowViewAttachment; //允许查看帖子中上传的文件
    private $ForumShowEditInfo; //编辑帖子时是否显示编辑信息
    private $ForumAllowSearch; //允许使用论坛搜索功能
    private $ForumIgnoreLimit; //忽视帖子中的所有限制设定（一般只有管理员有此权限）

    private $UserAllowHidden; //允许隐身登录


    private $UserSignMaxContentCount; //会员签名的最大字符数
    private $UserAlbumMaxUploadPerOnce; //会员相册单次最大上传文件数

    private $UserCanPostActivity; //是否允许发布活动
    private $UserSetRecCountLimit;  //给会员相册打推荐分的最大值
    private $UserSetRecCountDayMax; //给会员相册打推荐分的每日上限值

} 