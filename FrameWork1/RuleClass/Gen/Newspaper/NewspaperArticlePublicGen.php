<?php

/**
 * 公开访问 电子报文章 生成类
 * @category iCMS
 * @package iCMS_FrameWork1_RuleClass_Gen_Newspaper
 * @author zhangchi
 */
class NewspaperArticlePublicGen extends NewspaperBasePublicGen
{

    /**
     * 引导方法
     * @return string 返回执行结果
     */
    public function GenPublic()
    {
        $result = "";
        $action = Control::GetRequest("a", "");
        switch ($action) {
            case "import":
                $result = self::Import();
                break;
            case "detail":
                $result = self::GenDetail();
                break;
            case "list":
                $result = self::GenList();
                break;
            case "get_newspaper_article_id_for_import":
                $result = self::GetNewspaperArticleIdForImport();
                break;
            case "add_hit_count":
                $result = self::AddHitCount();
                break;
        }
        return $result;
    }

    private function Import()
    {
        $removeXSS = false;
        $newspaperArticleId = -1;
        $authorityCode = str_ireplace("\r\n", "", Control::PostRequest("AuthorityCode", "", $removeXSS));
        $newspaperPageId = intval(str_ireplace("\r\n", "", Control::PostRequest("NewspaperPageId", 0, $removeXSS)));
        $newspaperArticleTitle = str_ireplace("\r\n", "", Control::PostRequest("NewspaperArticleTitle", "", $removeXSS));
        $newspaperArticleContent = str_ireplace("\r\n", "", Control::PostRequest("NewspaperArticleContent", "", $removeXSS));
        $newspaperArticleSubTitle = str_ireplace("\r\n", "", Control::PostRequest("NewspaperArticleSubTitle", "", $removeXSS));
        $newspaperArticleCiteTitle = str_ireplace("\r\n", "", Control::PostRequest("NewspaperArticleCiteTitle", "", $removeXSS));
        $publishType = str_ireplace("\r\n", "", Control::PostRequest("PublishType", "", $removeXSS));
        $published = str_ireplace("\r\n", "", Control::PostRequest("Published", "", $removeXSS));
        $informationId = str_ireplace("\r\n", "", Control::PostRequest("InformationId", "", $removeXSS));
        $source = str_ireplace("\r\n", "", Control::PostRequest("Source", "", $removeXSS));
        $author = str_ireplace("\r\n", "", Control::PostRequest("Author", "", $removeXSS));
        $column = str_ireplace("\r\n", "", Control::PostRequest("Column", "", $removeXSS));
        $picRemark = str_ireplace("\r\n", "", Control::PostRequest("PicRemark", "", $removeXSS));
        $next = str_ireplace("\r\n", "", Control::PostRequest("Next", "", $removeXSS));
        $previous = str_ireplace("\r\n", "", Control::PostRequest("Previous", "", $removeXSS));
        $no = str_ireplace("\r\n", "", Control::PostRequest("No", "", $removeXSS));
        $className = str_ireplace("\r\n", "", Control::PostRequest("ClassName", "", $removeXSS));
        $genre = str_ireplace("\r\n", "", Control::PostRequest("Genre", "", $removeXSS));
        $reprint = str_ireplace("\r\n", "", Control::PostRequest("Reprint", "", $removeXSS));
        $fileName = str_ireplace("\r\n", "", Control::PostRequest("FileName", "", $removeXSS));
        $abstractInfo = str_ireplace("\r\n", "", Control::PostRequest("AbstractInfo", "", $removeXSS));
        $wordCount = str_ireplace("\r\n", "", Control::PostRequest("WordCount", "", $removeXSS));
        $picMapping = str_ireplace("\r\n", "", Control::PostRequest("PicMapping", "", $removeXSS));

        if (
            $authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperPageId > 0 &&
            strlen($newspaperArticleTitle) > 0
        ) {
            $newspaperArticlePublicData = new NewspaperArticlePublicData();

            $newspaperArticleId = $newspaperArticlePublicData->CreateForImport(
                $newspaperPageId,
                $newspaperArticleTitle,
                $newspaperArticleContent,
                $newspaperArticleSubTitle,
                $newspaperArticleCiteTitle,
                $publishType,
                $published,
                $informationId,
                $source,
                $author,
                $column,
                $picRemark,
                $next,
                $previous,
                $no,
                $className,
                $genre,
                $reprint,
                $fileName,
                $abstractInfo,
                $wordCount,
                $picMapping
            );


            //删除缓冲
            parent::DelAllCache();
        }

        return $newspaperArticleId;
    }

    private function GenList()
    {
        $newspaperPageId = Control::GetRequest("newspaper_page_id", 0);
        //$newspaperId = Control::GetRequest("newspaper_id",0);
        //$channelId= Control::GetRequest("channel_id",0);
        $templateContent = "";

        if ($newspaperPageId > 0) {
            //$templateFileUrl = "newspaper/newspaper_article_list.html";
            //$templateName = "default";
            //$templatePath = "front_template";
            //$templateContent = Template::Load($templateFileUrl, $templateName, $templatePath);
            $templateContent = parent::GetDynamicTemplateContent("newspaper_article_list");
            parent::ReplaceFirst($templateContent);

            $newspaperPagePublicData = new NewspaperPagePublicData();
            $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);


            $newspaperPublicData = new NewspaperPublicData();
            $publishDate = $newspaperPublicData->GetPublishDate($newspaperId, true);
            $channelId = $newspaperPublicData->GetChannelId($newspaperId, true);


            $channelPublicData = new ChannelPublicData();
            $siteId = $channelPublicData->GetSiteId($channelId, true);

            parent::ReplaceSiteInfo($siteId, $templateContent);


            $newspaperPageName = $newspaperPagePublicData->GetNewspaperPageName($newspaperPageId, true);
            $newspaperPageNo = $newspaperPagePublicData->GetNewspaperPageNo($newspaperPageId, true);

            $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);
            $templateContent = str_ireplace("{NewspaperPageName}", $newspaperPageName, $templateContent);
            $templateContent = str_ireplace("{NewspaperPageNo}", $newspaperPageNo, $templateContent);
            $templateContent = str_ireplace("{NewspaperPageId}", $newspaperPageId, $templateContent);
            $templateContent = str_ireplace("{NewspaperId}", $newspaperId, $templateContent);
            $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);

            //版面选择

            $arrNewspaperPages = $newspaperPagePublicData->GetListForSelectPage($newspaperId);
            $listName = "newspaper_page";

            if (count($arrNewspaperPages) > 0) {
                Template::ReplaceList($templateContent, $arrNewspaperPages, $listName);
            } else {
                Template::RemoveCustomTag($templateContent, $listName);
            }

            $templateContent = parent::ReplaceTemplate($templateContent);


            parent::ReplaceEnd($templateContent);

        }
        return $templateContent;
    }

    private function GenDetail()
    {
        $result = "";
        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);


        if ($newspaperArticleId > 0) {
            $siteId = parent::GetSiteIdByDomain();
            $userId = Control::GetUserId();

            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $state = $newspaperArticlePublicData->GetState($newspaperArticleId, false);
            if ($state == 100) {

                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                exit;

            }


            $defaultTemp = "newspaper_article_detail";
            $templateContent = parent::GetDynamicTemplateContent(
                $defaultTemp,
                $siteId,
                "",
                $templateMode);

            /*******************页面级的缓存 begin********************** */

            $cacheDir = CACHE_PATH . DIRECTORY_SEPARATOR . 'default_page';
            $cacheFile = 'newspaper_article_detail_site_id_' . $siteId .
                '_aid_' . $newspaperArticleId .
                '_temp_' . $defaultTemp .
                '_mode_' . $templateMode;
            $withCache = true;

            //if ($IsAuthorizedUser || self::IsFreeReadByNewspaperArticleId($newspaperArticleId)) {
                if ($withCache) {

                    $pageCache = parent::GetCache($cacheDir, $cacheFile);

                    if ($pageCache === false) {
                        $result = self::getDetailTemplateContent(
                            $siteId, $newspaperArticleId, $templateContent);
                        parent::AddCache($cacheDir, $cacheFile, $result, 60);
                    } else {
                        $result = $pageCache;
                    }
                } else {
                    $result = self::getDetailTemplateContent(
                        $siteId, $newspaperArticleId, $templateContent);
                }
            //} else {
            //    $result = self::getDetailTemplateContent(
            //        $siteId, $newspaperArticleId, $templateContent);
            //}

            /*******************页面级的缓存 end  ********************** */

            parent::ReplaceUserInfoPanel($result, $siteId);

            /** 处理付费内容 ***/
            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $arrOne = $newspaperArticlePublicData->GetOne($newspaperArticleId);

            $isAuthorizedUser = self::IsAuthorizedUser($userId, $newspaperArticleId);

            //已经购买或者属于免费阅读
            if($isAuthorizedUser || self::IsFreeReadByNewspaperArticleId($newspaperArticleId)){
                $defaultTemp = "newspaper_article_buy_content";
                $newspaperArticleBuyContent = self::GetDynamicTemplateContent(
                    $defaultTemp,
                    $siteId,
                    "",
                    $templateMode);


                $result = str_ireplace("{NewsArticleBuyContent}", $newspaperArticleBuyContent, $result);


                Template::ReplaceOne($result, $arrOne);
                $result = parent::ReplaceTemplate($result);

            }else{

                //没有购买
                $newspaperPagePublicData = new NewspaperPagePublicData();
                $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
                $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);


                //去除图片标记
                Template::ReplaceCustomTag($result, "newspaper_article_" . $newspaperArticleId, "");
                Template::ReplaceCustomTag($result, "newspaper_article_slider" . $newspaperArticleId, "");


                if($userId>0){
                    $defaultTemp = "newspaper_article_not_buy_content";
                }
                else{
                    $defaultTemp = "newspaper_article_not_buy_content_not_login";
                }

                $newspaperArticleNotBuyContent = self::GetDynamicTemplateContent(
                    $defaultTemp,
                    $siteId,
                    "",
                    $templateMode);

                $result = str_ireplace("{NewsArticleBuyContent}", $newspaperArticleNotBuyContent, $result);
                $result = str_ireplace("{NewspaperId}", $newspaperId, $result);
                $result = str_ireplace("{NewspaperArticleId}", $newspaperArticleId, $result);
                $result = str_ireplace("{SiteId}", $siteId, $result);
                $refUrl = urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
                $result = str_ireplace("{LoginUrl}", "/default.php?mod=user&a=login&re_url=$refUrl", $result);
                Template::ReplaceOne($result, $arrOne);

            }
        }



        return $result;
    }

    private function getDetailTemplateContent(
        $siteId, $newspaperArticleId, $templateContent
    )
    {

        $templateContent = str_ireplace("{NewspaperArticleId}",
            $newspaperArticleId, $templateContent);

        parent::ReplaceFirst($templateContent);
        parent::ReplaceSiteInfo($siteId, $templateContent);


        $newspaperArticlePublicData = new NewspaperArticlePublicData();
        $channelId = $newspaperArticlePublicData->GetChannelId($newspaperArticleId, true);
        $templateContent = str_ireplace("{ChannelId}", $channelId, $templateContent);



        /**
        $IsAuthorizedUser = self::IsAuthorizedUser(Control::GetUserId(), $newspaperArticleId);
        if ($IsAuthorizedUser || self::IsFreeReadByNewspaperArticleId($newspaperArticleId)) {

            $arrOne["NewspaperArticleContent"] =
                str_ireplace("\n", "<br /><br />", $arrOne["NewspaperArticleContent"]);
            $arrOne["NewspaperArticleContent"] =
                str_ireplace("&lt;", "<", $arrOne["NewspaperArticleContent"]);
            $arrOne["NewspaperArticleContent"] =
                str_ireplace("&gt;", ">", $arrOne["NewspaperArticleContent"]);
        } else {
            Template::ReplaceCustomTag($templateContent, "newspaper_article_" . $newspaperArticleId, "");
            Template::ReplaceCustomTag($templateContent, "newspaper_article_slider" . $newspaperArticleId, "");
            $newspaperArticlePublicData = new NewspaperArticlePublicData();
            $newspaperPagePublicData = new NewspaperPagePublicData();
            $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
            $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);
            $arrOne["NewspaperArticleContent"] = "";
        }
        */




        //版面选择
        $newspaperPagePublicData = new NewspaperPagePublicData();
        $newspaperPageId = $newspaperArticlePublicData->GetNewspaperPageId($newspaperArticleId, true);
        $newspaperId = $newspaperPagePublicData->GetNewspaperId($newspaperPageId, true);

        $newspaperPageUploadFilePath = $newspaperPagePublicData->GetUploadFilePath($newspaperPageId, true);


        $newspaperPublicData = new NewspaperPublicData();
        $publishDate = $newspaperPublicData->GetPublishDate($newspaperId, true);

        $templateContent = str_ireplace("{PublishDate}", $publishDate, $templateContent);
        $templateContent = str_ireplace("{NewspaperPageUploadFilePath}", $newspaperPageUploadFilePath, $templateContent);
        $templateContent = str_ireplace("{CurrentPublishDate}", $publishDate, $templateContent);

        $arrNewspaperPages = $newspaperPagePublicData->GetListForSelectPage($newspaperId, true);
        $tagId = "newspaper_page";

        if (count($arrNewspaperPages) > 0) {
            Template::ReplaceList($templateContent, $arrNewspaperPages, $tagId);
        } else {
            Template::RemoveCustomTag($templateContent, $tagId);
        }

        //$templateContent = parent::ReplaceTemplate($templateContent);
        //左边导航菜单选择文章列表
        //默认只显示已发状态的新闻

        if (count($arrNewspaperPages) > 0) {
            $state = 0;
            $newspaperPageIds = "";
            foreach ($arrNewspaperPages as $page) {
                $newspaperPageIds .= "," . $page["NewspaperPageId"];
            }

            if (strpos($newspaperPageIds, ',') == 0) {
                $newspaperPageIds = substr($newspaperPageIds, 1);
            }

            $tagId = "newspaper_page_and_article";
            if (stripos($templateContent, $tagId) > 0) {
                $newspaperArticlePublicData = new NewspaperArticlePublicData();
                $arrNewspaperArticles = $newspaperArticlePublicData->GetListOfMultiPage($newspaperPageIds, $state);


                $tagName = Template::DEFAULT_TAG_NAME;
                $tableIdName = "NewspaperPageId";
                $parentIdName = "NewspaperPageId";
                Template::ReplaceList(
                    $templateContent,
                    $arrNewspaperPages,
                    $tagId,
                    $tagName,
                    $arrNewspaperArticles,
                    $tableIdName,
                    $parentIdName
                );
            }


        } else {
            $tagId = "newspaper_page_and_article";
            Template::RemoveCustomTag($templateContent, $tagId);
        }

        parent::ReplaceEnd($templateContent);

        return $templateContent;

    }


    private function GetNewspaperArticleIdForImport()
    {
        $result = -1;
        $removeXSS = false;
        $authorityCode = str_ireplace("\r\n", "", Control::PostRequest("AuthorityCode", "", $removeXSS));
        $newspaperPageId = intval(str_ireplace("\r\n", "", Control::PostRequest("NewspaperPageId", 0, $removeXSS)));
        $newspaperArticleTitle = str_ireplace("\r\n", "", Control::PostRequest("NewspaperArticleTitle", "", $removeXSS));

        if ($authorityCode == "C_S_W_B_E_P_A_P_E_R_I_M_P_O_R_T" &&
            $newspaperPageId > 0 &&
            strlen($newspaperArticleTitle) > 0
        ) {
            $newspaperArticlePublicData = new NewspaperArticlePublicData();

            $result = $newspaperArticlePublicData->GetNewspaperArticleIdForImport(
                $newspaperArticleTitle, $newspaperPageId
            );
        }
        return $result;
    }

    private function AddHitCount()
    {

        $result = -1;

        $newspaperArticleId = Control::GetRequest("newspaper_article_id", 0);

        if ($newspaperArticleId > 0) {

            $newspaperArticlePublicData = new NewspaperArticlePublicData();

            $result = $newspaperArticlePublicData->AddHitCount($newspaperArticleId);
        }


        return $result;

    }

} 