<?php

/**
 * 后台论坛生成类 
 * @category iCMS
 * @package iCMS_Rules_Gen_Forum
 * @author zhangchi
 */
class ForumManageGen extends BaseManageGen implements IBaseManageGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function Gen() {
        $result = "";
        $method = Control::GetRequest("a", "");
        switch ($method) {
            case "list":
                $result = self::GenManageList();
                break;
            case "create":
                $result = self::GenCreate();
                break;
            case "modify":
                $result = self::GenModify();
                break;
            case "async_modifystate":
                $result = self::AsyncModifyState();
        }
        $result = str_ireplace("{action}", $method, $result);
        return $result;
    }

    /**
     * 后台版块列表
     */
    private function GenManageList() {
        $siteId = intval(Control::GetRequest("siteid", 0));

        if ($siteId > 0) {

            $tempContent = Template::Load("forum/forum_manage_list.html", "common");
            parent::ReplaceFirst($tempContent);


            $forumManageData = new ForumManageData();
            $forumRank = 0;
            $arrRankOneList = $forumManageData->GetListByRank($siteId, $forumRank);
            $forumRank = 1;
            $arrRankTwoList = $forumManageData->GetListByRank($siteId, $forumRank);
            $forumRank = 2;
            $arrRankThreeList = $forumManageData->GetListByRank($siteId, $forumRank);

            if (count($arrRankOneList) > 0) {

                $forumManageListOneTemplate = Template::Load("forum/forum_manage_list_one.html", "common");
                $forumManageListTwoTemplate = Template::Load("forum/forum_manage_list_two.html", "common");

                $resultTemplate = "";

                for ($i = 0; $i < count($arrRankOneList); $i++) {
                    $rankOneForumId = intval($arrRankOneList[$i]["ForumId"]);
                    $rankOneForumName = $arrRankOneList[$i]["ForumName"];
                    $rankOneForumType = intval($arrRankOneList[$i]["ForumType"]);
                    $rankOneState = intval($arrRankOneList[$i]["State"]);
                    $rankOneSort = intval($arrRankOneList[$i]["Sort"]);
                    $rankOneForumMode = intval($arrRankOneList[$i]["ForumMode"]);
                    $rankOneForumAccess = intval($arrRankOneList[$i]["ForumAccess"]);

                    $forumOneTemplate = $forumManageListOneTemplate;
                    $forumOneTemplate = str_ireplace("{f_ForumId}", $rankOneForumId, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_ForumName}", $rankOneForumName, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_State}", $rankOneState, $forumOneTemplate);
                    $forumOneTemplate = str_ireplace("{f_Sort}", $rankOneSort, $forumOneTemplate);

                    $resultTemplate = $resultTemplate . $forumOneTemplate;

                    for ($j = 0; $j < count($arrRankTwoList); $j++) {
                        $rankTwoForumId = intval($arrRankTwoList[$j]["ForumId"]);
                        $rankTwoParentId = intval($arrRankTwoList[$j]["ParentId"]);
                        $rankTwoForumName = $arrRankTwoList[$j]["ForumName"];
                        $rankTwoForumType = intval($arrRankTwoList[$j]["ForumType"]);
                        $rankTwoState = intval($arrRankTwoList[$j]["State"]);
                        $rankTwoSort = intval($arrRankTwoList[$j]["Sort"]);
                        $rankTwoForumMode = intval($arrRankTwoList[$j]["ForumMode"]);
                        $rankTwoForumAccess = intval($arrRankTwoList[$j]["ForumAccess"]);

                        if ($rankOneForumId === $rankTwoParentId) {
                            $forumTwoTemplate = $forumManageListTwoTemplate;
                            $forumTwoTemplate = str_ireplace("{f_forum_id}", $rankTwoForumId, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumName}", $rankTwoForumName, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_State}", $rankTwoState, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_Sort}", $rankTwoSort, $forumTwoTemplate);
                            $resultTemplate = $resultTemplate . $forumTwoTemplate;
                        }
                    }
                }
            }

            $tempContent = str_ireplace("{ForumList}", $resultTemplate, $tempContent);


            parent::ReplaceEnd($tempContent);
            return $tempContent;
        } else {
            return parent::ShowError(Language::Load("forum", 1));
        }
    }

    /**
     * 修改论坛状态
     */
    private function AsyncModifyState() {
        $forumId = Control::GetRequest("forumid", 0);
        $state = Control::GetRequest("state", -1);
        $result = -1;
        if ($forumId > 0 && $state >= 0) {

            $forumManageData = new ForumManageData();
            $result = $forumManageData->ModifyState($forumId, $state);
        }
        return $result;
    }

}

?>