<?php
/**
 * 后台 论坛帖子类型管理
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author momo
 */

class ForumTopicTypeManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = '';
        $method = Control::GetRequest('m', '');
        switch ($method) {
            case 'list':
                $result = self::GenList();
                break;
            case 'create_page':  //创建增加类型的编辑页面
                $result = self::CreatePage();
                break;
            case 'create':
                $result = self::Create();
                break;
            case 'modify_state':
                $result = self::ModifyState();
                break;
            case 'delete_type':
                $result = self::DeleteType();
                break;
            case 'modify_page':
                $result = self::ModifyPage();
                break;
            case 'modify_type_name':
                $result = self::ModifyTypeName();
                break;

        }
        return $result;
    }

    private function GenList(){
        $result = '尚未添加类型';

        $forumId = Control::GetRequest('forum_id', '');

        $pageSize         = Control::GetRequest("ps", 20);
        $pageIndex        = Control::GetRequest("p", 1);
        $searchKey        = Control::GetRequest("search_key", "");
        $searchKey        = urldecode($searchKey);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        $tagId = 'forum_list_topic_type';

        if($siteId > 0 && $forumId > 0 && $pageIndex > 0){

            $tempContent = Template::Load("forum/forum_topic_type_list.html", "common");
            parent::ReplaceFirst($tempContent);

            $pageBegin = ($pageIndex - 1) * $pageSize;
            $allCount = 0;

            $forumTopicTypeManageData = new ForumTopicTypeManageData();
            $arrList = $forumTopicTypeManageData->GetListPager($siteId, $forumId, $pageBegin, $pageSize, $allCount, $searchKey);

            if($arrList != null && count($arrList) > 0){
                Template::ReplaceList($tempContent, $arrList, $tagId);

                $styleNumber = 1;
                $pagerTemplate = Template::Load("pager/pager_style$styleNumber.html", "common");
                $isJs = FALSE;
                $navUrl = "default.php?secu=manage&mod=forum_topic_type&m=list&site_id=$siteId&p={0}&ps=$pageSize&forum_id=$forumId";
                $jsFunctionName = "";
                $jsParamList = "";
                $pagerButton = Pager::ShowPageButton($pagerTemplate, $navUrl, $allCount, $pageSize, $pageIndex ,$styleNumber = 1, $isJs, $jsFunctionName, $jsParamList);

                $replace_arr = array(
                    "{PagerButton}" => $pagerButton
                );
                $tempContent = strtr($tempContent, $replace_arr);
                parent::ReplaceEnd($tempContent);
                $result = $tempContent;
            }

        }
        return $result;
    }

    private function CreatePage(){
        $result = -4;

        $tempContent = Template::Load('forum/forum_topic_type_create.html', 'common');

        $forumId =Control::GetRequest('forum_id', 0);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        if($siteId > 0 && $forumId > 0){
            $tempContent = str_ireplace("{siteId}", strval($siteId), $tempContent);
            $tempContent = str_ireplace("{forumId}", strval($forumId), $tempContent);
            $result = $tempContent;
        }
        return $result;
    }

    private function Create(){
        $result = -4;

        $forumId = Control::GetRequest('forum_id', 0);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        $newType = Control::PostRequest('newType', '');

        if($siteId > 0 && $forumId > 0){
            $forumTopicTypeManageData = new ForumTopicTypeManageData();
            $result = $forumTopicTypeManageData->Create($siteId, $forumId,$newType);
        }
        return $result;
    }

    private function ModifyState(){
        $result = -4;

        $forumId          = Control::GetRequest('forum_id', 0);
        $forumTopicTypeId = Control::GetRequest('forum_topic_type_id',0);
        $state            = Control::GetRequest('state', -1);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);



        if($siteId > 0 && $forumId > 0 && $forumTopicTypeId >0 && $state != -1){

            $forumTopicTypeManageData = new ForumTopicTypeManageData();
            $result = $forumTopicTypeManageData->ModifyState($siteId, $forumId, $forumTopicTypeId, $state);
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }


    private function DeleteType(){
        $result = -4;

        $forumId          = Control::GetRequest('forum_id', 0);
        $forumTopicTypeId = Control::GetRequest('forum_topic_type_id',0);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        if($siteId > 0 && $forumId > 0 && $forumTopicTypeId >0 ){

            $forumTopicTypeManageData = new ForumTopicTypeManageData();
            $result = $forumTopicTypeManageData->DeleteType($siteId, $forumId, $forumTopicTypeId);
        }
        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }

    private function ModifyPage(){
        $result = -4;

        $tempContent = Template::Load('forum/forum_topic_type_modify.html', 'common');

        $forumId =Control::GetRequest('forum_id', 0);
        $forumTopicTypeId = Control::GetRequest('forum_topic_type_id',0);

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        if($siteId > 0 && $forumId > 0){
            $tempContent = str_ireplace("{forumId}", strval($forumId), $tempContent);
            $tempContent = str_ireplace("{forumTopicTypeId}", strval($forumTopicTypeId), $tempContent);
            $result = $tempContent;
        }
        return $result;
    }

    private function ModifyTypeName(){
        $result = -4;

        $forumId          = Control::GetRequest('forum_id', 0);
        $forumTopicTypeId = Control::GetRequest('forum_topic_type_id',0);
        $newTypeName      = Control::GetRequest('forum_new_type_name', '');

        $forumManageData = new ForumManageData();
        $siteId = $forumManageData->GetSiteId($forumId, false);

        if($siteId > 0 && $forumId > 0 && $forumTopicTypeId >0 && count($newTypeName) > 0){

            $forumTopicTypeManageData = new ForumTopicTypeManageData();
            $result = $forumTopicTypeManageData->ModifyTypeName($siteId, $forumId, $forumTopicTypeId, $newTypeName);
        }

        return Control::GetRequest("jsonpcallback", "") . '({"result":' . $result . '})';
    }
}
