<?php

/**
 * 通用评论 前台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Comment
 * @author yin
 */
class CommentPublicGen extends BasePublicGen implements IBasePublicGen
{
    /**
     *没有购买
     */
    const IS_NOT_BOUGHT = -2;
    /**
     *系统错误
     */
    const SYSTEM_ERROR = -3;
    /**
     *没有登录
     */
    const IS_NOT_LOGIN = -4;
    /**
     *成功
     */
    const SUCCESS = 1;
    /**
     *参数错误
     */
    const PARAMETER_ERROR = -1;

    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "create":
                $result = self::GenCreate();
                break;
            case "list":
                $result = self::GenList();
                break;
        }
        return $result;
    }

    private function GenCreate()
    {
        $tableId = intval(Control::PostRequest("table_id", 0));
        $tableType = intval(Control::PostRequest("table_type", 0));
        $subject = Format::FormatHtmlTag(Control::PostRequest("subject", ""));
        $content = Format::FormatHtmlTag(Control::PostRequest("content", ""));
        $siteId = intval(parent::GetSiteIdByDomain());
        $channelId = intval(Control::PostRequest("channel_id", 0));
        $guestName = Format::FormatHtmlTag(Control::PostRequest("guest_name", ""));
        $guestEmail = Format::FormatHtmlTag(Control::PostRequest("guest_email", ""));
        $url = urldecode(Control::PostRequest("url", ""));
        $commentType = intval(Control::PostRequest("comment_type", CommentData::COMMENT_TYPE_SHORT_TEXT));

        $commentPublicData = new CommentPublicData();

        //判断引用
//        if ($tableId > 0 && $tableType > 0 && $siteId > 0 && strlen($content) >= 0) {
//            $citeId = Control::GetRequest("citeid", 0);
//            if ($citeId > 0) {
//                $result = $commentData->GetOneComment($citeId);
//                $userId = $result["UserID"];
//                if ($userId > 0) {
//                    $userData = new UserData();
//                    $userName = $userData->GetUserNickName($userId);
//                } else {
//                    $userName = "游客";
//                }
//                $citeContent = $result["Content"];
//                //处理引用
//                $citeContent = '{box}{span}' . $userName . '{/span}' . $citeContent . '{/box}';
//                $content = $citeContent . $content;
//            }

        /*******************过滤字符 begin********************** */
//            $mutiFilterContent = array();
//            $mutiFilterContent[0] = $subject;
//            $mutiFilterContent[1] = $content;
//            $mutiFilterContent[2] = $guestName;
//            $mutiFilterContent[3] = $guestEmail;
//            $siteFilterGen = new SiteFilterGen();
//            $useArea = 4;  //过滤范围 4:评论
//            $stop = FALSE; //是否停止执行
//            $filterContent = null;
//            $stopWord = $siteFilterGen->DoFilter($siteId, $useArea, $stop, $filterContent, $mutiFilterContent);
//            $subject = $mutiFilterContent[0];
//            $content = $mutiFilterContent[1];
//            $guestName = $mutiFilterContent[2];
//            $guestEmail = $mutiFilterContent[3];
        /*******************过滤字符 end********************** */

        $userId = Control::GetUserId();
        $userName = Control::GetUserName();
        $openState = 0;
        switch ($tableType) {
            case CommentData::COMMENT_TABLE_TYPE_OF_USER_ALBUM: //相册
                $openState = 10;
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_USER_ALBUM_PIC: //相片
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_ACTIVE: //活动
//                    $activityData = new ActivityData();
//                    $openState = $activityData->GetOpenComment($tableId);
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_PRODUCT: //产品
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_SITE_CONTENT: //站点内容
                $openState = 10;
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_CHANNEL: //频道评论
                $channelPublicData = new ChannelPublicData();
                $openState = $channelPublicData->GetOpenComment($tableId);
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_DOCUMENT_NEWS: //新闻资讯
                $documentNewsPublicData = new DocumentNewsPublicData();
                $openState = $documentNewsPublicData->GetOpenComment($tableId,TRUE);
                if ($openState == 40) { //根据频道设置而定
                    $channelId = $documentNewsPublicData->GetChannelID($tableId,TRUE);
                    $channelPublicData = new ChannelPublicData();
                    $openState = $channelPublicData->GetOpenComment($channelId);
                }
                break;
            case CommentData::COMMENT_TABLE_TYPE_OF_NEWSPAPER: //电子报
                $documentNewsPublicData = new DocumentNewsPublicData();
                $openState = $documentNewsPublicData->GetOpenComment($tableId,TRUE);
                if ($openState == 40) { //根据频道设置而定
                    $channelId = $documentNewsPublicData->GetChannelID($tableId,TRUE);
                    $channelPublicData = new ChannelPublicData();
                    $openState = $channelPublicData->GetOpenComment($channelId);
                }
                break;
        }

        //开放评论状态 0:不允许 10:先审后发 20:先发后审 30:自由评论 40:根据频道设置而定
        //评论状态 0:未审 10:先审后发 20:先发后审 40:已否
        $state = 0;
        switch ($openState) {
            case 10:
                $state = CommentData::COMMENT_STATE_FIRST_CHECK_THEN_PUBLISH;
                break;
            case 20:
                $state = CommentData::COMMENT_STATE_FIRST_PUBLISH_THEN_CHECK;
                break;
            case 30:
                $state = CommentData::COMMENT_STATE_UN_CHECK;
                break;
        }

        $result = $commentPublicData->Create($siteId, $subject, $content, $channelId, $tableId, $tableType, $userId, $userName, $guestName, $guestEmail, $state, $commentType);
        if ($result > 0) {
            if ($tableType == 1) { //更新相册和会员信息表的统计

            }
            Control::GoUrl($url);
        } else {
            Control::ShowMessage("评论发表失败,请再尝试.");
        }

        return $result;
    }

    private function GenList(){
        $tableId = intval(Control::GetRequest("table_id",0));
        $tableType = intval(Control::GetRequest("table_type",0));
        $commentType = intval(Control::GetRequest("comment_type", 1));
        $siteId = intval(parent::GetSiteIdByDomain());

        if($tableId > 0 && $tableType > 0 && $siteId > 0){
            $pageSize = Control::GetRequest("ps", 5);
            $pageIndex = Control::GetRequest("p", 1);
            if ($pageIndex === 0) {
                $pageIndex = 1;
            }

            $commentPublicData = new CommentPublicData();

            if ($pageIndex > 0 && $tableId > 0) {
                $pageBegin = ($pageIndex - 1) * $pageSize;
                $allCount = 0;
                $arrList = $commentPublicData->GetList($tableId,$tableType,$siteId,$commentType,$allCount,$pageBegin,$pageSize);
                if (count($arrList) > 0) {
                    $templateFileUrl = "comment/pager_comment_js.html";
                    $templateName = "default";
                    $templatePath = "front_template";
                    $pagerTemplate = Template::Load($templateFileUrl, $templateName, $templatePath);
                    $isJs = true;
                    $jsFunctionName = "CommentShow";
                    $jsParamList = "," . $tableId . "," . $tableType . ",'".' '."'";
                    $pagerButton = Pager::ShowPageButton(
                        $pagerTemplate,
                        "",
                        $allCount,
                        $pageSize,
                        $pageIndex ,
                        1,
                        $isJs,
                        $jsFunctionName,
                        $jsParamList,
                        $pageIndex,
                        $pageSize,
                        TRUE,
                        TRUE,
                        ""
                    );
                    $result = Format::FixJsonEncode($arrList);
                    $pagerButton = Format::FixJsonEncode($pagerButton);

                    return Control::GetRequest("jsonpcallback","") . '({"result":' . $result . ',"count":' . $allCount . ',"page_button":' . $pagerButton . '})';
                }else{
                    return Control::GetRequest("jsonpcallback","") . '({"result":""})';
                }
            }else{
                return Control::GetRequest("jsonpcallback","") . '({"result":""})';
            }
        }else{
            return Control::GetRequest("jsonpcallback","") . '({"result":""})';
        }
    }
}