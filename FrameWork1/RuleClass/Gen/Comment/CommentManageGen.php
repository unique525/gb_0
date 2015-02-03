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
        }
        $result = str_ireplace("{method}", $method, $result);
        return $result;
    }

    private function GenList(){
        $siteId = Control::GetRequest("site_id",0);
        $tableId = Control::GetRequest("table_id",0);
        $tableType = Control::GetRequest("table_type",0);

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
            parent::ReplaceFirst($templateContent);

            $tagId = "comment_list";

            $commentManageData = new CommentManageData();

            $arrCommentList = $commentManageData->GetListForSite($tableType,$siteId,$pageBegin,$pageSize,$allCount);

            if(count($arrCommentList) > 0){
                Template::ReplaceList($templateContent,$arrCommentList,$tagId);
                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=comment&m=list_for_site&site_id=".$siteId."&table_type=".$tableType."&p={0}&ps=".$pageSize;
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
}