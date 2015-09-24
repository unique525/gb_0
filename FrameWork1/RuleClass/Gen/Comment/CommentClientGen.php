<?php

/**
 * 客户端 评论 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Comment
 * @author zhangchi
 */
class CommentClientGen extends BaseClientGen
{
    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenClient()
    {
        $result = "";
        $function = Control::GetRequest("f", "");
        switch ($function) {

            case "create":
                $result = self::GenCreate();
                break;

            case "list":
                $result = self::GenList();
                break;

            case "list_of_user":
                $result = self::GenListOfUser();
                break;

        }
        $result = str_ireplace("{function}", $function, $result);
        return $result;
    }

    private function GenCreate()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            $tableId = intval(Control::PostORGetRequest("table_id", 0));
            $tableType = intval(Control::PostORGetRequest("table_type", 0));
            $subject = Format::FormatHtmlTag(Control::PostORGetRequest("subject", ""));
            $content = Format::FormatHtmlTag(Control::PostORGetRequest("content", ""));
            $siteId = intval(Control::PostORGetRequest("site_id", ""));
            $channelId = intval(Control::PostORGetRequest("channel_id", 0));
            $guestName = Format::FormatHtmlTag(Control::PostORGetRequest("guest_name", ""));
            $guestEmail = Format::FormatHtmlTag(Control::PostORGetRequest("guest_email", ""));
            $reUrl = urldecode(Control::PostORGetRequest("url", ""));
            $commentType = intval(Control::PostORGetRequest("comment_type", CommentData::COMMENT_TYPE_SHORT_TEXT));

            $commentClientData = new CommentClientData();

            /*******************过滤字符 begin********************** */
            $multiFilterContent = array();
            $multiFilterContent[0] = $subject;
            $multiFilterContent[1] = $content;
            $multiFilterContent[2] = $guestName;
            $multiFilterContent[3] = $guestEmail;
            $useArea = 4; //过滤范围 4:评论
            $stop = FALSE; //是否停止执行
            $filterContent = null;
            $stopWord = parent::DoFilter($siteId, $useArea, $stop, $filterContent, $multiFilterContent);
            $subject = $multiFilterContent[0];
            $content = $multiFilterContent[1];
            $guestName = $multiFilterContent[2];
            $guestEmail = $multiFilterContent[3];
            /*******************过滤字符 end********************** */

            $userClientData = new UserClientData();

            $userName = $userClientData->GetUserName($userId, true);
            $openState = 0;

            //开放评论状态 0:不允许 10:先审后发 20:先发后审 30:自由评论 40:根据频道设置而定
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
                    $openState = $channelPublicData->GetOpenComment($tableId, TRUE);
                    break;
                case CommentData::COMMENT_TABLE_TYPE_OF_DOCUMENT_NEWS: //新闻资讯
                    $documentNewsPublicData = new DocumentNewsPublicData();
                    $openState = $documentNewsPublicData->GetOpenComment($tableId, FALSE);
                    if ($openState == 40) { //根据频道设置而定
                        $channelId = $documentNewsPublicData->GetChannelId($tableId, FALSE);
                        $channelPublicData = new ChannelPublicData();
                        $openState = $channelPublicData->GetOpenComment($channelId, TRUE);
                    }
                    break;
                case CommentData::COMMENT_TABLE_TYPE_OF_NEWSPAPER: //电子报
                    $newspaperArticlePublicData = new NewspaperArticlePublicData();
                    $channelId = $newspaperArticlePublicData->GetChannelId($tableId, TRUE);
                    $channelPublicData = new ChannelPublicData();
                    $openState = $channelPublicData->GetOpenComment($channelId, TRUE);
                    break;
            }

            //评论状态 0:未审 10:先审后发 20:先发后审 30:已审 100:已否
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

            $result = $commentClientData->Create(
                $siteId,
                $subject,
                $content,
                $channelId,
                $tableId,
                $tableType,
                $userId,
                $userName,
                $guestName,
                $guestEmail,
                $state,
                $commentType,
                $reUrl
            );

            if ($result > 0) {

                $resultCode = 1;

                $arrOne = $commentClientData->GetOne($result);

                $result = Format::FixJsonEncode($arrOne);


                switch ($tableType) {
                    case CommentData::COMMENT_TABLE_TYPE_OF_USER_ALBUM: //相册

                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_USER_ALBUM_PIC: //相片
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_ACTIVE: //活动
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_PRODUCT: //产品
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_SITE_CONTENT: //站点内容
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_CHANNEL: //频道评论
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_DOCUMENT_NEWS: //新闻资讯
                        $documentNewsClientData = new DocumentNewsClientData();
                        $documentNewsClientData->AddCommentCount($tableId);
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_NEWSPAPER: //电子报
                        $newspaperArticleClientData = new NewspaperArticleClientData();
                        $newspaperArticleClientData->AddCommentCount($tableId);
                        break;
                }

            } else {
                $resultCode = -6;
            }


        }


        return '{"result_code":"' . $resultCode . '","comment_create":' . $result . '}';
    }

    private function GenList()
    {
        $result = "[{}]";
        $tableId = intval(Control::PostOrGetRequest("table_id", 0));
        $tableType = intval(Control::PostOrGetRequest("table_type", 0));
        $commentType = intval(Control::PostOrGetRequest("comment_type", 1));
        $siteId = intval(Control::PostOrGetRequest("site_id", 0));

        if ($tableId > 0 && $tableType > 0 && $siteId > 0) {
            $pageSize = Control::PostOrGetRequest("ps", 5);
            $pageIndex = Control::PostOrGetRequest("p", 1);
            if ($pageIndex === 0) {
                $pageIndex = 1;
            }

            $commentClientData = new CommentClientData();

            $pageBegin = ($pageIndex - 1) * $pageSize;

            $arrList = $commentClientData->GetList(
                $tableId,
                $tableType,
                $commentType,
                $pageBegin,
                $pageSize);
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);


            } else {
                $resultCode = -1;
            }

        } else {
            $resultCode = -6; //参数错误
        }

        return '{"result_code":"' . $resultCode . '","comment":{"comment_list":' . $result . '}}';
    }

    private function GenListOfUser()
    {
        $result = "[{}]";
        $userId = parent::GetUserId();

        if ($userId <= 0) {
            $resultCode = $userId; //会员检验失败,参数错误
        } else {

            $pageSize = Control::PostOrGetRequest("ps", 5);
            $pageIndex = Control::PostOrGetRequest("p", 1);
            if ($pageIndex === 0) {
                $pageIndex = 1;
            }

            $commentClientData = new CommentClientData();

            $pageBegin = ($pageIndex - 1) * $pageSize;

            $arrList = $commentClientData->GetListOfUser(
                $userId,
                $pageBegin,
                $pageSize);
            if (count($arrList) > 0) {
                $resultCode = 1;
                $result = Format::FixJsonEncode($arrList);


            } else {
                $resultCode = -1;
            }


        }


        return '{"result_code":"' . $resultCode . '","comment":{"comment_list":' . $result . '}}';
    }
}