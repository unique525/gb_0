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
            $arrRankOneList = $forumManageData->GetList($siteId, $forumRank);
            $forumRank = 1;
            $arrRankTwoList = $forumManageData->GetList($siteId, $forumRank);
            $forumRank = 2;
            $arrRankThreeList = $forumManageData->GetList($siteId, $forumRank);

            if (count($arrRankOneList) > 0) {

                $forumManageListOneTemplate = Template::Load("forum/forum_manage_list_one.html", "common");
                $resultTemplate = "";

                for ($i = 0; $i < count($arrRankOneList); $i++) {
                    $rankOneForumId = intval($arrRankOneList[$i]["ForumId"]);
                    $rankOneForumName = $arrRankOneList[$i]["ForumName"];
                    $rankOneForumType = intval($arrRankOneList[$i]["ForumType"]);
                    $rankOneState = intval($arrRankOneList[$i]["State"]);
                    $rankOneForumMode = intval($arrRankOneList[$i]["ForumMode"]);
                    $rankOneForumAccess = intval($arrRankOneList[$i]["ForumAccess"]);
                    $rankOneForumAdContent = $arrRankOneList[$i]["ForumAdContent"];
                    $rankOneForumMod = "";

                    $forumOneTemplate = $forumManageListOneTemplate;
                    $forumOneTemplate = str_ireplace("{ForumName}", $rankTwoForumName, $forumOneTemplate);

                    $resultTemplate = $resultTemplate . $forumOneTemplate;
                }
            }

            $tempContent = str_ireplace("{ForumList}", $resultTemplate, $tempContent);


            parent::ReplaceEnd($tempContent);
            return $tempContent;
        } else {
            return parent::ShowError(Language::Load("forum", 1));
        }
    }

}

?>
