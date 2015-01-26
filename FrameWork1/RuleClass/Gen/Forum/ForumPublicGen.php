<?php

/**
 * 前台 论坛 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Forum
 * @author zhangchi
 */
class ForumPublicGen extends ForumBasePublicGen implements IBasePublicGen {

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic() {
        $result = "";

        $action = Control::GetRequest("a", "");
        switch ($action) {
            default:
                $result = self::GenDefault();
                break;
        }

        return $result;
    }

    /**
     * 生成论坛首页
     * @return string 论坛首页HTML
     */
    private function GenDefault() {
        $siteId = Control::GetRequest("siteid", 0);
        if ($siteId <= 0) {
            $siteId = parent::GetSiteIdByDomain();
        }

        $templateFileUrl = "forum/forum_default.html";
        $templateName = "default";
        $templatePath = "front_template";
        $tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);

        parent::ReplaceFirstForForum($tempContent);

        /******************  顶部推荐栏  ********************** */
        $templateForumRecTopicFileUrl = "forum/forum_rec_1.html";
        $templateForumRecTopic = Template::Load($templateForumRecTopicFileUrl, $templateName, $templatePath);
        $tempContent = str_ireplace("{forum_rec_1}", $templateForumRecTopic, $tempContent);

        /******************  版块栏  ***********************/
        $templateForumBoardFileUrl = "forum/forum_default_list.html";
        $templateForumBoard = Template::Load($templateForumBoardFileUrl, $templateName, $templatePath);

        $forumPublicData = new ForumPublicData();
        $forumRank = 0;
        $arrRankOneList = $forumPublicData->GetListByForumRank($siteId, $forumRank);



        $forumRank = 1;
        $arrRankTwoList = $forumPublicData->GetListByForumRank($siteId, $forumRank);


        $tagId = "forum_".$siteId;
        $tagName = Template::DEFAULT_TAG_NAME;
        $tableIdName = BaseData::TableId_Forum;
        $parentIdName = "ParentId";

        $arrRankThreeList = null;
        $thirdTableIdName = null;
        $thirdParentIdName = null;

        Template::ReplaceList(
            $tempContent,
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










        /**
        if (count($arrRankOneList) > 0) {




            $forumListOneType0Template = Template::Load("forum/forum_default_list_one_0.html", $templateName, $templatePath);
            $forumListTwoType0Template = Template::Load("forum/forum_default_list_two_0.html", $templateName, $templatePath);

            $resultOneTemplate = "";
            //print_r($arrRankOneList);
            for ($i = 0; $i < count($arrRankOneList); $i++) {
                $rankOneForumId = intval($arrRankOneList[$i]["ForumId"]);
                $rankOneForumName = $arrRankOneList[$i]["ForumName"];
                $rankOneForumType = intval($arrRankOneList[$i]["ForumType"]);
                $rankOneState = intval($arrRankOneList[$i]["State"]);
                $rankOneSort = intval($arrRankOneList[$i]["Sort"]);
                $rankOneForumMode = intval($arrRankOneList[$i]["ForumMode"]);
                $rankOneForumAccess = intval($arrRankOneList[$i]["ForumAccess"]);
                $rankOneShowColumnCount = intval($arrRankOneList[$i]["ShowColumnCount"]);
                $rankOneForumNameFontColor = $arrRankOneList[$i]["ForumNameFontColor"];
                $rankOneForumNameFontBold = $arrRankOneList[$i]["ForumNameFontBold"];
                $rankOneForumNameFontSize = $arrRankOneList[$i]["ForumNameFontSize"];



                $forumOneTemplate = $forumListOneType0Template;
                $forumOneTemplate = str_ireplace("{f_ForumId}", $rankOneForumId, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_ForumName}", $rankOneForumName, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_State}", $rankOneState, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_Sort}", $rankOneSort, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_ForumNameFontColor}", $rankOneForumNameFontColor, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_ForumNameFontBold}", $rankOneForumNameFontBold, $forumOneTemplate);
                $forumOneTemplate = str_ireplace("{f_ForumNameFontSize}", $rankOneForumNameFontSize, $forumOneTemplate);

                if (count($arrRankTwoList) > 0) {
                    $resultTwoTemplate = "";
                    $rankTwoIndex = 0;
                    for ($j = 0; $j < count($arrRankTwoList); $j++) {
                        $rankTwoForumId = intval($arrRankTwoList[$j]["ForumId"]);
                        $rankTwoParentId = intval($arrRankTwoList[$j]["ParentId"]);
                        $rankTwoForumName = $arrRankTwoList[$j]["ForumName"];
                        $rankTwoForumInfo = $arrRankTwoList[$j]["ForumInfo"];
                        $rankTwoForumType = intval($arrRankTwoList[$j]["ForumType"]);
                        $rankTwoState = intval($arrRankTwoList[$j]["State"]);
                        $rankTwoSort = intval($arrRankTwoList[$j]["Sort"]);
                        $rankTwoNewCount = $arrRankTwoList[$j]["NewCount"];
                        $rankTwoTopicCount = $arrRankTwoList[$j]["TopicCount"];
                        $rankTwoPostCount = $arrRankTwoList[$j]["PostCount"];
                        
                        $rankTwoForumMode = intval($arrRankTwoList[$j]["ForumMode"]);
                        $rankTwoForumAccess = intval($arrRankTwoList[$j]["ForumAccess"]);
                        $rankTwoForumNameFontColor = $arrRankTwoList[$j]["ForumNameFontColor"];
                        $rankTwoForumNameFontBold = $arrRankTwoList[$j]["ForumNameFontBold"];
                        $rankTwoForumNameFontSize = $arrRankTwoList[$j]["ForumNameFontSize"];

                        if ($rankOneForumId === $rankTwoParentId) {
                            $rankTwoIndex++;
                            $forumTwoTemplate = $forumListTwoType0Template;
                            $forumTwoTemplate = str_ireplace("{f_ForumId}", $rankTwoForumId, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumName}", $rankTwoForumName, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumInfo}", $rankTwoForumInfo, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_State}", $rankTwoState, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_Sort}", $rankTwoSort, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_NewCount}", $rankTwoNewCount, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_TopicCount}", $rankTwoTopicCount, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_PostCount}", $rankTwoPostCount, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumNameFontColor}", $rankTwoForumNameFontColor, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumNameFontBold}", $rankTwoForumNameFontBold, $forumTwoTemplate);
                            $forumTwoTemplate = str_ireplace("{f_ForumNameFontSize}", $rankTwoForumNameFontSize, $forumTwoTemplate);

                            $itemFlag = "";
                            if ($rankOneShowColumnCount <= 0) {
                                $rankOneShowColumnCount = 3;
                            }
                            if ($rankOneShowColumnCount > 0) {
                                if ($rankOneShowColumnCount === 3) {
                                    if ($rankTwoIndex % $rankOneShowColumnCount === 0) {
                                        $itemFlag = "2";
                                    }
                                } elseif ($rankOneShowColumnCount === 4) {
                                    if ($rankTwoIndex % $rankOneShowColumnCount === 0) {
                                        $itemFlag = "2";
                                    }
                                }
                            }
                            $forumTwoTemplate = str_ireplace("{itemflag}", $itemFlag, $forumTwoTemplate);
                            $resultTwoTemplate = $resultTwoTemplate . $forumTwoTemplate;
                        }
                    }
                    $forumOneTemplate = str_ireplace("{forum_two_list}", $resultTwoTemplate, $forumOneTemplate);
                } else {
                    $forumOneTemplate = str_ireplace("{forum_two_list}", "", $forumOneTemplate);
                }
                $resultOneTemplate = $resultOneTemplate . $forumOneTemplate;
            }

            $templateForumBoard = str_ireplace("{forum_one_list}", $resultOneTemplate, $templateForumBoard);

            $tempContent = str_ireplace("{forum_list}", $templateForumBoard, $tempContent);
        } else {
            $tempContent = str_ireplace("{forum_list}", "", $tempContent);
        }
        **/

        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);
        return $tempContent;
    }

}

?>
