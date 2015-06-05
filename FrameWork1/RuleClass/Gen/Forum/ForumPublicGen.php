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
        $siteId = parent::GetSiteIdByDomain();

        /*******************页面级的缓存 begin********************** */

        $templateMode = 0;
        $defaultTemp = "forum_default";
        $tempContent = parent::GetDynamicTemplateContent(
            $defaultTemp, $siteId, "", $templateMode);

        //$templateFileUrl = "forum/forum_default.html";
        //$templateName = "default";
        //$templatePath = "front_template";
        //$tempContent = Template::Load($templateFileUrl, $templateName, $templatePath);


        $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'forum_page';
        $cacheFile = 'site_id_' . $siteId . '_mode_' . $templateMode;
        $withCache = true;
        if($withCache){
            $pageCache = DataCache::Get($cacheDir . DIRECTORY_SEPARATOR . $cacheFile);

            if ($pageCache === false) {
                $result = self::getDefaultTemplateContent($siteId, $tempContent);
                DataCache::Set($cacheDir, $cacheFile, $result);
            } else {
                $result = $pageCache;
            }
        }else{
            $result = self::getDefaultTemplateContent($siteId, $tempContent);
        }

        /*******************页面级的缓存 end  ********************** */

        return $result;
    }

    private function getDefaultTemplateContent($siteId, $tempContent){

        parent::ReplaceFirstForForum($tempContent);

        $tempContent = str_ireplace("{SiteId}", $siteId, $tempContent);

        $forumPublicData = new ForumPublicData();
        $forumRank = 0;
        $arrRankOneList = $forumPublicData->GetListByForumRank($siteId, $forumRank, true);

        $forumRank = 1;
        $arrRankTwoList = $forumPublicData->GetListByForumRank($siteId, $forumRank, true);



        $tagId = "forum_".$siteId;
        $tagName = Template::DEFAULT_TAG_NAME;
        $tableIdName = BaseData::TableId_Forum;
        $parentIdName = "ParentId";

        $arrRankThreeList = null;
        $thirdTableIdName = null;
        $thirdParentIdName = null;

        $childArrayFieldName = "LastPostInfo";
        $thirdArrayFieldName = "";

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
            $thirdParentIdName,
            $childArrayFieldName,
            $thirdArrayFieldName
        );


        parent::ReplaceTemplate($tempContent);

        parent::ReplaceEndForForum($tempContent);
        parent::ReplaceSiteConfig($siteId, $tempContent);

        /*******************过滤字符 begin********************** */

        $multiFilterContent = array();
        $multiFilterContent[0] = $tempContent;
        $useArea = 4; //过滤范围 4:评论
        $stop = FALSE; //是否停止执行
        $filterContent = null;
        $stopWord = parent::DoFilter($siteId, $useArea, $stop, $filterContent, $multiFilterContent);
        $tempContent = $multiFilterContent[0];

        /*******************过滤字符 end  ********************** */

        return $tempContent;
    }
}

?>
