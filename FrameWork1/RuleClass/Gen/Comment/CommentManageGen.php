<?php
/**
 * 通用评论 后台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Comment
 * @author yin
 */
class CommentManageGen extends BaseManageGen implements IBaseManageGen
{

    /**
     * 操作失败
     */
    const FAIL = -1;


    public function Gen(){

        $result = "";
        $method = Control::GetRequest("m","");

        switch($method){
            case "list":
                $result = self::GenList();
                break;
            case "list_for_site":
                $result = self::GenListForSite();
                break;
            case "list_for_channel":
                $result = self::GenListForChannel();
                break;
            case "async_modify_state":
                $result = self::AsyncModifyState();
                break;
            case "reply":
                $result = self::GenReply();
                break;
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        $tableId = Control::GetRequest("table_id",0);
        $tableType = Control::GetRequest("table_type",0);
        $channelId = Control::GetRequest("channel_id",0);

        if($siteId > 0 && $tableId > 0 && $tableType > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",20);
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $templateContent = Template::Load("comment/comment_list.html","common");

            parent::ReplaceFirst($templateContent);

            $tagId = "comment_list";

            $commentManageData = new CommentManageData();

            $arrCommentList = $commentManageData->GetList($tableId,$tableType,$siteId,$pageBegin,$pageSize,$allCount);

            if(count($arrCommentList) > 0){
                Template::ReplaceList($templateContent,$arrCommentList,$tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=comment&m=list_for_channel&channel_id=".$channelId."&table_type=".$tableType."&p={0}&ps=".$pageSize;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("comment",1));
            }
            $templateContent = str_ireplace("{siteId}", $siteId, $templateContent);
            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }else{
            return "";
        }
    }

    private function GenListForSite(){
        $siteId = Control::GetRequest("site_id",0);
        $tableType = Control::GetRequest("table_type",0);

        if($siteId > 0 && $tableType > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",20);
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $templateContent = Template::Load("comment/comment_list.html","common");


            $tagId = "comment_list";
            //parent::ReplaceFirst($templateContent);

            $commentManageData = new CommentManageData();
            $commentRank = 0;
            $arrRankOneList = $commentManageData->GetListForSite($tableType,$siteId,$pageBegin,$pageSize,$allCount,$commentRank);
            $parentIds = '';
            for($i =0 ; $i<count($arrRankOneList);$i++){
                $parentIds .= ','.$arrRankOneList[$i]["CommentId"];
            }
            if(strlen($parentIds) > 1){
                $parentIds = substr($parentIds,1,strlen($parentIds));
            }



            $commentRank = 1;
            $arrRankTwoList = $commentManageData->GetListOfChild($parentIds);
            //print_r($arrRankTwoList);

            $tagName = Template::DEFAULT_TAG_NAME;
            $tableIdName = BaseData::TableId_Comment;
            $parentIdName = "ParentId";

            $arrRankThreeList = null;
            $thirdTableIdName = null;
            $thirdParentIdName = null;


            Template::ReplaceList(
                $templateContent,
                $arrRankOneList,
                $tagId,
                $tagName,
                $arrRankTwoList,
                $tableIdName,
                $parentIdName,
                $arrRankThreeList,
                $thirdTableIdName,
                $thirdParentIdName
            );


            //$commentManageData = new CommentManageData();

            //$arrCommentList = $commentManageData->GetListForSite($tableType,$siteId,$pageBegin,$pageSize,$allCount);

            //if(count($arrCommentList) > 0){
                //Template::ReplaceList($templateContent,$arrCommentList,$tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=comment&m=list_for_site&site_id=".$siteId."&table_type=".$tableType."&p={0}&ps=".$pageSize;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);
                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            //}else{
                //$templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("comment",1));
            //}

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }

        return "";
    }

    private function GenListForChannel(){
        $channelId = Control::GetRequest("channel_id",0);
        $tableType = Control::GetRequest("table_type",0);

        if($channelId > 0 && $tableType > 0){
            $pageIndex = Control::GetRequest("p",1);
            $pageSize = Control::GetRequest("ps",20);
            $allCount = 0;
            $pageBegin = ($pageIndex - 1) * $pageSize;
            $templateContent = Template::Load("comment/comment_list.html","common");
            parent::ReplaceFirst($templateContent);

            $tagId = "comment_list";

            $commentManageData = new CommentManageData();

            $arrCommentList = $commentManageData->GetListForChannel($tableType,$channelId,$pageBegin,$pageSize,$allCount);

            if(count($arrCommentList) > 0){
                Template::ReplaceList($templateContent,$arrCommentList,$tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=comment&m=list_for_channel&channel_id=".$channelId."&table_type=".$tableType."&p={0}&ps=".$pageSize;
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex, $styleNumber, $isJs, $jsFunctionName, $jsParamList);

                $templateContent = str_ireplace("{pager_button}", $pagerButton, $templateContent);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("comment",1));
            }

            parent::ReplaceEnd($templateContent);
            return $templateContent;
        }

        return "";
    }

    private function AsyncModifyState(){
        $commentId = Control::GetRequest("comment_id",0);
        $state = Control::GetRequest("state",0);

        if($commentId > 0 && $state > 0){
            $commentManageData = new CommentManageData();

            $result = $commentManageData->ModifyState($commentId,$state);
            return Control::GetRequest("jsonpcallback","").'({"result":'.$result.'})';
        }else{
            return Control::GetRequest("jsonpcallback","").'({"result":'.self::FAIL.'})';
        }
    }

    private function GenReply()
    {

        $templateFileUrl = "comment/comment_reply.html";
        $templateName = "common";
        $templatePath = "system_template";
        $templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
        parent::ReplaceFirst($templateContent);
        $siteId = Control::GetRequest("site_id", 0);
        $commentId = Control::GetRequest("commentid", "");
        $templateContent = str_ireplace("{commentId}", $commentId, $templateContent);
        $resultJavaScript = "";


        if(!empty($_POST)){

            $commentManageData = new CommentManageData();
            $parentId = Control::PostRequest("commentId", "");
            $arrCommentOne =  $commentManageData->GetOne($parentId);
            $rank = 1;
            $agreeCount = 0;
            $disagreeCount = 0;
            $sourceUrl = urldecode(Control::PostRequest("url", ""));
            $tableId = $arrCommentOne["TableId"];
            $tableType = $arrCommentOne["TableType"];
            $channelId = $arrCommentOne["ChannelId"];
            $subject = Format::FormatHtmlTag(Control::PostRequest("subject", ""));
            $content = Format::FormatHtmlTag(Control::PostRequest("commentContent", ""));


            $guestName = Format::FormatHtmlTag(Control::PostRequest("guest_name", ""));
            $guestEmail = Format::FormatHtmlTag(Control::PostRequest("guest_email", ""));
            $reUrl = urldecode(Control::PostRequest("url", ""));
            $commentType = intval(Control::PostRequest("comment_type", CommentData::COMMENT_TYPE_SHORT_TEXT));
            //



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

            $userId = Control::GetUserId();
            $userName = Control::GetUserName();
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

            $result = $commentPublicData->Reply(
                $parentId,
                $rank,
                $tableId,
                $tableType,
                $subject,
                $content,
                $userId,
                $userName,
                $guestName,
                $agreeCount,
                $disagreeCount,
                $guestEmail,
                $state,
                $commentType,
                $siteId,
                $channelId,
                $sourceUrl
            );
            if ($result > 0) {
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
                        $documentNewsPublicData = new DocumentNewsPublicData();
                        $documentNewsPublicData->AddCommentCount($tableId);
                        break;
                    case CommentData::COMMENT_TABLE_TYPE_OF_NEWSPAPER: //电子报
                        $newspaperArticlePublicData = new NewspaperArticlePublicData();
                        $newspaperArticlePublicData->AddCommentCount($tableId);
                        break;
                }
                //Control::GoUrl($reUrl);
                Control::ShowMessage("评论回复成功");
                //$resultJavaScript = Control::GetJqueryMessage(Language::Load('comment', 2));
            } else {
                Control::ShowMessage("评论回复失败");
                //$resultJavaScript = Control::GetJqueryMessage(Language::Load('comment', 3));
                //Control::ShowMessage("回复发表失败,请再尝试.");
            }
        }
        parent::ReplaceEnd($templateContent);
        //$templateContent = str_ireplace("{ResultJavascript}", $resultJavaScript, $templateContent);
        return $templateContent;
    }
}