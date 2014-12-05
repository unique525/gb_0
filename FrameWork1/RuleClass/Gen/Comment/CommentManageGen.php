<?php
/**
 * 通用评论 后台 生成类
 * @category iCMS
 * @package iCMS_Rules_Gen_Comment
 * @author yin
 */
class CommentManageGen extends BaseManageGen implements IBaseManageGen
{
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
            $templateContent = Template::Load("comment/comment_list.htm","common");
            $tagId = "comment_list";

            $commentManageData = new CommentManageData();

            $arrCommentList = $commentManageData->GetList($tableId,$tableType,$siteId,$pageBegin,$pageSize,$allCount);

            if(count($arrCommentList) > 0){
                Template::ReplaceList($templateContent,$arrCommentList,$tagId);
            }else{
                $templateContent = Template::ReplaceCustomTag($templateContent,$tagId,Language::Load("comment",1));
            }

            return $templateContent;
        }else{
            return "";
        }
    }

    private function GenListForSite(){
        $siteId = Control::GetRequest("site_id",0);

        if($siteId > 0){

        }

        return "";
    }

    private function GenListForChannel(){
        $siteId = Control::GetRequest("site_id",0);
        $channelId = Control::GetRequest("channel_id",0);

        if($siteId > 0 && $channelId > 0){}
        return "";
    }
}